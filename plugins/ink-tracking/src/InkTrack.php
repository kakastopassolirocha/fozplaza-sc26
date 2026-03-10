<?php

namespace INKTRACK\src;

use WP_REST_Request;
use WP_REST_Response;
use WP_User;

if (!defined('ABSPATH')) {
    exit;
}

class InkTrack
{
    private array $config;
    private string $pixel_id;
    private string $access_token;
    private string $api_version;
    private bool $debug;

    public function __construct()
    {
        // Carrega configurações via filtro
        $this->config = apply_filters('inktrack/config', [
            'pixel_id' => '',
            'access_token' => '',
            'api_version' => 'v24.0',
            'cookie_name' => 'INKTRACK_external_id',
            'debug' => defined('WP_DEBUG') && WP_DEBUG,
        ]);

        $this->pixel_id = $this->config['pixel_id'];
        $this->access_token = $this->config['access_token'];
        $this->api_version = $this->config['api_version'];
        $this->debug = $this->config['debug'] ?? false;

        $this->init_hooks();
    }

    private function init_hooks(): void
    {
        // Enfileira scripts e localiza dados
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        
        // Injeta o Pixel e dispara PageView no head
        add_action('wp_head', [$this, 'render_pixel_and_pageview'], 5);
        
        // Registra endpoint para receber eventos do front-end (CAPI)
        add_action('rest_api_init', [$this, 'register_rest_routes']);

        // Admin Menu & Logs
        add_action('admin_menu', [$this, 'register_admin_menu']);
        add_action('admin_init', [$this, 'create_logs_table']);
    }

    /**
     * Enfileira o script JS principal.
     */
    public function enqueue_scripts(): void
    {
        $script_url = get_stylesheet_directory_uri() . '/plugins/ink-tracking/assets/js/inktrack.js';
        $script_path = get_stylesheet_directory() . '/plugins/ink-tracking/assets/js/inktrack.js';
        $version = file_exists($script_path) ? (string) filemtime($script_path) : '2.0.0';

        wp_register_script('inktrack-js', $script_url, [], $version, true);

        // Dados de configuração para o JS
        $js_config = [
            'pixelId' => $this->pixel_id,
            'endpoint' => rest_url('inktrack/v1/event'),
            'nonce' => wp_create_nonce('wp_rest'),
            'debug' => $this->debug,
            'userData' => $this->get_current_user_data_clear() // Dados claros para o Pixel (Client-side)
        ];

        wp_localize_script('inktrack-js', 'INKTRACK_CONFIG', $js_config);
        wp_enqueue_script('inktrack-js');
    }

    /**
     * Renderiza o código base do Pixel e dispara o PageView (Server e Client).
     */
    public function render_pixel_and_pageview(): void
    {
        if (is_admin() || wp_doing_ajax() || wp_is_json_request()) {
            return;
        }

        if (empty($this->pixel_id)) {
            return;
        }

        // Gera ID único para desduplicação (Server e Client devem usar o mesmo)
        $event_id = $this->generate_event_id('pageview');
        
        // Garante que temos um External ID
        $external_id = $this->get_or_create_external_id();

        // Dados do usuário (se logado)
        $user_data_clear = $this->get_current_user_data_clear();
        $user_data_hashed = $this->get_current_user_data_hashed();

        // Dispara PageView via CAPI (Server-Side)
        $this->send_capi_event('PageView', $event_id, [], $user_data_hashed);

        // Prepara JSON para o Pixel (Client-Side)
        $pixel_init_data = !empty($user_data_clear) ? wp_json_encode($user_data_clear) : '{}';
        
        ?>
        <!-- Meta Pixel Code (INKTRACK Refactored) -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            
            // Init com Advanced Matching (se disponível)
            fbq('init', '<?php echo esc_js($this->pixel_id); ?>', <?php echo $pixel_init_data; ?>);
            
            // PageView com EventID para desduplicação
            fbq('track', 'PageView', { eventID: '<?php echo esc_js($event_id); ?>' });
        </script>
        <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=<?php echo esc_js($this->pixel_id); ?>&ev=PageView&noscript=1"
        /></noscript>
        <!-- End Meta Pixel Code -->
        <?php
    }

    /**
     * Registra rota REST para receber eventos do JS.
     */
    public function register_rest_routes(): void
    {
        register_rest_route('inktrack/v1', '/event', [
            'methods' => 'POST',
            'callback' => [$this, 'handle_rest_event'],
            'permission_callback' => '__return_true', // Aberto, pois vem do front público
        ]);
    }

    /**
     * Processa o evento recebido do JS e envia para CAPI.
     */
    public function handle_rest_event(WP_REST_Request $request): WP_REST_Response
    {
        $params = $request->get_json_params();

        if (empty($params['eventName']) || empty($params['eventID'])) {
            return new WP_REST_Response(['success' => false, 'message' => 'Missing parameters'], 400);
        }

        $event_name = sanitize_text_field($params['eventName']);
        $event_id = sanitize_text_field($params['eventID']);
        $custom_data = isset($params['params']) && is_array($params['params']) ? $params['params'] : [];
        
        // Dados do usuário enviados pelo JS (Client-Side)
        // Precisamos fazer hash desses dados antes de enviar para o CAPI
        $user_data_raw = isset($params['userData']) && is_array($params['userData']) ? $params['userData'] : [];
        
        // Se usuário estiver logado, mescla com dados do banco (prioridade para o banco ou para o JS? 
        // Geralmente JS tem dados mais frescos de um formulário, mas banco é mais confiável se logado.
        // Vamos mesclar, priorizando o que veio do JS se preenchido, senão fallback para banco).
        $db_user_data = $this->get_current_user_data_clear(); // Pega limpo para poder fazer hash junto
        $merged_user_data = array_merge($db_user_data, $user_data_raw);
        
        // Prepara dados para CAPI (Hashing)
        $user_data_hashed = $this->hash_user_data($merged_user_data);

        // Envia para CAPI
        $success = $this->send_capi_event($event_name, $event_id, $custom_data, $user_data_hashed);

        return new WP_REST_Response(['success' => $success], 200);
    }

    /**
     * Envia evento para a API de Conversões da Meta.
     */
    private function send_capi_event(string $event_name, string $event_id, array $custom_data, array $user_data_hashed): bool
    {
        if (empty($this->access_token) || empty($this->pixel_id)) {
            return false;
        }

        // Adiciona dados de contexto obrigatórios/recomendados
        $user_data_hashed['client_ip_address'] = $this->get_client_ip();
        $user_data_hashed['client_user_agent'] = $this->get_user_agent();
        
        // Adiciona fbc e fbp se existirem nos cookies
        $fbc = $this->get_cookie('_fbc');
        if ($fbc) $user_data_hashed['fbc'] = $fbc;
        
        $fbp = $this->get_cookie('_fbp');
        if ($fbp) $user_data_hashed['fbp'] = $fbp;

        // External ID
        $external_id = $this->get_or_create_external_id();
        if ($external_id) {
            $user_data_hashed['external_id'] = hash('sha256', $external_id);
        }

        $payload = [
            'data' => [
                [
                    'event_name' => $event_name,
                    'event_time' => time(),
                    'event_id' => $event_id,
                    'event_source_url' => $this->get_current_url(),
                    'action_source' => 'website',
                    'user_data' => $user_data_hashed,
                    'custom_data' => $custom_data,
                ]
            ]
        ];

        // Test Event Code (se configurado, útil para testar no Events Manager)
        // $payload['test_event_code'] = 'TEST12345'; 

        $url = "https://graph.facebook.com/{$this->api_version}/{$this->pixel_id}/events?access_token={$this->access_token}";

        $response = wp_remote_post($url, [
            'body' => wp_json_encode($payload),
            'headers' => ['Content-Type' => 'application/json'],
            'timeout' => 5, // Timeout curto para não travar muito
            'blocking' => true // Blocking para garantir envio, mas poderia ser false se performance for crítica
        ]);

        if (is_wp_error($response)) {
            $this->log('CAPI Error', $response->get_error_message());
            return false;
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);

        // Log to DB
        $this->log_to_db($event_name, $event_id, $payload, $code, $body);

        if ($code >= 400) {
            $this->log('CAPI Failed', ['code' => $code, 'body' => $body]);
            return false;
        }

        return true;
    }

    /**
     * Retorna dados do usuário logado SEM hash (para Pixel Client-side).
     */
    private function get_current_user_data_clear(): array
    {
        if (!is_user_logged_in()) {
            return [];
        }

        $user = wp_get_current_user();
        $data = [];

        // Email
        if (!empty($user->user_email)) {
            $data['em'] = strtolower(trim($user->user_email));
        }

        // Nome e Sobrenome
        if (!empty($user->first_name)) {
            $data['fn'] = strtolower(trim($user->first_name));
        }
        if (!empty($user->last_name)) {
            $data['ln'] = strtolower(trim($user->last_name));
        }

        // Telefone (ACF Fields)
        // Campos: lead_ddi, lead_whatsapp_number
        if (function_exists('get_field')) {
            $ddi = get_field('lead_ddi', 'user_' . $user->ID);
            $number = get_field('lead_whatsapp_number', 'user_' . $user->ID);

            if ($ddi && $number) {
                // Remove tudo que não for número
                $full_phone = preg_replace('/[^0-9]/', '', $ddi . $number);
                if (!empty($full_phone)) {
                    $data['ph'] = $full_phone;
                }
            }
        }

        // Outros campos podem ser adicionados aqui (cidade, estado, etc) se disponíveis no WP

        return $data;
    }

    /**
     * Retorna dados do usuário logado COM hash (para CAPI).
     */
    private function get_current_user_data_hashed(): array
    {
        $clear_data = $this->get_current_user_data_clear();
        return $this->hash_user_data($clear_data);
    }

    /**
     * Aplica SHA256 nos campos sensíveis conforme documentação da Meta.
     */
    private function hash_user_data(array $data): array
    {
        $hashed = [];
        $fields_to_hash = ['em', 'ph', 'fn', 'ln', 'ge', 'db', 'ct', 'st', 'zp', 'country'];

        foreach ($data as $key => $value) {
            if (in_array($key, $fields_to_hash)) {
                // Normalização antes do hash
                $value = strtolower(trim($value));
                if ($key === 'ph') {
                    // Remove zeros à esquerda e símbolos, mas já fizemos isso no get_clear
                    $value = preg_replace('/[^0-9]/', '', $value); 
                }
                $hashed[$key] = hash('sha256', $value);
            } else {
                $hashed[$key] = $value;
            }
        }

        return $hashed;
    }

    // --- Helpers ---

    private function get_client_ip(): string
    {
        // Cloudflare
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        }
        // Forwarded
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        }
        return $_SERVER['REMOTE_ADDR'] ?? '';
    }

    private function get_user_agent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    private function get_current_url(): string
    {
        return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    private function get_cookie(string $name): ?string
    {
        return isset($_COOKIE[$name]) ? sanitize_text_field($_COOKIE[$name]) : null;
    }

    private function get_or_create_external_id(): string
    {
        $cookie_name = $this->config['cookie_name'];
        if (isset($_COOKIE[$cookie_name])) {
            return sanitize_text_field($_COOKIE[$cookie_name]);
        }

        // Gera novo ID
        $new_id = uniqid('ink_', true);
        
        // Define cookie (730 dias = 2 anos)
        setcookie($cookie_name, $new_id, time() + (86400 * 730), "/", "", true, true);
        $_COOKIE[$cookie_name] = $new_id; // Disponível imediatamente no script atual

        return $new_id;
    }

    private function generate_event_id(string $prefix): string
    {
        return $prefix . '.' . uniqid() . '.' . time();
    }

    // --- Admin & Logging ---

    public function create_logs_table(): void
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'inktrack_logs';
        
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                event_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                event_name varchar(255) NOT NULL,
                event_id varchar(255) NOT NULL,
                payload longtext NOT NULL,
                response_code int(3) NOT NULL,
                response_body longtext NOT NULL,
                PRIMARY KEY  (id)
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    public function register_admin_menu(): void
    {
        add_menu_page(
            'InkTrack Logs',
            'InkTrack Logs',
            'manage_options',
            'inktrack-logs',
            [$this, 'render_logs_page'],
            'dashicons-chart-line',
            99
        );
    }

    public function render_logs_page(): void
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'inktrack_logs';
        
        // Limpar logs
        if (isset($_POST['inktrack_clear_logs']) && check_admin_referer('inktrack_clear_logs_action')) {
            $wpdb->query("TRUNCATE TABLE $table_name");
            echo '<div class="updated"><p>Logs limpos com sucesso.</p></div>';
        }

        $logs = $wpdb->get_results("SELECT * FROM $table_name ORDER BY event_time DESC LIMIT 100");

        ?>
        <div class="wrap">
            <h1>InkTrack CAPI Logs</h1>
            <form method="post" style="margin-bottom: 20px;">
                <?php wp_nonce_field('inktrack_clear_logs_action'); ?>
                <input type="submit" name="inktrack_clear_logs" class="button button-secondary" value="Limpar Logs" onclick="return confirm('Tem certeza?');">
            </form>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th width="150">Data/Hora</th>
                        <th width="150">Evento</th>
                        <th width="200">Event ID</th>
                        <th>Payload (Envio)</th>
                        <th width="100">Status</th>
                        <th>Resposta (Meta)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr><td colspan="6">Nenhum log encontrado.</td></tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?php echo esc_html($log->event_time); ?></td>
                                <td><strong><?php echo esc_html($log->event_name); ?></strong></td>
                                <td><code><?php echo esc_html($log->event_id); ?></code></td>
                                <td>
                                    <details>
                                        <summary>Ver Payload</summary>
                                        <pre style="font-size: 10px; overflow: auto; max-height: 200px;"><?php echo esc_html(json_encode(json_decode($log->payload), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                                    </details>
                                </td>
                                <td>
                                    <?php 
                                    $color = $log->response_code >= 400 ? 'red' : 'green';
                                    echo "<span style='color: $color; font-weight: bold;'>" . esc_html($log->response_code) . "</span>"; 
                                    ?>
                                </td>
                                <td>
                                    <details>
                                        <summary>Ver Resposta</summary>
                                        <pre style="font-size: 10px; overflow: auto; max-height: 200px;"><?php echo esc_html(json_encode(json_decode($log->response_body), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                                    </details>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    private function log_to_db(string $event_name, string $event_id, array $payload, int $code, string $body): void
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'inktrack_logs';
        
        // Verifica se a tabela existe antes de inserir (caso o admin_init não tenha rodado ainda por algum motivo)
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_logs_table();
        }

        $wpdb->insert(
            $table_name,
            [
                'event_time' => current_time('mysql'),
                'event_name' => $event_name,
                'event_id' => $event_id,
                'payload' => wp_json_encode($payload),
                'response_code' => $code,
                'response_body' => $body
            ]
        );
    }

    private function log(string $message, $data = null): void
    {
        if ($this->debug) {
            error_log("[INKTRACK] $message: " . print_r($data, true));
        }
    }
}

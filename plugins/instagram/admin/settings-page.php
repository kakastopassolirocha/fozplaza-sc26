<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('fozplaza_instagram_get_admin_slug')) {
    function fozplaza_instagram_get_admin_slug() {
        return 'fozplaza-instagram-widget';
    }
}

if (!function_exists('fozplaza_instagram_register_admin_menu')) {
    function fozplaza_instagram_register_admin_menu() {
        add_menu_page(
            'Instagram Widget',
            'Instagram Widget',
            'manage_options',
            fozplaza_instagram_get_admin_slug(),
            'fozplaza_instagram_render_settings_page',
            'dashicons-format-image',
            59
        );
    }
}

if (!function_exists('fozplaza_instagram_register_admin_settings')) {
    function fozplaza_instagram_register_admin_settings() {
        register_setting(
            'fozplaza_instagram_settings_group',
            fozplaza_instagram_get_option_key(),
            array(
                'type' => 'array',
                'sanitize_callback' => 'fozplaza_instagram_sanitize_settings',
                'default' => array(),
            )
        );
    }
}

if (!function_exists('fozplaza_instagram_sanitize_settings')) {
    function fozplaza_instagram_sanitize_settings($input) {
        $input = is_array($input) ? $input : array();
        $current = get_option(fozplaza_instagram_get_option_key(), array());
        $current = is_array($current) ? $current : array();

        $current_access_token = isset($current['access_token']) ? preg_replace('/\s+/', '', trim((string) $current['access_token'])) : '';

        $sanitized = array(
            'access_token' => isset($input['access_token']) ? preg_replace('/\s+/', '', trim((string) $input['access_token'])) : '',
            'user_id' => isset($input['user_id']) ? sanitize_text_field((string) $input['user_id']) : '',
            'profile_url' => isset($input['profile_url']) ? esc_url_raw((string) $input['profile_url']) : '',
            'api_version' => isset($input['api_version']) ? trim((string) $input['api_version']) : 'v21.0',
            'cache_ttl' => max(MINUTE_IN_SECONDS, absint(isset($input['cache_ttl']) ? $input['cache_ttl'] : 1800)),
            'default_limit' => max(1, min(12, absint(isset($input['default_limit']) ? $input['default_limit'] : 8))),
            'widget_layout' => isset($input['widget_layout']) && in_array($input['widget_layout'], array('1', '2'), true) ? $input['widget_layout'] : '1',
            'debug' => !empty($input['debug']) ? 1 : 0,
        );

        if ($sanitized['api_version'] === '') {
            $sanitized['api_version'] = 'v21.0';
        } elseif (strpos($sanitized['api_version'], 'v') !== 0) {
            $sanitized['api_version'] = 'v' . $sanitized['api_version'];
        }

        $token_meta_keys = array(
            'token_saved_at',
            'token_saved_ts',
            'token_last_refresh_at',
            'token_last_refresh_ts',
            'token_last_attempt_at',
            'token_last_attempt_ts',
            'token_expires_at',
            'token_expires_in',
            'token_refresh_status',
            'token_refresh_message',
            'token_refresh_source',
            'token_refresh_next_at',
        );
        foreach ($token_meta_keys as $meta_key) {
            if (array_key_exists($meta_key, $current)) {
                $sanitized[$meta_key] = $current[$meta_key];
            }
        }

        $token_changed = ($current_access_token !== $sanitized['access_token']);
        if ($token_changed) {
            $now_mysql = current_time('mysql');
            $now_ts = time();

            $sanitized['token_saved_at'] = $now_mysql;
            $sanitized['token_saved_ts'] = $now_ts;
            $sanitized['token_last_refresh_at'] = '';
            $sanitized['token_last_refresh_ts'] = 0;
            $sanitized['token_last_attempt_at'] = '';
            $sanitized['token_last_attempt_ts'] = 0;
            $sanitized['token_expires_at'] = '';
            $sanitized['token_expires_in'] = 0;
            $sanitized['token_refresh_source'] = 'admin_save';
            $sanitized['token_refresh_next_at'] = '';

            if ($sanitized['access_token'] === '') {
                $sanitized['token_refresh_status'] = 'empty';
                $sanitized['token_refresh_message'] = 'Token removido no painel.';
            } else {
                $sanitized['token_refresh_status'] = 'pending';
                $sanitized['token_refresh_message'] = 'Token atualizado no painel. Aguarde a proxima janela de refresh.';
            }
        }

        $cache_sensitive_keys = array('access_token', 'user_id', 'api_version', 'default_limit');
        $must_flush_cache = false;
        foreach ($cache_sensitive_keys as $key) {
            $current_value = isset($current[$key]) ? (string) $current[$key] : '';
            $new_value = isset($sanitized[$key]) ? (string) $sanitized[$key] : '';
            if ($current_value !== $new_value) {
                $must_flush_cache = true;
                break;
            }
        }

        if ($must_flush_cache) {
            fozplaza_instagram_flush_cache();
        }

        add_settings_error(
            'fozplaza_instagram_messages',
            'fozplaza_instagram_saved',
            'Configuracoes do widget Instagram salvas com sucesso.',
            'updated'
        );

        return $sanitized;
    }
}

if (!function_exists('fozplaza_instagram_render_settings_page')) {
    function fozplaza_instagram_render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $settings = fozplaza_instagram_get_config();
        $saved_settings = function_exists('fozplaza_instagram_get_saved_settings')
            ? fozplaza_instagram_get_saved_settings()
            : get_option(fozplaza_instagram_get_option_key(), array());
        $saved_settings = is_array($saved_settings) ? $saved_settings : array();

        $token_refresh_status = !empty($saved_settings['token_refresh_status'])
            ? sanitize_key((string) $saved_settings['token_refresh_status'])
            : 'unknown';
        $token_refresh_message = !empty($saved_settings['token_refresh_message'])
            ? sanitize_text_field((string) $saved_settings['token_refresh_message'])
            : 'Sem historico de refresh ainda.';
        $token_last_refresh_at = !empty($saved_settings['token_last_refresh_at'])
            ? sanitize_text_field((string) $saved_settings['token_last_refresh_at'])
            : '-';
        $token_last_attempt_at = !empty($saved_settings['token_last_attempt_at'])
            ? sanitize_text_field((string) $saved_settings['token_last_attempt_at'])
            : '-';
        $token_expires_at = !empty($saved_settings['token_expires_at'])
            ? sanitize_text_field((string) $saved_settings['token_expires_at'])
            : '-';
        $token_refresh_next_at = !empty($saved_settings['token_refresh_next_at'])
            ? sanitize_text_field((string) $saved_settings['token_refresh_next_at'])
            : '-';
        $token_refresh_source = !empty($saved_settings['token_refresh_source'])
            ? sanitize_key((string) $saved_settings['token_refresh_source'])
            : '-';

        $token_type = !empty($settings['access_token'])
            ? fozplaza_instagram_detect_token_type((string) $settings['access_token'])
            : 'none';

        $cron_hook = function_exists('fozplaza_instagram_get_refresh_cron_hook')
            ? fozplaza_instagram_get_refresh_cron_hook()
            : '';
        $next_cron_ts = $cron_hook !== '' ? wp_next_scheduled($cron_hook) : false;
        $next_cron_at = $next_cron_ts ? wp_date('Y-m-d H:i:s', (int) $next_cron_ts, wp_timezone()) : 'nao agendado';

        $status_labels = array(
            'success' => 'Sucesso',
            'error' => 'Erro',
            'pending' => 'Pendente',
            'skipped' => 'Ignorado',
            'empty' => 'Sem token',
            'unknown' => 'Sem historico',
        );
        $status_label = isset($status_labels[$token_refresh_status]) ? $status_labels[$token_refresh_status] : ucfirst($token_refresh_status);
        ?>
        <div class="wrap">
            <h1>Instagram Widget</h1>
            <p>Configure aqui os dados da API e o comportamento do widget Instagram da Home.</p>

            <?php
            settings_errors('fozplaza_instagram_messages');
            if (!empty($_GET['foz_ig_cache_cleared'])) {
                echo '<div class="notice notice-success is-dismissible"><p>Cache do Instagram limpo com sucesso.</p></div>';
            }
            if (!empty($_GET['foz_ig_token_refresh'])) {
                $manual_refresh_status = sanitize_key((string) $_GET['foz_ig_token_refresh']);
                if ($manual_refresh_status === 'success') {
                    echo '<div class="notice notice-success is-dismissible"><p>Token renovado manualmente com sucesso.</p></div>';
                } elseif ($manual_refresh_status === 'skipped') {
                    echo '<div class="notice notice-warning is-dismissible"><p>Refresh manual ignorado pela regra de janela do token.</p></div>';
                } else {
                    echo '<div class="notice notice-error is-dismissible"><p>Nao foi possivel renovar o token agora. Verifique o status abaixo.</p></div>';
                }
            }
            ?>

            <form method="post" action="options.php">
                <?php settings_fields('fozplaza_instagram_settings_group'); ?>
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="foz_ig_access_token">Access Token</label></th>
                            <td>
                                <textarea id="foz_ig_access_token" class="large-text code" rows="4"
                                    name="<?php echo esc_attr(fozplaza_instagram_get_option_key()); ?>[access_token]"><?php echo esc_textarea((string) $settings['access_token']); ?></textarea>
                                <p class="description">Token de acesso da API Instagram/Meta.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="foz_ig_user_id">User ID</label></th>
                            <td>
                                <input id="foz_ig_user_id" class="regular-text code" type="text"
                                    name="<?php echo esc_attr(fozplaza_instagram_get_option_key()); ?>[user_id]"
                                    value="<?php echo esc_attr((string) $settings['user_id']); ?>" />
                                <p class="description">Para token <code>IG...</code>, prefira deixar vazio para usar <code>/me/media</code>.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="foz_ig_profile_url">URL do perfil</label></th>
                            <td>
                                <input id="foz_ig_profile_url" class="regular-text" type="url"
                                    name="<?php echo esc_attr(fozplaza_instagram_get_option_key()); ?>[profile_url]"
                                    value="<?php echo esc_attr((string) $settings['profile_url']); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="foz_ig_api_version">Versao da API</label></th>
                            <td>
                                <input id="foz_ig_api_version" class="small-text code" type="text"
                                    name="<?php echo esc_attr(fozplaza_instagram_get_option_key()); ?>[api_version]"
                                    value="<?php echo esc_attr((string) $settings['api_version']); ?>" />
                                <p class="description">Exemplo: <code>v21.0</code></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="foz_ig_cache_ttl">Cache TTL (segundos)</label></th>
                            <td>
                                <input id="foz_ig_cache_ttl" class="small-text" type="number" min="60" step="60"
                                    name="<?php echo esc_attr(fozplaza_instagram_get_option_key()); ?>[cache_ttl]"
                                    value="<?php echo esc_attr((string) $settings['cache_ttl']); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="foz_ig_default_limit">Quantidade de posts</label></th>
                            <td>
                                <input id="foz_ig_default_limit" class="small-text" type="number" min="1" max="12"
                                    name="<?php echo esc_attr(fozplaza_instagram_get_option_key()); ?>[default_limit]"
                                    value="<?php echo esc_attr((string) $settings['default_limit']); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="foz_ig_widget_layout">Layout do Widget</label></th>
                            <td>
                                <?php $current_layout = !empty($settings['widget_layout']) ? $settings['widget_layout'] : '1'; ?>
                                <select id="foz_ig_widget_layout" name="<?php echo esc_attr(fozplaza_instagram_get_option_key()); ?>[widget_layout]">
                                    <option value="1" <?php selected($current_layout, '1'); ?>>Layout 1 — Grade contida</option>
                                    <option value="2" <?php selected($current_layout, '2'); ?>>Layout 2 — Full-width (imagens grandes)</option>
                                </select>
                                <p class="description">Escolha o modelo visual do widget Instagram na Home.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="foz_ig_debug">Debug visual</label></th>
                            <td>
                                <label for="foz_ig_debug">
                                    <input id="foz_ig_debug" type="checkbox"
                                        name="<?php echo esc_attr(fozplaza_instagram_get_option_key()); ?>[debug]"
                                        value="1" <?php checked(!empty($settings['debug'])); ?> />
                                    Ativar painel de debug visual (somente super_admin).
                                </label>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <?php submit_button('Salvar configuracoes'); ?>
            </form>

            <hr />

            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('fozplaza_instagram_flush_cache'); ?>
                <input type="hidden" name="action" value="fozplaza_instagram_flush_cache">
                <?php submit_button('Limpar cache do widget', 'secondary', 'submit', false); ?>
            </form>

            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="margin-top:12px;">
                <?php wp_nonce_field('fozplaza_instagram_manual_refresh_token'); ?>
                <input type="hidden" name="action" value="fozplaza_instagram_manual_refresh_token">
                <?php submit_button('Renovar token agora', 'secondary', 'submit', false); ?>
            </form>

            <hr />

            <h2>Status do Token</h2>
            <table class="widefat striped" style="max-width:980px;">
                <tbody>
                    <tr>
                        <th style="width:280px;">Ultimo status</th>
                        <td><?php echo esc_html($status_label); ?></td>
                    </tr>
                    <tr>
                        <th>Mensagem</th>
                        <td><?php echo esc_html($token_refresh_message); ?></td>
                    </tr>
                    <tr>
                        <th>Tipo de token detectado</th>
                        <td><?php echo esc_html($token_type); ?></td>
                    </tr>
                    <tr>
                        <th>Ultimo refresh concluido</th>
                        <td><?php echo esc_html($token_last_refresh_at); ?></td>
                    </tr>
                    <tr>
                        <th>Ultima tentativa</th>
                        <td><?php echo esc_html($token_last_attempt_at); ?></td>
                    </tr>
                    <tr>
                        <th>Expira em</th>
                        <td><?php echo esc_html($token_expires_at); ?></td>
                    </tr>
                    <tr>
                        <th>Proximo refresh interno estimado</th>
                        <td><?php echo esc_html($token_refresh_next_at); ?></td>
                    </tr>
                    <tr>
                        <th>Proximo WP-Cron agendado</th>
                        <td><?php echo esc_html($next_cron_at); ?></td>
                    </tr>
                    <tr>
                        <th>Origem do ultimo refresh</th>
                        <td><?php echo esc_html($token_refresh_source); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
    }
}

if (!function_exists('fozplaza_instagram_admin_flush_cache')) {
    function fozplaza_instagram_admin_flush_cache() {
        if (!current_user_can('manage_options')) {
            wp_die('Permissao negada.');
        }

        check_admin_referer('fozplaza_instagram_flush_cache');
        fozplaza_instagram_flush_cache();

        $redirect_url = add_query_arg(
            array(
                'page' => fozplaza_instagram_get_admin_slug(),
                'foz_ig_cache_cleared' => 1,
            ),
            admin_url('admin.php')
        );

        wp_safe_redirect($redirect_url);
        exit;
    }
}

if (!function_exists('fozplaza_instagram_admin_manual_refresh_token')) {
    function fozplaza_instagram_admin_manual_refresh_token() {
        if (!current_user_can('manage_options')) {
            wp_die('Permissao negada.');
        }

        check_admin_referer('fozplaza_instagram_manual_refresh_token');

        $result = function_exists('fozplaza_instagram_refresh_access_token')
            ? fozplaza_instagram_refresh_access_token(true, 'manual')
            : array(
                'status' => 'error',
                'message' => 'Funcao de refresh indisponivel.',
            );

        $status = !empty($result['status']) ? sanitize_key((string) $result['status']) : 'error';
        $redirect_url = add_query_arg(
            array(
                'page' => fozplaza_instagram_get_admin_slug(),
                'foz_ig_token_refresh' => $status,
            ),
            admin_url('admin.php')
        );

        wp_safe_redirect($redirect_url);
        exit;
    }
}

add_action('admin_menu', 'fozplaza_instagram_register_admin_menu');
add_action('admin_init', 'fozplaza_instagram_register_admin_settings');
add_action('admin_post_fozplaza_instagram_flush_cache', 'fozplaza_instagram_admin_flush_cache');
add_action('admin_post_fozplaza_instagram_manual_refresh_token', 'fozplaza_instagram_admin_manual_refresh_token');

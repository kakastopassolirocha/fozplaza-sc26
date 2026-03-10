<?php
function get_real_client_ip() {
    // 1) Se estiver atrás do Cloudflare
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }

    // 2) Se o proxy enviar HTTP_X_FORWARDED_FOR
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    // 3) Se o proxy enviar X-Real-IP
    if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
        return $_SERVER['HTTP_X_REAL_IP'];
    }

    // 4) Se estiver usando X-Forwarded-For
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Pode ter lista de IPs separados por vírgula, pegamos o primeiro (cliente)
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip  = trim($ips[0]);
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
    }

    // 5) Fallback: REMOTE_ADDR (melhor que nada)
    if (!empty($_SERVER['REMOTE_ADDR'])) {
        return $_SERVER['REMOTE_ADDR'];
    }

    return null;
}

/**
 * Bloqueia o acesso ao painel de administração do WordPress para IPs não autorizados.
 *
 * Esta função verifica se o usuário está tentando acessar a área de administração
 * e se o IP do usuário não está na lista de IPs permitidos. Se ambas as condições
 * forem verdadeiras, o acesso é bloqueado com uma mensagem de erro.
 */
function fpb_block_admin_access() {
    if(is_super_admin()) return; // Permite acesso para super administradores em multisite
    // Permite requisições internas do sistema
    // Permite requisições AJAX, CRON
    if (wp_doing_ajax() || wp_doing_cron()) return;
    // Permite chamadas REST API (/wp-json/)
    if (defined('REST_REQUEST') && REST_REQUEST || strpos($_SERVER['REQUEST_URI'], '/wp-json') !== false) return;
    // Permite chamadas via WP-CLI
    if (defined('WP_CLI') && WP_CLI) return;
    // Permite execuções de cron interno do WordPress (/wp-cron.php)
    if (defined('DOING_CRON') && DOING_CRON) return;
    // Permite chamadas XML-RPC (/xmlrpc.php)
    if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) return;
    // Permite endpoints específicos
    $allowed_endpoints = [
        'admin-post.php', // Permite endpoint admin-post.php para hooks públicos
        '/wp-json', 
        '/wp-content/uploads', // Permite chamadas /wp-content/uploads
        '/wc', // Permite chamadas /wc/*
        'action=logout' // Permite chamadas de ações específicas (ex: action=logout)
    ];
    foreach ($allowed_endpoints as $endpoint) {
        if (strpos($_SERVER['REQUEST_URI'], $endpoint) !== false) return;
    }
    //Permite em ambiente local
    // if(!isset($_SERVER['HTTP_HOST'])) return; // Verifica se a variável HTTP_HOST está definida
    $allowed_hosts = [
        'inkweb.local',
        '.local'
    ];
    foreach ($allowed_hosts as $host) {
        if (strpos($_SERVER['HTTP_HOST'], $host) !== false) return; // Permite acesso em ambiente local
    }

    // Defina o IP autorizado a acessar o /wp-admin.
    $allowed_ip = '177.220.172.145';
    // Obtém o IP do visitante.
    $visitor_ip = get_real_client_ip();

    // Verifica se é uma tentativa de acesso ao admin, não é uma requisição AJAX
    // e o IP do visitante não é o IP autorizado.
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        // wp_die(
        //     'Seu endereço IP não tem permissão para acessar esta área.',
        //     'Acesso Restrito',
        //     [ 'response' => 403 ]
        // );

        if($visitor_ip !== $allowed_ip)
        {
            wp_redirect( home_url() );
            exit;
        }
        else
        {
            // echo "<script>console.log('Acesso permitido para o IP autorizado: " . $visitor_ip . "');</script>";
        }
    }
}
add_action( 'init', 'fpb_block_admin_access' );
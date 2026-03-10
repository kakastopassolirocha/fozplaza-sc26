<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('fozplaza_instagram_get_module_dir')) {
    function fozplaza_instagram_get_module_dir() {
        return trailingslashit(get_template_directory()) . 'plugins/instagram/';
    }
}

if (!function_exists('fozplaza_instagram_get_option_key')) {
    function fozplaza_instagram_get_option_key() {
        return 'fozplaza_instagram_settings';
    }
}

if (!function_exists('fozplaza_instagram_get_config')) {
    function fozplaza_instagram_get_config() {
        static $config = null;

        if (is_array($config)) {
            return $config;
        }

        $defaults = array(
            'access_token' => '',
            'user_id' => '',
            'profile_url' => 'https://www.instagram.com/fozplazahotel/',
            'api_version' => 'v21.0',
            'cache_ttl' => 1800,
            'default_limit' => 8,
            'widget_layout' => '1',
            'debug' => false,
        );

        $config_file = fozplaza_instagram_get_module_dir() . 'config.php';
        $file_config = file_exists($config_file) ? include $config_file : array();
        if (!is_array($file_config)) {
            $file_config = array();
        }

        $saved_config = get_option(fozplaza_instagram_get_option_key(), array());
        if (!is_array($saved_config)) {
            $saved_config = array();
        }

        $config = wp_parse_args($saved_config, wp_parse_args($file_config, $defaults));

        $api_version = trim((string) $config['api_version']);
        if ($api_version === '') {
            $api_version = 'v21.0';
        } elseif (strpos($api_version, 'v') !== 0) {
            $api_version = 'v' . $api_version;
        }

        if (is_string($config['access_token'])) {
            $config['access_token'] = preg_replace('/\s+/', '', trim($config['access_token']));
        } else {
            $config['access_token'] = '';
        }
        $config['user_id'] = is_string($config['user_id']) ? trim($config['user_id']) : '';
        $config['profile_url'] = esc_url_raw((string) $config['profile_url']);
        $config['api_version'] = sanitize_text_field($api_version);
        $config['cache_ttl'] = max(MINUTE_IN_SECONDS, absint($config['cache_ttl']));
        $config['default_limit'] = max(1, min(12, absint($config['default_limit'])));
        $config['widget_layout'] = !empty($config['widget_layout']) && in_array($config['widget_layout'], array('1', '2'), true) ? $config['widget_layout'] : '1';
        $config['debug'] = !empty($config['debug']);

        return $config;
    }
}

if (!function_exists('fozplaza_instagram_flush_cache')) {
    function fozplaza_instagram_flush_cache() {
        global $wpdb;

        $transient_like = $wpdb->esc_like('_transient_foz_ig_posts_') . '%';
        $timeout_like = $wpdb->esc_like('_transient_timeout_foz_ig_posts_') . '%';

        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
                $transient_like,
                $timeout_like
            )
        );
    }
}

if (!function_exists('fozplaza_instagram_debug_log')) {
    function fozplaza_instagram_debug_log($message, $context = array()) {
        if (!defined('INK_DEBUG') || !INK_DEBUG) {
            return;
        }

        $log_line = '[FOZ_INSTAGRAM] ' . $message;
        if (!empty($context)) {
            $log_line .= ' ' . wp_json_encode($context);
        }

        error_log($log_line);
    }
}

if (!function_exists('fozplaza_instagram_set_debug_context')) {
    function fozplaza_instagram_set_debug_context($status, $message, $details = array()) {
        $GLOBALS['fozplaza_instagram_debug_context'] = array(
            'status' => sanitize_key((string) $status),
            'message' => sanitize_text_field((string) $message),
            'details' => is_array($details) ? $details : array(),
            'timestamp' => current_time('mysql'),
        );
    }
}

if (!function_exists('fozplaza_instagram_get_debug_context')) {
    function fozplaza_instagram_get_debug_context() {
        if (!empty($GLOBALS['fozplaza_instagram_debug_context']) && is_array($GLOBALS['fozplaza_instagram_debug_context'])) {
            return $GLOBALS['fozplaza_instagram_debug_context'];
        }

        return array(
            'status' => 'info',
            'message' => 'Sem eventos de debug nesta requisicao.',
            'details' => array(),
            'timestamp' => current_time('mysql'),
        );
    }
}

if (!function_exists('fozplaza_instagram_debug_status_priority')) {
    function fozplaza_instagram_debug_status_priority($status) {
        $status = sanitize_key((string) $status);

        $map = array(
            'error' => 40,
            'warning' => 30,
            'success' => 20,
            'info' => 10,
        );

        return isset($map[$status]) ? $map[$status] : 0;
    }
}

if (!function_exists('fozplaza_instagram_maybe_set_debug_context')) {
    function fozplaza_instagram_maybe_set_debug_context($status, $message, $details = array()) {
        $current = fozplaza_instagram_get_debug_context();
        $current_priority = fozplaza_instagram_debug_status_priority(isset($current['status']) ? $current['status'] : 'info');
        $new_priority = fozplaza_instagram_debug_status_priority($status);

        if ($new_priority > $current_priority) {
            fozplaza_instagram_set_debug_context($status, $message, $details);
        }
    }
}

if (!function_exists('fozplaza_instagram_is_debug_enabled')) {
    function fozplaza_instagram_is_debug_enabled() {
        $config = fozplaza_instagram_get_config();
        return !empty($config['debug']);
    }
}

if (!function_exists('fozplaza_instagram_is_super_admin_user')) {
    function fozplaza_instagram_is_super_admin_user() {
        return function_exists('is_super_admin') && is_super_admin();
    }
}

if (!function_exists('fozplaza_instagram_can_show_visual_debug')) {
    function fozplaza_instagram_can_show_visual_debug() {
        return fozplaza_instagram_is_debug_enabled() && fozplaza_instagram_is_super_admin_user();
    }
}

if (!function_exists('fozplaza_instagram_can_view_widget_fallback')) {
    function fozplaza_instagram_can_view_widget_fallback() {
        return current_user_can('manage_options') || fozplaza_instagram_is_super_admin_user();
    }
}

if (!function_exists('fozplaza_instagram_get_media_fields')) {
    function fozplaza_instagram_get_media_fields($variant = 'full') {
        $variant = sanitize_key((string) $variant);

        if ($variant === 'basic') {
            return 'id,caption,media_type,media_url,permalink,thumbnail_url,timestamp';
        }

        return 'id,caption,media_type,media_url,permalink,thumbnail_url,timestamp,children{media_type,media_url,thumbnail_url}';
    }
}

if (!function_exists('fozplaza_instagram_detect_token_type')) {
    function fozplaza_instagram_detect_token_type($access_token) {
        if (!is_string($access_token) || $access_token === '') {
            return 'unknown';
        }

        $token_prefix = strtoupper(substr($access_token, 0, 3));
        if (strpos($token_prefix, 'IG') === 0) {
            return 'instagram';
        }

        if (strpos($token_prefix, 'EA') === 0) {
            return 'facebook';
        }

        return 'unknown';
    }
}

if (!function_exists('fozplaza_instagram_build_media_endpoint')) {
    function fozplaza_instagram_build_media_endpoint($limit = 8, $force_me_endpoint = false, $fields = '') {
        $config = fozplaza_instagram_get_config();
        if (empty($config['access_token'])) {
            return '';
        }

        $resolved_fields = is_string($fields) && trim($fields) !== ''
            ? trim($fields)
            : fozplaza_instagram_get_media_fields('full');

        $token_type = fozplaza_instagram_detect_token_type($config['access_token']);
        $use_me_endpoint = $force_me_endpoint || $token_type === 'instagram' || empty($config['user_id']);
        $base_url = $use_me_endpoint
            ? 'https://graph.instagram.com/me/media'
            : sprintf(
                'https://graph.facebook.com/%s/%s/media',
                rawurlencode($config['api_version']),
                rawurlencode($config['user_id'])
            );

        $query = array(
            'fields' => $resolved_fields,
            'limit' => max(1, min(12, absint($limit))),
            'access_token' => $config['access_token'],
        );

        return add_query_arg($query, $base_url);
    }
}

if (!function_exists('fozplaza_instagram_push_endpoint')) {
    function fozplaza_instagram_push_endpoint(&$endpoints, $label, $url) {
        if (empty($url) || !is_array($endpoints)) {
            return;
        }

        foreach ($endpoints as $endpoint) {
            if (!empty($endpoint['url']) && $endpoint['url'] === $url) {
                return;
            }
        }

        $endpoints[] = array(
            'label' => sanitize_key((string) $label),
            'url' => $url,
        );
    }
}

if (!function_exists('fozplaza_instagram_get_media_endpoints')) {
    function fozplaza_instagram_get_media_endpoints($limit = 8) {
        $config = fozplaza_instagram_get_config();
        $endpoints = array();

        $token_type = fozplaza_instagram_detect_token_type($config['access_token']);
        $has_user_id = !empty($config['user_id']);
        $prefer_me_endpoint = ($token_type === 'instagram') || !$has_user_id;

        $endpoint_sequence = array();
        if ($prefer_me_endpoint) {
            $endpoint_sequence[] = array(
                'label' => 'me_media',
                'use_me_endpoint' => true,
            );
            if ($has_user_id) {
                $endpoint_sequence[] = array(
                    'label' => 'fallback_user_id_media',
                    'use_me_endpoint' => false,
                );
            }
        } else {
            if ($has_user_id) {
                $endpoint_sequence[] = array(
                    'label' => 'user_id_media',
                    'use_me_endpoint' => false,
                );
            }
            $endpoint_sequence[] = array(
                'label' => 'fallback_me_media',
                'use_me_endpoint' => true,
            );
        }

        foreach ($endpoint_sequence as $endpoint_data) {
            $full_fields_url = fozplaza_instagram_build_media_endpoint(
                $limit,
                !empty($endpoint_data['use_me_endpoint']),
                fozplaza_instagram_get_media_fields('full')
            );
            fozplaza_instagram_push_endpoint($endpoints, $endpoint_data['label'], $full_fields_url);

            $basic_fields_url = fozplaza_instagram_build_media_endpoint(
                $limit,
                !empty($endpoint_data['use_me_endpoint']),
                fozplaza_instagram_get_media_fields('basic')
            );
            fozplaza_instagram_push_endpoint($endpoints, $endpoint_data['label'] . '_basic_fields', $basic_fields_url);
        }

        return $endpoints;
    }
}

if (!function_exists('fozplaza_instagram_mask_endpoint_for_debug')) {
    function fozplaza_instagram_mask_endpoint_for_debug($url) {
        $url = is_string($url) ? trim($url) : '';
        if ($url === '') {
            return '';
        }

        $parts = wp_parse_url($url);
        if (!is_array($parts)) {
            return '';
        }

        $base = '';
        if (!empty($parts['scheme'])) {
            $base .= $parts['scheme'] . '://';
        }
        if (!empty($parts['host'])) {
            $base .= $parts['host'];
        }
        if (!empty($parts['path'])) {
            $base .= $parts['path'];
        }

        $query_params = array();
        if (!empty($parts['query'])) {
            wp_parse_str($parts['query'], $query_params);
        }

        unset($query_params['access_token']);

        if (empty($query_params)) {
            return $base;
        }

        return add_query_arg($query_params, $base);
    }
}

if (!function_exists('fozplaza_instagram_resolve_image_url')) {
    function fozplaza_instagram_resolve_image_url($item) {
        if (!is_array($item)) {
            return '';
        }

        $media_type = isset($item['media_type']) ? strtoupper((string) $item['media_type']) : '';
        $candidates = array();

        if ($media_type === 'VIDEO') {
            if (!empty($item['thumbnail_url'])) {
                $candidates[] = $item['thumbnail_url'];
            }
            if (!empty($item['media_url'])) {
                $candidates[] = $item['media_url'];
            }
        } else {
            if (!empty($item['media_url'])) {
                $candidates[] = $item['media_url'];
            }
            if (!empty($item['thumbnail_url'])) {
                $candidates[] = $item['thumbnail_url'];
            }
        }

        if (!empty($item['children']['data']) && is_array($item['children']['data'])) {
            foreach ($item['children']['data'] as $child) {
                if (!is_array($child)) {
                    continue;
                }

                if (!empty($child['media_url'])) {
                    $candidates[] = $child['media_url'];
                }

                if (!empty($child['thumbnail_url'])) {
                    $candidates[] = $child['thumbnail_url'];
                }
            }
        }

        foreach ($candidates as $candidate) {
            $url = esc_url_raw($candidate);
            if (!empty($url)) {
                return $url;
            }
        }

        return '';
    }
}

if (!function_exists('fozplaza_instagram_normalize_media_item')) {
    function fozplaza_instagram_normalize_media_item($item) {
        if (!is_array($item)) {
            return null;
        }

        $permalink = isset($item['permalink']) ? esc_url_raw($item['permalink']) : '';
        $image_url = fozplaza_instagram_resolve_image_url($item);
        if (empty($permalink) || empty($image_url)) {
            return null;
        }

        $caption = '';
        if (!empty($item['caption']) && is_string($item['caption'])) {
            $caption = trim(wp_strip_all_tags($item['caption']));
        }

        return array(
            'id' => isset($item['id']) ? sanitize_text_field($item['id']) : '',
            'caption' => $caption,
            'media_type' => isset($item['media_type']) ? sanitize_text_field(strtoupper((string) $item['media_type'])) : 'IMAGE',
            'permalink' => $permalink,
            'image_url' => $image_url,
            'timestamp' => !empty($item['timestamp']) ? sanitize_text_field($item['timestamp']) : '',
        );
    }
}

if (!function_exists('fozplaza_instagram_request_posts')) {
    function fozplaza_instagram_request_posts($limit = 8) {
        $endpoints = fozplaza_instagram_get_media_endpoints($limit);
        if (empty($endpoints)) {
            fozplaza_instagram_debug_log('Token ausente nas configuracoes do widget Instagram.');
            fozplaza_instagram_set_debug_context(
                'error',
                'Token ausente ou endpoint invalido.',
                array(
                    'step' => 'build_endpoint',
                    'hint' => 'Preencha Access Token no menu WP-Admin > Instagram Widget.',
                )
            );
            return array();
        }

        $attempts = array();

        foreach ($endpoints as $endpoint_data) {
            $endpoint_debug_url = fozplaza_instagram_mask_endpoint_for_debug($endpoint_data['url']);

            $response = wp_remote_get(
                $endpoint_data['url'],
                array(
                    'timeout' => 12,
                    'redirection' => 3,
                    'headers' => array(
                        'Accept' => 'application/json',
                    ),
                )
            );

            if (is_wp_error($response)) {
                $attempts[] = array(
                    'label' => $endpoint_data['label'],
                    'endpoint' => $endpoint_debug_url,
                    'result' => 'wp_error',
                    'message' => $response->get_error_message(),
                );
                continue;
            }

            $status_code = wp_remote_retrieve_response_code($response);
            $body = wp_remote_retrieve_body($response);
            $payload = json_decode($body, true);

            if ($status_code < 200 || $status_code > 299) {
                $attempts[] = array(
                    'label' => $endpoint_data['label'],
                    'endpoint' => $endpoint_debug_url,
                    'result' => 'http_error',
                    'status_code' => (int) $status_code,
                    'body_preview' => is_string($body) ? substr($body, 0, 200) : '',
                );
                continue;
            }

            if (!is_array($payload)) {
                $attempts[] = array(
                    'label' => $endpoint_data['label'],
                    'endpoint' => $endpoint_debug_url,
                    'result' => 'json_invalid',
                );
                continue;
            }

            if (!empty($payload['error'])) {
                $api_error = is_array($payload['error']) ? $payload['error'] : array();
                $attempts[] = array(
                    'label' => $endpoint_data['label'],
                    'endpoint' => $endpoint_debug_url,
                    'result' => 'api_error',
                    'error_type' => isset($api_error['type']) ? sanitize_text_field((string) $api_error['type']) : '',
                    'error_code' => isset($api_error['code']) ? (int) $api_error['code'] : 0,
                    'error_subcode' => isset($api_error['error_subcode']) ? (int) $api_error['error_subcode'] : 0,
                    'error_message' => isset($api_error['message']) ? sanitize_text_field((string) $api_error['message']) : '',
                );
                continue;
            }

            if (empty($payload['data']) || !is_array($payload['data'])) {
                $attempts[] = array(
                    'label' => $endpoint_data['label'],
                    'endpoint' => $endpoint_debug_url,
                    'result' => 'empty_data',
                );
                continue;
            }

            $posts = array();
            foreach ($payload['data'] as $item) {
                $normalized_item = fozplaza_instagram_normalize_media_item($item);
                if (empty($normalized_item)) {
                    continue;
                }

                $posts[] = $normalized_item;
            }

            if (!empty($posts)) {
                fozplaza_instagram_set_debug_context(
                    'success',
                    'Feed carregado com sucesso da API.',
                    array(
                        'step' => 'normalize',
                        'endpoint_used' => $endpoint_data['label'],
                        'endpoint_url' => $endpoint_debug_url,
                        'posts_count' => count($posts),
                        'attempts' => $attempts,
                    )
                );

                return $posts;
            }

            $attempts[] = array(
                'label' => $endpoint_data['label'],
                'endpoint' => $endpoint_debug_url,
                'result' => 'normalized_empty',
                'raw_items' => is_array($payload['data']) ? count($payload['data']) : 0,
            );
        }

        $has_error = false;
        foreach ($attempts as $attempt) {
            if (!empty($attempt['result']) && in_array($attempt['result'], array('wp_error', 'http_error', 'json_invalid', 'api_error'), true)) {
                $has_error = true;
                break;
            }
        }

        if ($has_error) {
            fozplaza_instagram_set_debug_context(
                'error',
                'Nao foi possivel obter posts da API.',
                array(
                    'step' => 'request_attempts',
                    'attempts' => $attempts,
                )
            );
        } else {
            fozplaza_instagram_set_debug_context(
                'warning',
                'API respondeu sem posts para os endpoints tentados.',
                array(
                    'step' => 'request_attempts',
                    'attempts' => $attempts,
                )
            );
        }

        return array();
    }
}

if (!function_exists('fozplaza_get_instagram_posts')) {
    function fozplaza_get_instagram_posts($limit = null) {
        $config = fozplaza_instagram_get_config();
        $limit = is_null($limit) ? $config['default_limit'] : max(1, min(12, absint($limit)));
        $bypass_cache_for_debug = fozplaza_instagram_can_show_visual_debug();

        $endpoint = fozplaza_instagram_build_media_endpoint($limit);
        if (empty($endpoint)) {
            fozplaza_instagram_maybe_set_debug_context(
                'error',
                'Widget sem token configurado.',
                array(
                    'step' => 'cache_key_seed',
                    'hint' => 'Preencha Access Token no menu WP-Admin > Instagram Widget.',
                )
            );
            return array();
        }

        $cache_key = 'foz_ig_posts_' . md5($endpoint);
        if (!$bypass_cache_for_debug) {
            $cached_posts = get_transient($cache_key);
            if (is_array($cached_posts)) {
                fozplaza_instagram_maybe_set_debug_context(
                    'info',
                    'Feed carregado do cache (transient).',
                    array(
                        'step' => 'cache_hit',
                        'cache_key' => $cache_key,
                        'posts_count' => count($cached_posts),
                    )
                );
                return $cached_posts;
            }
        } else {
            fozplaza_instagram_maybe_set_debug_context(
                'info',
                'Cache ignorado para depuracao do super_admin.',
                array(
                    'step' => 'cache_bypass_debug',
                    'cache_key' => $cache_key,
                )
            );
        }

        $posts = fozplaza_instagram_request_posts($limit);
        $cache_ttl = !empty($posts) ? $config['cache_ttl'] : (5 * MINUTE_IN_SECONDS);

        if (!$bypass_cache_for_debug) {
            set_transient($cache_key, $posts, $cache_ttl);
            fozplaza_instagram_maybe_set_debug_context(
                !empty($posts) ? 'success' : 'warning',
                !empty($posts) ? 'Feed salvo no cache.' : 'Feed vazio salvo com cache curto.',
                array(
                    'step' => 'cache_write',
                    'cache_key' => $cache_key,
                    'cache_ttl' => (int) $cache_ttl,
                    'posts_count' => count($posts),
                )
            );
        }

        return $posts;
    }
}

if (!function_exists('fozplaza_get_instagram_profile_url')) {
    function fozplaza_get_instagram_profile_url() {
        $config = fozplaza_instagram_get_config();
        if (!empty($config['profile_url'])) {
            return $config['profile_url'];
        }

        return 'https://www.instagram.com/fozplazahotel/';
    }
}

if (!function_exists('fozplaza_instagram_media_label')) {
    function fozplaza_instagram_media_label($media_type) {
        $media_type = strtoupper((string) $media_type);

        if ($media_type === 'VIDEO') {
            return 'Video';
        }

        if ($media_type === 'CAROUSEL_ALBUM') {
            return 'Galeria';
        }

        return 'Post';
    }
}

if (!function_exists('fozplaza_instagram_get_saved_settings')) {
    function fozplaza_instagram_get_saved_settings() {
        $settings = get_option(fozplaza_instagram_get_option_key(), array());
        return is_array($settings) ? $settings : array();
    }
}

if (!function_exists('fozplaza_instagram_update_saved_settings')) {
    function fozplaza_instagram_update_saved_settings($settings) {
        if (!is_array($settings)) {
            $settings = array();
        }

        return update_option(fozplaza_instagram_get_option_key(), $settings);
    }
}

if (!function_exists('fozplaza_instagram_get_refresh_cron_hook')) {
    function fozplaza_instagram_get_refresh_cron_hook() {
        return 'fozplaza_instagram_refresh_token_event';
    }
}

if (!function_exists('fozplaza_instagram_get_refresh_interval_seconds')) {
    function fozplaza_instagram_get_refresh_interval_seconds() {
        return 7 * DAY_IN_SECONDS;
    }
}

if (!function_exists('fozplaza_instagram_record_refresh_status')) {
    function fozplaza_instagram_record_refresh_status($settings, $status, $message, $source, $extra = array()) {
        $settings = is_array($settings) ? $settings : array();
        $extra = is_array($extra) ? $extra : array();

        $settings['token_last_attempt_at'] = current_time('mysql');
        $settings['token_last_attempt_ts'] = time();
        $settings['token_refresh_status'] = sanitize_key((string) $status);
        $settings['token_refresh_message'] = sanitize_text_field((string) $message);
        $settings['token_refresh_source'] = sanitize_key((string) $source);

        $int_keys = array(
            'token_saved_ts',
            'token_last_refresh_ts',
            'token_expires_in',
        );
        $text_keys = array(
            'token_saved_at',
            'token_last_refresh_at',
            'token_refresh_next_at',
            'token_expires_at',
        );

        foreach ($int_keys as $int_key) {
            if (array_key_exists($int_key, $extra)) {
                $settings[$int_key] = absint($extra[$int_key]);
            }
        }

        foreach ($text_keys as $text_key) {
            if (array_key_exists($text_key, $extra)) {
                $settings[$text_key] = sanitize_text_field((string) $extra[$text_key]);
            }
        }

        if (array_key_exists('access_token', $extra)) {
            $settings['access_token'] = preg_replace('/\s+/', '', trim((string) $extra['access_token']));
        }

        fozplaza_instagram_update_saved_settings($settings);
        return $settings;
    }
}

if (!function_exists('fozplaza_instagram_refresh_access_token')) {
    function fozplaza_instagram_refresh_access_token($force = false, $source = 'cron') {
        $source = sanitize_key((string) $source);
        $settings = fozplaza_instagram_get_saved_settings();
        $token = isset($settings['access_token']) ? preg_replace('/\s+/', '', trim((string) $settings['access_token'])) : '';

        if ($token === '') {
            fozplaza_instagram_record_refresh_status(
                $settings,
                'skipped',
                'Sem token salvo para renovacao automatica.',
                $source
            );
            return array(
                'success' => false,
                'status' => 'skipped',
                'message' => 'Sem token salvo para renovacao automatica.',
            );
        }

        if (fozplaza_instagram_detect_token_type($token) !== 'instagram') {
            fozplaza_instagram_record_refresh_status(
                $settings,
                'skipped',
                'Refresh automatico disponivel apenas para token IG.',
                $source
            );
            return array(
                'success' => false,
                'status' => 'skipped',
                'message' => 'Refresh automatico disponivel apenas para token IG.',
            );
        }

        $now_ts = time();
        $refresh_interval = fozplaza_instagram_get_refresh_interval_seconds();
        $last_refresh_ts = !empty($settings['token_last_refresh_ts']) ? absint($settings['token_last_refresh_ts']) : 0;
        $token_saved_ts = !empty($settings['token_saved_ts']) ? absint($settings['token_saved_ts']) : 0;

        if (!$force && $last_refresh_ts > 0 && ($now_ts - $last_refresh_ts) < $refresh_interval) {
            $next_refresh_ts = $last_refresh_ts + $refresh_interval;
            fozplaza_instagram_record_refresh_status(
                $settings,
                'skipped',
                'Token ainda dentro da janela segura de refresh.',
                $source,
                array(
                    'token_refresh_next_at' => wp_date('Y-m-d H:i:s', $next_refresh_ts, wp_timezone()),
                )
            );
            return array(
                'success' => false,
                'status' => 'skipped',
                'message' => 'Token ainda dentro da janela segura de refresh.',
            );
        }

        if (!$force && $last_refresh_ts === 0 && $token_saved_ts > 0 && ($now_ts - $token_saved_ts) < DAY_IN_SECONDS) {
            $next_refresh_ts = $token_saved_ts + DAY_IN_SECONDS;
            fozplaza_instagram_record_refresh_status(
                $settings,
                'skipped',
                'Token novo. Aguardando janela minima de 24h para refresh.',
                $source,
                array(
                    'token_refresh_next_at' => wp_date('Y-m-d H:i:s', $next_refresh_ts, wp_timezone()),
                )
            );
            return array(
                'success' => false,
                'status' => 'skipped',
                'message' => 'Token novo. Aguardando janela minima de 24h para refresh.',
            );
        }

        $refresh_url = add_query_arg(
            array(
                'grant_type' => 'ig_refresh_token',
                'access_token' => $token,
            ),
            'https://graph.instagram.com/refresh_access_token'
        );

        $response = wp_remote_get(
            $refresh_url,
            array(
                'timeout' => 12,
                'redirection' => 3,
                'headers' => array(
                    'Accept' => 'application/json',
                ),
            )
        );

        if (is_wp_error($response)) {
            $error_message = 'Falha ao chamar endpoint de refresh: ' . $response->get_error_message();
            fozplaza_instagram_record_refresh_status($settings, 'error', $error_message, $source);
            fozplaza_instagram_debug_log('Falha no refresh do token Instagram.', array('error' => $response->get_error_message()));

            return array(
                'success' => false,
                'status' => 'error',
                'message' => $error_message,
            );
        }

        $status_code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $payload = json_decode($body, true);

        if ($status_code < 200 || $status_code > 299) {
            $error_message = 'Falha HTTP ao renovar token.';
            if (is_array($payload) && !empty($payload['error']['message'])) {
                $error_message = (string) $payload['error']['message'];
            }

            fozplaza_instagram_record_refresh_status($settings, 'error', $error_message, $source);
            fozplaza_instagram_debug_log(
                'Falha HTTP no refresh do token Instagram.',
                array(
                    'status_code' => (int) $status_code,
                    'body_preview' => is_string($body) ? substr($body, 0, 220) : '',
                )
            );

            return array(
                'success' => false,
                'status' => 'error',
                'message' => $error_message,
            );
        }

        if (!is_array($payload) || !empty($payload['error'])) {
            $error_message = 'Resposta invalida ao renovar token.';
            if (is_array($payload) && !empty($payload['error']['message'])) {
                $error_message = (string) $payload['error']['message'];
            }

            fozplaza_instagram_record_refresh_status($settings, 'error', $error_message, $source);
            return array(
                'success' => false,
                'status' => 'error',
                'message' => $error_message,
            );
        }

        $new_token = !empty($payload['access_token']) ? preg_replace('/\s+/', '', trim((string) $payload['access_token'])) : '';
        if ($new_token === '') {
            $error_message = 'Refresh retornou sucesso sem access_token.';
            fozplaza_instagram_record_refresh_status($settings, 'error', $error_message, $source);
            return array(
                'success' => false,
                'status' => 'error',
                'message' => $error_message,
            );
        }

        $expires_in = !empty($payload['expires_in']) ? absint($payload['expires_in']) : 0;
        $expires_at = $expires_in > 0
            ? wp_date('Y-m-d H:i:s', $now_ts + $expires_in, wp_timezone())
            : '';
        $next_refresh_at = wp_date('Y-m-d H:i:s', $now_ts + $refresh_interval, wp_timezone());
        $now_mysql = current_time('mysql');

        fozplaza_instagram_record_refresh_status(
            $settings,
            'success',
            'Token renovado com sucesso.',
            $source,
            array(
                'access_token' => $new_token,
                'token_saved_at' => $now_mysql,
                'token_saved_ts' => $now_ts,
                'token_last_refresh_at' => $now_mysql,
                'token_last_refresh_ts' => $now_ts,
                'token_expires_in' => $expires_in,
                'token_expires_at' => $expires_at,
                'token_refresh_next_at' => $next_refresh_at,
            )
        );

        fozplaza_instagram_flush_cache();
        fozplaza_instagram_debug_log(
            'Token Instagram renovado automaticamente.',
            array(
                'source' => $source,
                'expires_in' => $expires_in,
                'next_refresh_at' => $next_refresh_at,
            )
        );

        return array(
            'success' => true,
            'status' => 'success',
            'message' => 'Token renovado com sucesso.',
            'expires_in' => $expires_in,
        );
    }
}

if (!function_exists('fozplaza_instagram_unschedule_refresh_event')) {
    function fozplaza_instagram_unschedule_refresh_event() {
        $hook = fozplaza_instagram_get_refresh_cron_hook();
        $next_timestamp = wp_next_scheduled($hook);

        while ($next_timestamp) {
            wp_unschedule_event($next_timestamp, $hook);
            $next_timestamp = wp_next_scheduled($hook);
        }
    }
}

if (!function_exists('fozplaza_instagram_schedule_refresh_event')) {
    function fozplaza_instagram_schedule_refresh_event() {
        $hook = fozplaza_instagram_get_refresh_cron_hook();
        if (!wp_next_scheduled($hook)) {
            wp_schedule_event(time() + HOUR_IN_SECONDS, 'daily', $hook);
        }
    }
}

if (!function_exists('fozplaza_instagram_setup_refresh_cron')) {
    function fozplaza_instagram_setup_refresh_cron() {
        $settings = fozplaza_instagram_get_saved_settings();
        $token = !empty($settings['access_token']) ? preg_replace('/\s+/', '', trim((string) $settings['access_token'])) : '';

        if ($token !== '' && fozplaza_instagram_detect_token_type($token) === 'instagram') {
            fozplaza_instagram_schedule_refresh_event();
            return;
        }

        fozplaza_instagram_unschedule_refresh_event();
    }
}

if (!function_exists('fozplaza_instagram_run_scheduled_refresh')) {
    function fozplaza_instagram_run_scheduled_refresh() {
        fozplaza_instagram_refresh_access_token(false, 'cron');
    }
}

add_action('init', 'fozplaza_instagram_setup_refresh_cron', 30);
add_action(fozplaza_instagram_get_refresh_cron_hook(), 'fozplaza_instagram_run_scheduled_refresh');

if (!function_exists('fozplaza_instagram_enqueue_assets')) {
    function fozplaza_instagram_find_swiper_script_handle() {
        $candidate_handles = array('swiper', 'swiper-bundle');

        foreach ($candidate_handles as $handle) {
            if (
                wp_script_is($handle, 'registered')
                || wp_script_is($handle, 'enqueued')
                || wp_script_is($handle, 'to_do')
                || wp_script_is($handle, 'done')
            ) {
                return $handle;
            }
        }

        return '';
    }

    function fozplaza_instagram_find_swiper_style_handle() {
        $candidate_handles = array('swiper', 'swiper-bundle', 'swiper-css');

        foreach ($candidate_handles as $handle) {
            if (
                wp_style_is($handle, 'registered')
                || wp_style_is($handle, 'enqueued')
                || wp_style_is($handle, 'to_do')
                || wp_style_is($handle, 'done')
            ) {
                return $handle;
            }
        }

        return '';
    }

    function fozplaza_instagram_find_fontawesome_style_handle() {
        global $wp_styles;

        $candidate_handles = array(
            'font-awesome',
            'fontawesome',
            'font-awesome-5',
            'font-awesome-6',
            'fa',
        );

        foreach ($candidate_handles as $handle) {
            if (
                wp_style_is($handle, 'registered')
                || wp_style_is($handle, 'enqueued')
                || wp_style_is($handle, 'to_do')
                || wp_style_is($handle, 'done')
            ) {
                return $handle;
            }
        }

        if (isset($wp_styles) && !empty($wp_styles->registered) && is_array($wp_styles->registered)) {
            foreach ($wp_styles->registered as $handle => $style) {
                if (!is_string($handle)) {
                    continue;
                }

                $src = '';
                if (is_object($style) && !empty($style->src)) {
                    $src = (string) $style->src;
                }

                $src_lower = strtolower($src);
                if (strpos($src_lower, 'font-awesome') !== false || strpos($src_lower, 'fontawesome') !== false) {
                    return $handle;
                }
            }
        }

        return '';
    }

    function fozplaza_instagram_enqueue_assets() {
        $swiper_script_handle = fozplaza_instagram_find_swiper_script_handle();
        $swiper_style_handle = fozplaza_instagram_find_swiper_style_handle();
        $fontawesome_style_handle = fozplaza_instagram_find_fontawesome_style_handle();

        if ($swiper_script_handle === '') {
            $swiper_script_handle = 'fozplaza-swiper-cdn';

            wp_register_script(
                $swiper_script_handle,
                'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
                array(),
                '11.2.6',
                true
            );
        }

        if ($swiper_style_handle === '') {
            $swiper_style_handle = 'fozplaza-swiper-cdn';

            wp_register_style(
                $swiper_style_handle,
                'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
                array(),
                '11.2.6'
            );
        }

        wp_enqueue_style($swiper_style_handle);

        if ($fontawesome_style_handle === '') {
            $fontawesome_style_handle = 'fozplaza-font-awesome-cdn';

            wp_register_style(
                $fontawesome_style_handle,
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
                array(),
                '5.15.4'
            );
        }

        wp_enqueue_style($fontawesome_style_handle);

        $script_file = fozplaza_instagram_get_module_dir() . 'assets/instagram-widget.js';
        $script_url = trailingslashit(get_template_directory_uri()) . 'plugins/instagram/assets/instagram-widget.js';
        $script_ver = file_exists($script_file) ? (string) filemtime($script_file) : '1.0.0';

        wp_enqueue_script(
            'fozplaza-instagram-widget',
            $script_url,
            array($swiper_script_handle),
            $script_ver,
            true
        );
    }
}

if (!function_exists('fozplaza_instagram_render_widget')) {
    function fozplaza_instagram_render_widget($args = array()) {
        $args = wp_parse_args(
            $args,
            array(
                'limit' => null,
                'embedded' => false,
                'title' => 'Instagram',
                'description' => 'Veja as ultimas publicacoes do nosso perfil oficial.',
                'follow_label' => 'Seguir @fozplazahotel',
                'empty_message' => 'Nao foi possivel carregar o feed agora. Veja as postagens diretamente no nosso Instagram.',
                'fade_edges' => false,
            )
        );

        $posts = fozplaza_get_instagram_posts($args['limit']);
        $profile_url = fozplaza_get_instagram_profile_url();
        $title = (string) $args['title'];
        $description = (string) $args['description'];
        $follow_label = (string) $args['follow_label'];
        $empty_message = (string) $args['empty_message'];
        $embedded = !empty($args['embedded']);
        $fade_edges = !empty($args['fade_edges']);
        $show_debug = fozplaza_instagram_can_show_visual_debug();
        $debug_context = $show_debug ? fozplaza_instagram_get_debug_context() : array();

        if (empty($posts) && !fozplaza_instagram_can_view_widget_fallback()) {
            return '';
        }

        fozplaza_instagram_enqueue_assets();

        $config = fozplaza_instagram_get_config();
        $layout = !empty($args['layout']) ? $args['layout'] : (!empty($config['widget_layout']) ? $config['widget_layout'] : '1');
        $view_filename = ($layout === '2') ? 'views/widget-view-2.php' : 'views/widget.php';
        $view_file = fozplaza_instagram_get_module_dir() . $view_filename;
        if (!file_exists($view_file)) {
            fozplaza_instagram_debug_log('View do widget nao encontrada.', array('file' => $view_file));
            return '';
        }

        ob_start();
        include $view_file;
        return (string) ob_get_clean();
    }
}

if (is_admin()) {
    $fozplaza_instagram_admin_file = fozplaza_instagram_get_module_dir() . 'admin/settings-page.php';
    if (file_exists($fozplaza_instagram_admin_file)) {
        require_once $fozplaza_instagram_admin_file;
    }
}

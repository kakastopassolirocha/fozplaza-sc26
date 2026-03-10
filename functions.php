<?php
/**
 * Determines if the current domain contains a specified term or any term from an array.
 *
 * Retrieves the server's HTTP host and checks whether it includes the provided term (or any term from the given array).
 *
 * @param string|array $term A string or an array of strings to search for within the domain.
 *
 * @return bool Returns true if the domain contains the term or any term from the array, otherwise false.
 */
function INK_domain_contain($term) {
    $url = $_SERVER['HTTP_HOST'];
    if (is_array($term)) {
        foreach ($term as $str) {
            if (strpos($url, $str) !== false) {
                return true;
            }
        }
        return false;
    }
    return strpos($url, $term) !== false;
}

/**
 * Verifica se a URL contém uma ou mais strings.
 *
 * @param string|array $term Uma string ou array de strings a serem verificadas.
 * @return bool Retorna true se a URL contiver pelo menos uma das strings.
 */
function INK_url_contain($term) {
    $url = $_SERVER['REQUEST_URI'];
    if (is_array($term)) {
        foreach ($term as $str) {
            if (strpos($url, $str) !== false) {
                return true;
            }
        }
        return false;
    }
    return strpos($url, $term) !== false;
}

// Defininindo o locale para pt_BR
add_action('init', function() {
    // Define o locale para pt_BR
    setlocale(LC_TIME, 'pt_BR.UTF-8', 'portuguese', 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese-brazil');
});

// * Definições de constantes
define('THEME_URI', get_stylesheet_directory_uri()."/");
define("THEME_DIR", get_stylesheet_directory()."/");
define('THEME_DIST', get_stylesheet_directory_uri()."/dist/");
define('THEME_PUBLIC', get_stylesheet_directory_uri()."/public/");
define('THEME_COMPONENT', get_stylesheet_directory_uri()."/components/");

require_once THEME_DIR . 'inc/configs.php';

// Desativa barra admin no frontend
add_filter('show_admin_bar', '__return_false');

//* Plugins
add_filter('inktrack/config', function ($config) {
    $config['pixel_id'] = '1429042787694485';
    $config['api_version'] = 'v24.0';
    $config['access_token'] = 'EAARnl9wWOUwBO7tsrKPC3KnlVeYSwL1JVsAilRv5NteNy8v6HuVqhxu6BEj47kT7jbImiXAkdOUsEyJyT8O0M3P0AL7LPxDZA7sPwBSp9GWAKQLZCquh8n5QJnJsV45U4njvcYymEJN0zwmjUgEkXlCicc11TSWcrDZCHEe1pZCL5SuE4E4XuqTI5ScKZCxnFigZDZD';
    $config['cookie_name'] = 'INKTRACK_external_id';
    return $config;
});
require_once(THEME_DIR . '/plugins/ink-tracking/ink-tracking.php');

require_once(get_template_directory().'/plugins/instagram/init.php');

add_action( 'wp_enqueue_scripts', function(){
    // Registrando scripts e estilos
    wp_enqueue_style( 'tailwindcss', THEME_URI . 'tailwind/output.css', array(), time(), 'all' );
});
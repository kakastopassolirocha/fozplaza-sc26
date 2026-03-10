<?php

if (!defined('ABSPATH')) {
    exit;
}

/*
|--------------------------------------------------------------------------
| Configuracao do Widget Instagram (tema)
|--------------------------------------------------------------------------
| Preencha os valores abaixo com os dados da sua app/token Meta.
| Este arquivo agora define apenas valores padrao.
| Os dados de producao devem ser preenchidos no painel WP-Admin.
*/
return array(
    'access_token' => '',
    'user_id' => '',
    'profile_url' => 'https://www.instagram.com/fozplazahotel/',
    'api_version' => 'v21.0',
    'cache_ttl' => 1800,
    'default_limit' => 8,
    'debug' => false,
);

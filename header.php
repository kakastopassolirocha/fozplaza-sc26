<!DOCTYPE html>
<html <?php language_attributes();?>>

<head>
    <meta charset="<?php bloginfo( 'charset' );?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <title><?php bloginfo('name'); ?></title>
    <meta name="description" content="<?php bloginfo('description'); ?>">
    
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js" defer></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Favicon padrão para navegadores -->
    <link rel="icon" href="<?=THEME_PUBLIC?>favicon/favicon.ico" type="image/x-icon">

    <!-- Favicon para navegadores modernos (formato SVG) -->
    <link rel="icon" href="<?=THEME_PUBLIC?>favicon/favicon.svg" type="image/svg+xml">

    <!-- Apple Touch Icon para iOS -->
    <link rel="apple-touch-icon" href="<?=THEME_PUBLIC?>favicon/apple-touch-icon.png">

    <!-- Favicon em tamanhos específicos para dispositivos Android e outros -->
    <link rel="icon" href="<?=THEME_PUBLIC?>favicon/favicon-96x96.png" sizes="96x96" type="image/png">

    <!-- Ícones de aplicativo da web (Progressive Web App - PWA) -->
    <link rel="icon" href="<?=THEME_PUBLIC?>favicon/web-app-manifest-192x192.png" sizes="192x192" type="image/png">
    <link rel="icon" href="<?=THEME_PUBLIC?>favicon/web-app-manifest-512x512.png" sizes="512x512" type="image/png">

    <?php if (!INK_domain_contain('.inkweb')): ?>
        <!-- Google Tag Manager -->
        <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-MZJ3M5X9');
        </script>
        <!-- End Google Tag Manager -->
    <?php endif; ?>

    <?php wp_head(); ?>
</head>

<body <?php body_class();?>>
    <?php if (function_exists('wp_body_open')) { wp_body_open(); } ?>
    <?php if (!INK_domain_contain('.inkweb')): ?>
        <!-- Google Tag Manager -->
         <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-MZJ3M5X9');
        </script>
        <!-- Google Tag Manager -->
    <?php endif; ?>
    
    
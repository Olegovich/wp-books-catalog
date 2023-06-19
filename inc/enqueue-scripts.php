<?php
/**
 * Enqueue scripts
 *
 * @package Blogus Child theme
 * @version 1.0.0
 * @author Olegovich
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function enqueue_theme_scripts()
{
    wp_register_script(
        'collection',
        BLOGUS_CHILD_THEME_DIRECTORY_URI . '/template-parts/components/collection/js/collection.js',
        '',
        filemtime(BLOGUS_CHILD_THEME_DIRECTORY . '/template-parts/components/collection/js/collection.js'),
        true,
    );

    wp_register_script(
        'collection-resources-api',
        BLOGUS_CHILD_THEME_DIRECTORY_URI . '/inc/api/collection-resources-api/js/collection-resources-api.js',
        '',
        filemtime(BLOGUS_CHILD_THEME_DIRECTORY . '/inc/api/collection-resources-api/js/collection-resources-api.js'),
        true,
    );

    wp_register_script(
        'ajax-filter',
        BLOGUS_CHILD_THEME_DIRECTORY_URI . '/template-parts/components/ajax-filter/js/ajax-filter.js',
        '',
        filemtime(BLOGUS_CHILD_THEME_DIRECTORY . '/template-parts/components/ajax-filter/js/ajax-filter.js'),
        true,
    );

    wp_register_script(
        'notification',
        BLOGUS_CHILD_THEME_DIRECTORY_URI . '/template-parts/components/notification/js/notification.js',
        '',
        filemtime(BLOGUS_CHILD_THEME_DIRECTORY . '/template-parts/components/notification/js/notification.js'),
        true,
    );

    if (is_page_template('home-page-custom.php')) {
        wp_enqueue_script('collection');
        wp_enqueue_script('collection-resources-api');
        wp_enqueue_script('ajax-filter');
        wp_enqueue_script('notification');
    }
}

add_action('wp_enqueue_scripts', 'enqueue_theme_scripts');

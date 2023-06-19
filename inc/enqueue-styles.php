<?php
/**
 * Enqueue styles
 *
 * @package Blogus Child theme
 * @version 1.0.0
 * @author Olegovich
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function enqueue_theme_primary_styles()
{
}

add_action('wp_enqueue_scripts', 'enqueue_theme_primary_styles');


function enqueue_theme_secondary_styles()
{
    wp_register_style(
        'collection',
        BLOGUS_CHILD_THEME_DIRECTORY_URI . '/template-parts/components/collection/css/collection.css',
        '',
        filemtime(BLOGUS_CHILD_THEME_DIRECTORY . '/template-parts/components/collection/css/collection.css'),
    );

    wp_register_style(
        'books-list',
        BLOGUS_CHILD_THEME_DIRECTORY_URI . '/template-parts/components/books-list/css/books-list.css',
        '',
        filemtime(BLOGUS_CHILD_THEME_DIRECTORY . '/template-parts/components/books-list/css/books-list.css'),
    );

    wp_register_style(
        'book-card',
        BLOGUS_CHILD_THEME_DIRECTORY_URI . '/template-parts/components/book-card/css/book-card.css',
        '',
        filemtime(BLOGUS_CHILD_THEME_DIRECTORY . '/template-parts/components/book-card/css/book-card.css'),
    );

    wp_register_style(
        'reviews',
        BLOGUS_CHILD_THEME_DIRECTORY_URI . '/template-parts/components/reviews/css/reviews.css',
        '',
        filemtime(BLOGUS_CHILD_THEME_DIRECTORY . '/template-parts/components/reviews/css/reviews.css'),
    );

    if (is_page_template('home-page-custom.php')) {
        wp_enqueue_style('collection');
        wp_enqueue_style('books-list');
    }

    if (is_page_template('home-page-custom.php') || is_singular('book')) {
        wp_enqueue_style('book-card');
    }

    if (is_singular('book')) {
        wp_enqueue_style('reviews');
    }
}

add_action('get_footer', 'enqueue_theme_secondary_styles');

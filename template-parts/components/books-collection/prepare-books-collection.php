<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Prepare data for ajax response in books collection.
 * @since 1.0.0
 * @package Blogus Child theme
 * @subpackage template-parts/components/books-collection
 * @author Olegovich
 *
 * @param array $requestData
 * @return array
 */
function prepareBooksCollection(array $requestData = []): array
{
    if (empty($requestData)) {
        return [];
    }

    $output = '';
    $taxQuery = [];

    /**
     * Filter by genre
     */
    $genresFilterKey = 'book_genre';
    $genresFilterValue = !empty($requestData['filters']) && !empty($requestData['filters']['filter-by-genre'])
        ? $requestData['filters']['filter-by-genre']
        : '';

    // Query logic
    if ($genresFilterValue && $genresFilterValue !== 'all') {
        $taxQuery = [
            'relation' => 'OR',
            [
                'taxonomy' => $genresFilterKey,
                'field' => 'slug',
                'terms' => [$genresFilterValue],
            ],
        ];
    }

    $postsArgs = [
        'post_type' => 'book',
        'tax_query' => $taxQuery,
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'DESC',
    ];
    $postsQuery = new WP_Query($postsArgs);

    // Prepare html-output
    if ($postsQuery->have_posts()) {
        while ($postsQuery->have_posts()) {
            $postsQuery->the_post();
            $post = $postsQuery->post;

            if (function_exists('prepareBooksItem')) {
                $output .= prepareBooksItem($post);
            }
        }
        wp_reset_postdata();
    }

    return [
        'cards' => $output,
    ];
}

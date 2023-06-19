<?php
/**
 * Component - books-filter
 *
 * @package Blogus Child theme
 * @version 1.0.0
 * @author Olegovich
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Filter by genre
 */
$genresFilterId = 'filter_by_genre';
$genresFilterName = 'filter-by-genre';
$genresFilterLabel = 'Filter by genre';
$genresFilterOptions = ['all' => __('Все жанры', 'blogus-child')];
$genresFilterCurrentOption = 'all';
$genresArr = get_terms([
    'taxonomy' => 'book_genre',
    'hide_empty' => false, // for testing with no results
]);

foreach ($genresArr as $term) {
    $genresFilterOptions += [$term->slug => $term->name];
}

/**
 * Include component: ajax-filter
 */
get_template_part(
    'template-parts/components/ajax-filter/ajax-filter',
    '',
    [
        'form_fields' => [
            [
                'field_type' => 'select',
                'field_settings' => [
                    'id' => $genresFilterId,
                    'name' => $genresFilterName,
                    'label' => $genresFilterLabel,
                    'options' => $genresFilterOptions,
                    'current_option_key' => $genresFilterCurrentOption,
                ],
            ],
//            [
//                'field_type' => 'select',
//                'field_settings' => [
//                    'id' => 'filter_by_tag',
//                    'name' => 'filter-by-tag',
//                    'label' => 'Example of filter by tag',
//                    'options' => ['all' => 'All tags', 'test-tag' => 'Test tag'],
//                    'current_option_key' => 'all',
//                ],
//            ],
//            [
//                'field_type' => 'text',
//                'field_settings' => [],
//            ],
//            [
//                'field_type' => 'textarea',
//                'field_settings' => [],
//            ],
        ],
    ],
);

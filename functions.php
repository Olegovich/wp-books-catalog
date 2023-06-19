<?php
/**
 * Define constants
 */
define('BLOGUS_CHILD_THEME_DIRECTORY', get_stylesheet_directory());
define('BLOGUS_CHILD_THEME_DIRECTORY_URI', get_stylesheet_directory_uri());


/**
 * Enqueue theme styles and scripts
 */
require_once BLOGUS_CHILD_THEME_DIRECTORY . '/inc/enqueue-styles.php';
require_once BLOGUS_CHILD_THEME_DIRECTORY . '/inc/enqueue-scripts.php';


/**
 * Add custom settings page in admin panel
 */
if (function_exists('acf_add_options_page')) {
    acf_add_options_page([
        'page_title' => __('Blogus child theme custom settings', 'blogus-child'),
        'menu_title' => __('Theme custom settings', 'blogus-child'),
        'menu_slug' => 'blogus-child-theme-custom-settings',
        'capability' => 'edit_posts',
        'redirect' => false,
    ]);
}


/**
 * Include custom widgets
 */
require_once BLOGUS_CHILD_THEME_DIRECTORY . '/inc/widgets/widget-ajax-filter.php';


/**
 * Register ajax-filter widget
 */
function register_ajax_filter_widget()
{
    register_widget('Ajax_filter_widget');
}

add_action('widgets_init', 'register_ajax_filter_widget');


/**
 * Filters the settings for a particular widget instance.
 * Returning false will effectively short-circuit display of the widget.
 * @param array $instance The current widget instance settings.
 * @param WP_Widget $widget The current widget instance.
 * @param array $args An array of default widget arguments.
 * @return array|bool
 */
function handle_widget_instance(array $instance, WP_Widget $widget, array $args): array|bool
{
    if ($widget->id_base === 'ajax_filter') {
        if (is_page_template('home-page-custom.php')) {
            return $instance;
        }

        return false;
    }

    return $instance;
}

add_filter('widget_display_callback', 'handle_widget_instance', 10, 3);


/**
 * Register taxonomy and post type: book
 */
function register_book_taxonomy_and_post_type()
{
    register_taxonomy(
        'book_genre',
        'book',
        [
            'labels' => [
                'name' => __('Genres', 'blogus-child'),
                'singular_name' => __('Genre', 'blogus-child'),
                'edit_item' => __('Edit Genre', 'blogus-child'),
                'view_item' => __('View Genre', 'blogus-child'),
                'update_item' => __('Update Genre', 'blogus-child'),
                'add_new_item' => __('Add New Genre', 'blogus-child'),
                'new_item_name' => __('New Genre Name', 'blogus-child'),
                'parent_item' => __('Parent Genre', 'blogus-child'),
                'parent_item_colon' => __('Parent Genre:', 'blogus-child'),
                'all_items' => __('All Genres', 'blogus-child'),
                'search_items' => __('Search Genres', 'blogus-child'),
                'not_found' => __('No genres found.', 'blogus-child'),
            ],
            'public' => true,
            'hierarchical' => true,
            'rewrite' => [
                'slug' => 'book-genre',
            ],
            'show_admin_column' => true,
            'show_in_rest' => true,
        ]
    );

    register_post_type(
        'book',
        [
            'labels' => [
                'name' => __('Books', 'blogus-child'),
                'singular_name' => __('Book', 'blogus-child'),
                'edit_item' => __('Edit Book', 'blogus-child'),
                'view_item' => __('View Book', 'blogus-child'),
                'add_new_item' => __('Add New Book', 'blogus-child'),
                'new_item' => __('New Book', 'blogus-child'),
                'all_items' => __('All Books', 'blogus-child'),
                'view_items' => __('View Books', 'blogus-child'),
                'search_items' => __('Search Books', 'blogus-child'),
                'not_found' => __('No books found.', 'blogus-child'),
                'not_found_in_trash' => __('No books found in Trash.', 'blogus-child'),
            ],
            'public' => true,
            'has_archive' => 'books',
            'taxonomies' => ['book_genre'],
            'rewrite' => [
                'slug' => 'book',
                'with_front' => false,
                'pages' => true,
                'feeds' => true,
            ],
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-book',
            'menu_position' => 20,
            'supports' => [
                'title',
                'editor',
                'thumbnail',
                'author',
                'revisions',
            ],
        ]
    );
}

add_action('init', 'register_book_taxonomy_and_post_type');


/**
 * Books collection functionality
 */
// Include necessary files
require_once BLOGUS_CHILD_THEME_DIRECTORY . '/inc/api/google-books-api/class-google-books-volumes-api.php';
require_once BLOGUS_CHILD_THEME_DIRECTORY . '/template-parts/components/books-list/prepare-books-item.php';
require_once BLOGUS_CHILD_THEME_DIRECTORY . '/template-parts/components/books-collection/prepare-books-collection.php';

// Init main script for books collection
function init_books_collection()
{
    if (is_page_template('home-page-custom.php')) {
        $data = [
            'URL' => admin_url('admin-ajax.php'),
        ];

        wp_register_script(
            'books-collection',
            BLOGUS_CHILD_THEME_DIRECTORY_URI . '/template-parts/components/books-collection/js/books-collection.js',
            '',
            filemtime(BLOGUS_CHILD_THEME_DIRECTORY . '/template-parts/components/books-collection/js/books-collection.js'),
            true,
        );
        wp_enqueue_script('books-collection');

        wp_add_inline_script(
            'books-collection',
            'const ADMIN_AJAX_API = ' . json_encode($data),
            'before',
        );
    }
}

add_action('wp_enqueue_scripts', 'init_books_collection');


/**
 * Handle resources-api for ajax functionality on any collections
 */
function handle_resources_api()
{
    // Get data from ajax request
    $collection = !empty($_POST['collection']) ? $_POST['collection'] : '';
    $filters = [];

    if (!empty($_POST['filters'])) {
        parse_str($_POST['filters'], $filters);
    }

    $requestData = [
        'collection' => $collection,
        'filters' => $filters,
    ];
    $response = [];

    switch ($collection) {
        case 'books':
            if (function_exists('prepareBooksCollection')) {
                $response = prepareBooksCollection($requestData);
            }
            break;
//        case 'portfolio':
//        case 'services':
    }

    // Send data to ajax response
    wp_send_json([
        'cards' => !empty($response['cards']) ? $response['cards'] : 0,
    ]);
}

add_action('wp_ajax_resources_api', 'handle_resources_api');
add_action('wp_ajax_nopriv_resources_api', 'handle_resources_api');

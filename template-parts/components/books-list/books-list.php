<?php
/**
 * Component - books-list
 *
 * @package Blogus Child theme
 * @version 1.0.0
 * @author Olegovich
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get all posts (without pagination)
$booksArr = get_posts([
    'post_type' => 'book',
    'post_status' => 'publish',
    'numberposts' => -1,
    'orderby' => 'title',
    'order' => 'DESC',
]);
?>

<?php if ($booksArr) { ?>
    <ul class="books-list ajax-cards-list" role="list">
        <?php
        $output = '';

        foreach ($booksArr as $post) {
            setup_postdata($post);

            if (function_exists('prepareBooksItem')) {
                $output .= prepareBooksItem($post);
            }
        }
        wp_reset_postdata();

        echo $output;
        ?>
    </ul>
<?php } ?>

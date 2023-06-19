<?php
/**
 * Component - books-collection
 *
 * @package Blogus Child theme
 * @version 1.0.0
 * @author Olegovich
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$collection = 'books';
$cardsTextEmpty = 'No books were found.';
$cardsTextError = 'Something went wrong. Try reloading the page.';
?>

<div class="collection"
     data-collection="<?php echo $collection; ?>"
     data-cards-text-empty="<?php _e($cardsTextEmpty, 'blogus-child'); ?>"
     data-cards-text-error="<?php _e($cardsTextError, 'blogus-child'); ?>"
>
    <?php
    /**
     * Include component: books-list
     */
    get_template_part('template-parts/components/books-list/books-list');

    /**
     * Include component: notification
     */
    get_template_part(
        'template-parts/components/notification/notification',
        '',
        ['notification_class' => 'alert alert-primary'],
    );
    ?>
</div>

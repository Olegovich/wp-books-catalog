<?php
/**
 * Component - reviews
 *
 * @package Blogus Child theme
 * @version 1.0.0
 * @author Olegovich
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$reviewsTitle = get_field('reviews_title');
$reviewsList = get_field('reviews_list');
?>

<?php if ($reviewsList) { ?>
    <div class="reviews">
        <?php if ($reviewsTitle) { ?>
            <h2 class="reviews__title"><?php echo $reviewsTitle; ?></h2>
        <?php }

        foreach ($reviewsList as $item) {
            $itemAuthorName = $item['item_author_name'];
            $itemDate = $item['item_date'];
            $itemQuotation = $item['item_quotation'];
            $itemAuthorNameToOutput = $itemAuthorName && $itemDate
                ? $itemAuthorName . ' · ' . $itemDate
                : $itemAuthorName;
            ?>

            <?php if ($itemQuotation) { ?>
                <figure class="reviews__item">
                    <blockquote class="reviews__item-quotation"><p><?php echo $itemQuotation; ?></p></blockquote>

                    <?php if ($itemAuthorName) { ?>
                        <figcaption class="reviews__item-author"><?php echo $itemAuthorNameToOutput; ?></figcaption>
                    <?php } ?>
                </figure>
            <?php }
        } ?>
    </div>
<?php } ?>

<?php
/**
 * Template for displaying single book.
 *
 * @package Blogus Child theme
 * @version 1.0.0
 * @author Olegovich
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

get_header();

$postLayout = get_theme_mod(
    'blogus_single_page_layout',
    'single-align-content-right',
);
$postWrapperClass = $postLayout === 'single-full-width-content' ? 'col-lg-12' : 'col-lg-9';
$postGenres = get_the_terms(get_the_ID(), 'book_genre');
$postIsbn = get_field('book_isbn');

// Data from Google Books API
$api = new Google_Books_Volumes_API(['isbn' => $postIsbn]);
$apiResponseData = $api->makeRequest();

$postAuthor = !empty($apiResponseData['volumeInfo']) && !empty($apiResponseData['volumeInfo']['authors'])
    ? implode(', ', $apiResponseData['volumeInfo']['authors'])
    : null;

$postPublishedDate = !empty($apiResponseData['volumeInfo']) && !empty($apiResponseData['volumeInfo']['publishedDate'])
    ? $apiResponseData['volumeInfo']['publishedDate']
    : null;

$postAuthorToOutput = $postAuthor && $postPublishedDate
    ? $postAuthor . ' · ' . date('Y', strtotime($postPublishedDate))
    : $postAuthor;

$postPublisher = !empty($apiResponseData['volumeInfo']) && !empty($apiResponseData['volumeInfo']['publisher'])
    ? $apiResponseData['volumeInfo']['publisher']
    : null;

$postThumbnail = !empty($apiResponseData['volumeInfo'])
&& !empty($apiResponseData['volumeInfo']['imageLinks'])
&& !empty($apiResponseData['volumeInfo']['imageLinks']['thumbnail'])
    ? $apiResponseData['volumeInfo']['imageLinks']['thumbnail']
    : null;
?>

<main id="content">
    <div class="container">
        <!--row-->
        <div class="row">
            <?php
            if ($postLayout === 'single-align-content-left') { ?>
                <!--col-lg-3-->
                <aside class="col-lg-3">
                    <?php get_sidebar(); ?>
                </aside>
                <!--/col-lg-3-->
            <?php } ?>

            <!--col-lg-->
            <div class="<?php echo $postWrapperClass; ?>">
                <div class="bs-blog-post single">
                    <div class="bs-header">
                        <div class="book-card">
                            <div class="book-card__content">
                                <?php if ($postGenres) { ?>
                                    <div class="bs-blog-category">
                                        <?php foreach ($postGenres as $term) {
                                            $termUrl = get_term_link($term);
                                            $termTitle = $term->name;

                                            if ($termUrl && $termTitle) { ?>
                                                <a class="blogus-categories category-color-1"
                                                   href="<?php echo $termUrl; ?>"
                                                ><?php echo $termTitle; ?></a>
                                            <?php }
                                        } ?>
                                    </div>
                                <?php } ?>

                                <h1 class="book-card__title title"><?php the_title(); ?></h1>

                                <?php if ($postAuthor) { ?>
                                    <p class="book-card__info"><?php echo $postAuthorToOutput; ?></p>
                                <?php }

                                if ($postPublisher) { ?>
                                    <p class="book-card__info"><i class="fa fa-feather"></i><?php echo str_replace('"', '', $postPublisher); ?></p>
                                <?php } ?>
                            </div>

                            <?php if ($postThumbnail) { ?>
                                <div class="book-card__img-wrapper">
                                    <img class="book-card__img" src="<?php echo $postThumbnail; ?>" alt="">
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <article class="small single">
                        <?php
                        if (have_posts()) {
                            while (have_posts()) {
                                the_post(); ?>

                                <div class="single__content"><?php the_content(); ?></div>
                            <?php }
                        }

                        /**
                         * Include component: reviews
                         */
                        get_template_part('template-parts/components/reviews/reviews');
                        ?>
                    </article>
                </div>
            </div>
            <!--/col-lg-->

            <?php if ($postLayout === 'single-align-content-right') { ?>
                <!--col-lg-3-->
                <aside class="col-lg-3">
                    <?php get_sidebar(); ?>
                </aside>
                <!--/col-lg-3-->
            <?php } ?>
        </div>
        <!--/row-->
    </div>
    <!--/container-->
</main>

<?php
get_footer();

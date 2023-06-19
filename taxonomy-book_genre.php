<?php
/**
 * Template for displaying book_genre taxonomy.
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

$term = get_queried_object();
$termId = $term->term_id;
$termTaxonomy = $term->taxonomy;
$termSlug = $term->slug;
$termName = $term->name;
?>

<main id="content">
    <!--container-->
    <div class="container">
        <!--row-->
        <div class="row">
            <!--col-lg-8-->
            <div class="col-lg-8">
                <div class="bs-card-box padding-20">
                    <h1 class="entry-title"><?php echo $termName; ?></h1>

                    <?php
                    // Get all posts (without pagination)
                    $booksArr = get_posts([
                        'post_type' => 'book',
                        'tax_query' => [
                            [
                                'taxonomy' => $termTaxonomy,
                                'field' => 'slug',
                                'terms' => $termSlug,
                            ],
                        ],
                        'post_status' => 'publish',
                        'numberposts' => -1,
                        'orderby' => 'title',
                        'order' => 'DESC',
                    ]);

                    if ($booksArr) { ?>
                        <ul role="list">
                            <?php foreach ($booksArr as $post) {
                                setup_postdata($post);

                                $postId = $post->ID;
                                $postLink = get_permalink($postId);
                                $postTitle = get_the_title();
                                ?>

                                <li>
                                    <article>
                                        <?php if ($postLink && $postTitle) { ?>
                                            <h3>
                                                <a href="<?php echo $postLink; ?>"><?php echo $postTitle; ?></a>
                                            </h3>
                                        <?php } ?>
                                    </article>
                                </li>
                            <?php }
                            wp_reset_postdata();
                            ?>
                        </ul>
                    <?php } ?>
                </div>
            </div>
            <!--/col-lg-8-->

            <!--col-lg-4-->
            <aside class="col-lg-4">
                <?php get_sidebar(); ?>
            </aside>
            <!--/col-lg-4-->
        </div><!--/row-->
    </div><!--/container-->
</main>

<?php
get_footer();

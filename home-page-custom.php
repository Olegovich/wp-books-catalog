<?php
/**
 * Template Name: Home page
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
?>

<main id="content">
    <!--container-->
    <div class="container">
        <!--row-->
        <div class="row">
            <!--col-lg-8-->
            <div class="col-lg-8">
                <div class="bs-card-box padding-20">
                    <h1 class="entry-title"><?php the_title(); ?></h1>

                    <?php
                    /**
                     * Include component: books-collection
                     */
                    get_template_part('template-parts/components/books-collection/books-collection');
                    ?>
                </div>
            </div>
            <!--/col-lg-8-->

            <!--col-lg-4-->
            <aside class="col-lg-4">
                <?php get_sidebar();?>
            </aside>
            <!--/col-lg-4-->
        </div><!--/row-->
    </div><!--/container-->
</main>

<?php
get_footer();

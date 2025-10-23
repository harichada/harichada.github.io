<?php
/**
 * The template for displaying archive pages
 *
 * @package OpenSourceBox
 */

get_header();
?>

<main id="primary" class="content-area">

    <?php if (have_posts()) : ?>

        <header class="page-header">
            <?php
            the_archive_title('<h1 class="page-title">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
            ?>
        </header>

        <?php
        while (have_posts()) :
            the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>

                    <?php if ('post' === get_post_type()) : ?>
                        <div class="entry-meta">
                            <?php
                            opensourcebox_posted_on();
                            opensourcebox_posted_by();
                            ?>
                        </div>
                    <?php endif; ?>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('large'); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php the_excerpt(); ?>
                    <a href="<?php the_permalink(); ?>" class="more-link">
                        <?php esc_html_e('Read More', 'opensourcebox'); ?> &rarr;
                    </a>
                </div>
            </article>

            <?php
        endwhile;

        the_posts_pagination(array(
            'mid_size'  => 2,
            'prev_text' => esc_html__('&larr; Previous', 'opensourcebox'),
            'next_text' => esc_html__('Next &rarr;', 'opensourcebox'),
        ));

    else :
        ?>

        <section class="no-results not-found">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('Nothing Found', 'opensourcebox'); ?></h1>
            </header>

            <div class="page-content">
                <p><?php esc_html_e('It seems we can\'t find what you\'re looking for.', 'opensourcebox'); ?></p>
                <?php get_search_form(); ?>
            </div>
        </section>

        <?php
    endif;
    ?>

</main>

<?php
get_sidebar();
get_footer();

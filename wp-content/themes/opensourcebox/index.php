<?php
/**
 * The main template file
 *
 * @package OpenSourceBox
 */

get_header();
?>

<main id="primary" class="content-area">

    <?php
    if (have_posts()) :

        if (is_home() && !is_front_page()) :
            ?>
            <header class="page-header">
                <h1 class="page-title"><?php single_post_title(); ?></h1>
            </header>
            <?php
        endif;

        // Start the Loop
        while (have_posts()) :
            the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php
                    if (is_singular()) :
                        the_title('<h1 class="entry-title">', '</h1>');
                    else :
                        the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                    endif;
                    ?>

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
                        <?php
                        if (is_singular()) :
                            the_post_thumbnail('large');
                        else :
                            ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('large'); ?>
                            </a>
                            <?php
                        endif;
                        ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php
                    if (is_singular()) :
                        the_content();

                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'opensourcebox'),
                            'after'  => '</div>',
                        ));
                    else :
                        the_excerpt();
                        ?>
                        <a href="<?php the_permalink(); ?>" class="more-link">
                            <?php esc_html_e('Read More', 'opensourcebox'); ?> &rarr;
                        </a>
                        <?php
                    endif;
                    ?>
                </div>

                <?php if (is_singular() && 'post' === get_post_type()) : ?>
                    <footer class="entry-footer">
                        <?php
                        opensourcebox_entry_categories();
                        opensourcebox_entry_tags();
                        ?>
                    </footer>
                <?php endif; ?>
            </article>

            <?php
        endwhile;

        // Pagination
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
                <?php
                if (is_home() && current_user_can('publish_posts')) :
                    ?>
                    <p>
                        <?php
                        printf(
                            wp_kses(
                                __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'opensourcebox'),
                                array(
                                    'a' => array(
                                        'href' => array(),
                                    ),
                                )
                            ),
                            esc_url(admin_url('post-new.php'))
                        );
                        ?>
                    </p>
                    <?php
                else :
                    ?>
                    <p><?php esc_html_e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'opensourcebox'); ?></p>
                    <?php
                    get_search_form();
                endif;
                ?>
            </div>
        </section>

        <?php
    endif;
    ?>

</main>

<?php
get_sidebar();
get_footer();

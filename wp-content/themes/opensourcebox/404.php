<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package OpenSourceBox
 */

get_header();
?>

<main id="primary" class="content-area">
    <section class="error-404 not-found">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'opensourcebox'); ?></h1>
        </header>

        <div class="page-content">
            <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try searching?', 'opensourcebox'); ?></p>

            <?php get_search_form(); ?>

            <h2><?php esc_html_e('Recent Posts', 'opensourcebox'); ?></h2>
            <ul>
                <?php
                wp_list_pages(array(
                    'title_li' => '',
                    'number'   => 5,
                ));
                ?>
            </ul>
        </div>
    </section>
</main>

<?php
get_footer();

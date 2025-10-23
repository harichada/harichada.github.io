<?php
/**
 * OpenSourceBox Theme Functions
 *
 * @package OpenSourceBox
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Theme Setup
 */
function opensourcebox_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(1200, 630, true);

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'opensourcebox'),
        'footer'  => esc_html__('Footer Menu', 'opensourcebox'),
    ));

    // Switch default core markup to output valid HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}
add_action('after_setup_theme', 'opensourcebox_setup');

/**
 * Set the content width in pixels
 */
function opensourcebox_content_width() {
    $GLOBALS['content_width'] = apply_filters('opensourcebox_content_width', 1200);
}
add_action('after_setup_theme', 'opensourcebox_content_width', 0);

/**
 * Register widget areas
 */
function opensourcebox_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'opensourcebox'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here to appear in your sidebar.', 'opensourcebox'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 1', 'opensourcebox'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets here to appear in your footer.', 'opensourcebox'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 2', 'opensourcebox'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Add widgets here to appear in your footer.', 'opensourcebox'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer 3', 'opensourcebox'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Add widgets here to appear in your footer.', 'opensourcebox'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'opensourcebox_widgets_init');

/**
 * Enqueue scripts and styles
 */
function opensourcebox_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style('opensourcebox-style', get_stylesheet_uri(), array(), '1.0.0');

    // Enqueue comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'opensourcebox_scripts');

/**
 * Custom excerpt length
 */
function opensourcebox_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'opensourcebox_excerpt_length', 999);

/**
 * Custom excerpt more
 */
function opensourcebox_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'opensourcebox_excerpt_more');

/**
 * Add a pingback url auto-discovery header for single posts
 */
function opensourcebox_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'opensourcebox_pingback_header');

/**
 * Custom post meta display
 */
function opensourcebox_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

    $time_string = sprintf($time_string,
        esc_attr(get_the_date(DATE_W3C)),
        esc_html(get_the_date())
    );

    $posted_on = sprintf(
        esc_html_x('Posted on %s', 'post date', 'opensourcebox'),
        '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
    );

    echo '<span class="posted-on">' . $posted_on . '</span>';
}

/**
 * Display post author
 */
function opensourcebox_posted_by() {
    $byline = sprintf(
        esc_html_x('by %s', 'post author', 'opensourcebox'),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
    );

    echo '<span class="byline"> ' . $byline . '</span>';
}

/**
 * Display categories
 */
function opensourcebox_entry_categories() {
    if ('post' === get_post_type()) {
        $categories_list = get_the_category_list(esc_html__(', ', 'opensourcebox'));
        if ($categories_list) {
            printf('<span class="cat-links">' . esc_html__('Categories: %1$s', 'opensourcebox') . '</span>', $categories_list);
        }
    }
}

/**
 * Display tags
 */
function opensourcebox_entry_tags() {
    if ('post' === get_post_type()) {
        $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'opensourcebox'));
        if ($tags_list) {
            printf('<span class="tags-links">' . esc_html__('Tags: %1$s', 'opensourcebox') . '</span>', $tags_list);
        }
    }
}

/**
 * Default menu fallback
 */
function opensourcebox_default_menu() {
    echo '<ul id="primary-menu" class="menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'opensourcebox') . '</a></li>';
    wp_list_pages(array(
        'title_li' => '',
        'depth'    => 1,
    ));
    echo '</ul>';
}

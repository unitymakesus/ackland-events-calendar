<?php

namespace App;

use Roots\Sage\Container;
use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Template\Blade;
use Roots\Sage\Template\BladeProvider;

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('sage/main.css', asset_path('styles/main.css'), false, null);
    wp_enqueue_script('sage/main.js', asset_path('scripts/main.js'), ['jquery'], null, true);

    if (is_single() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Set array of theme customizations for JS
    wp_localize_script( 'sage/main.js', 'simple_options', array('fonts' => get_theme_mod('theme_fonts'), 'colors' => get_theme_mod('theme_color')) );
}, 100);

/**
 * Theme setup
 */
add_action('after_setup_theme', function () {
    /**
     * Enable features from Soil when plugin is activated
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil-clean-up');
    add_theme_support('soil-jquery-cdn');
    add_theme_support('soil-nav-walker');
    add_theme_support('soil-nice-search');
    // add_theme_support('soil-relative-urls');

    /**
     * REMOVE WP EMOJI
     */
     remove_action('wp_head', 'print_emoji_detection_script', 7);
     remove_action('wp_print_styles', 'print_emoji_styles');
     remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
     remove_action( 'admin_print_styles', 'print_emoji_styles' );

    /**
     * Enable plugins to manage the document title
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Register navigation menus
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
        'social_links' => __('Social Links', 'sage')
    ]);

    /**
     * Enable post thumbnails
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable HTML5 markup support
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

    /**
     * Enable selective refresh for widgets in customizer
     * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
     */
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Use main stylesheet for visual editor
     * @see resources/assets/styles/layouts/_tinymce.scss
     */
    add_editor_style(asset_path('styles/main.css'));

    /**
    * Add support for Gutenberg.
    *
    * @link https://wordpress.org/gutenberg/handbook/reference/theme-support/
    */
    add_theme_support( 'align-wide' );
    add_theme_support( 'disable-custom-colors' );
    add_theme_support( 'wp-block-styles' );

    /**
     * Enqueue editor styles for Gutenberg
     */
    function simple_editor_styles() {
      wp_enqueue_style( 'simple-gutenberg-style', asset_path('styles/main.css') );
    }
    // add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\simple_editor_styles' );

    /**
     * Add image quality
     */
    add_filter('jpeg_quality', function($arg){return 100;});

    /**
     * Enable logo uploader in customizer
     */
    add_image_size('simple-logo', 200, 200, false);
    add_image_size('simple-logo-2x', 400, 400, false);
    add_theme_support('custom-logo', array(
      'size' => 'simple-logo-2x'
    ));

    /**
     * Add image sizes
     */
    add_image_size('tiny-thumbnail', 80, 80, true);
    add_image_size('small-thumbnail', 150, 150, true);
    add_image_size('medium-square-thumbnail', 400, 400, true);

    add_filter( 'image_size_names_choose', function( $sizes ) {
      return array_merge( $sizes, array(
        'tiny-thumbnail' => __( 'Tiny Thumbnail' ),
        'small-thumbnail' => __( 'Small Thumbnail' ),
        'medium-square-thumbnail' => __( 'Medium Square Thumbnail' ),
      ) );
    } );

}, 20);

/**
 * Register sidebars
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ];
    register_sidebar([
        'name'          => __('Primary', 'sage'),
        'id'            => 'sidebar-primary'
    ] + $config);
    register_sidebar([
        'name'          => __('Footer-Left', 'sage'),
        'id'            => 'footer-left'
    ] + $config);
    register_sidebar([
        'name'          => __('Footer-Center', 'sage'),
        'id'            => 'footer-center'
    ] + $config);
    register_sidebar([
        'name'          => __('Footer-Right', 'sage'),
        'id'            => 'footer-right'
    ] + $config);
});

/**
 * Updates the `$post` variable on each iteration of the loop.
 * Note: updated value is only available for subsequently loaded views, such as partials
 */
add_action('the_post', function ($post) {
    sage('blade')->share('post', $post);
});

/**
 * Setup Sage options
 */
add_action('after_setup_theme', function () {
    /**
     * Add JsonManifest to Sage container
     */
    sage()->singleton('sage.assets', function () {
        return new JsonManifest(config('assets.manifest'), config('assets.uri'));
    });

    /**
     * Add Blade to Sage container
     */
    sage()->singleton('sage.blade', function (Container $app) {
        $cachePath = config('view.compiled');
        if (!file_exists($cachePath)) {
            wp_mkdir_p($cachePath);
        }
        (new BladeProvider($app))->register();
        return new Blade($app['view']);
    });

    /**
     * Create @asset() Blade directive
     */
    sage('blade')->compiler()->directive('asset', function ($asset) {
        return "<?= " . __NAMESPACE__ . "\\asset_path({$asset}); ?>";
    });

    /**
     * Configure SVG location for @svg() Blade directive
     */
    add_filter('bladesvg_image_path', function () {
      return \BladeSvgSage\get_dist_path('images');
    });
});

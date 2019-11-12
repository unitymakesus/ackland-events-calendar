<?php

namespace App;

/**
 * Add <body> classes
 */
add_filter('body_class', function (array $classes) {
    /** Add page slug if it doesn't exist */
    if (is_single() || is_page() && !is_front_page()) {
        if (!in_array(basename(get_permalink()), $classes)) {
            $classes[] = basename(get_permalink());
        }
    }

    if (is_home()) {
      $classes[] = 'archive';
    }

    /** Add class if sidebar is active */
    if (display_sidebar()) {
        $classes[] = 'sidebar-primary';
    }

    /** Clean up class names for custom templates */
    $classes = array_map(function ($class) {
        return preg_replace(['/-blade(-php)?$/', '/^page-template-views/'], '', $class);
    }, $classes);

    return array_filter($classes);
});

/**
 * Add "… Continued" to the excerpt
 */
add_filter('excerpt_more', function () {
    // return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
});

/**
 * Template Hierarchy should search for .blade.php files
 */
collect([
    'index', '404', 'archive', 'author', 'category', 'tag', 'taxonomy', 'date', 'home',
    'frontpage', 'page', 'paged', 'search', 'single', 'singular', 'attachment'
])->map(function ($type) {
    add_filter("{$type}_template_hierarchy", __NAMESPACE__.'\\filter_templates');
});

/**
 * Render page using Blade
 */
 add_filter('template_include', function ($template) {
     collect(['get_header', 'wp_head'])->each(function ($tag) {
         ob_start();
         do_action($tag);
         $output = ob_get_clean();
         remove_all_actions($tag);
         add_action($tag, function () use ($output) {
             echo $output;
         });
     });
     $data = collect(get_body_class())->reduce(function ($data, $class) use ($template) {
         return apply_filters("sage/template/{$class}/data", $data, $template);
     }, []);
     if ($template) {
         echo template($template, $data);
         return get_stylesheet_directory().'/index.php';
     }
     return $template;
 }, PHP_INT_MAX);

 /**
  * Render comments.blade.php
  */
  add_filter('comments_template', function ($comments_template) {
      $comments_template = str_replace(
          [get_stylesheet_directory(), get_template_directory()],
          '',
          $comments_template
      );

      $data = collect(get_body_class())->reduce(function ($data, $class) use ($comments_template) {
          return apply_filters("sage/template/{$class}/data", $data, $comments_template);
      }, []);

      $theme_template = locate_template(["views/{$comments_template}", $comments_template]);

      if ($theme_template) {
          echo template($theme_template, $data);
          return get_stylesheet_directory().'/index.php';
      }

      return $comments_template;
  }, 100);

/**
 * Setup sidebar
 */
add_filter('sage/display_sidebar', function ($display) {
  static $display;

  isset($display) || $display = in_array(true, [
    // The sidebar will be displayed if any of the following return true
    // is_singular('post'),
    // is_home(),
    // is_date(),
    // is_category()
  ]);

  return $display;
});

/**
 * Remove prefixes from category and tag archive titles
 */
add_filter( 'get_the_archive_title', function ($title) {
  if ( is_category() ) {
    $title = single_cat_title( '', false );
  } elseif ( is_tag() ) {
    $title = single_tag_title( '', false );
  }
  return $title;
});

/**
 * If there's a subtitle, auto add it after the titles
 */
if ( ! is_admin() ) { // Don't touch anything inside of the WordPress Dashboard, yet.
	add_filter( 'the_title', function($title, $id = null) {
    /**
     * Which globals will we need?
     */
    global $post;

    /**
     * Check if $post is set. There's a chance that this can
     * be NULL on search results pages with zero results.
     */
    if ( ! isset( $post ) ) {
    	return $title;
    }

    /**
     * Make sure we're not touching any of the titles in the Dashboard
     * This filtering should only happen on the front end of the site.
     */
    if ( is_admin() ) {
    	return $title;
    }

    /**
     * Bail early if ACF isn't active
     */
    if ( !function_exists('get_field') ) {
      return $title;
    }

    /**
     * Bail early if no subtitle has been set for the post.
     */
    $post_id = (int) absint( $post->ID ); // post ID should always be a non-negative integer
    $subtitle = (string) html_entity_decode( get_field('subtitle'), ENT_QUOTES );

    if ( empty($subtitle) ) {
    	return $title;
    }

		/**
		 * Make sure we're in The Loop.
		 *
		 * @see in_the_loop()
		 * @link http://codex.wordpress.org/Function_Reference/in_the_loop
		 *
		 * @since 1.0.0
		 */
		if ( ! in_the_loop() ) {
			return $title;
		}

    /**
     * Let theme authors modify the subtitle markup, in case spans aren't appropriate
     * for what they are trying to do with their themes.
     *
     * The reason that spans are being used is because HTML does not have a dedicated
     * mechanism for marking up subheadings, alternative titles, or taglines. There
     * are suggested alternatives from the World Wide Web Consortium (W3C); among them
     * are spans, which work well for what we're trying to do with titles in WordPress.
     * See the linked documentation for more information.
     *
     * @link http://www.w3.org/html/wg/drafts/html/master/common-idioms.html#sub-head
     *
     * @since 1.0.0
     */
    $subtitle_markup = apply_filters(
    	'subtitle_markup',
    	array(
    		'before' => '<span class="entry-subtitle">',
    		'after'  => '</span>',
    	)
    );

    $subtitle = $subtitle_markup['before'] . $subtitle . $subtitle_markup['after'];

    /**
     * Put together the final title and subtitle set
     *
     * @since 1.0.0
     */
    $title = '<span class="entry-title-primary">' . $title . '</span> ' . $subtitle;

    return $title;
  }, 10, 2 );
}

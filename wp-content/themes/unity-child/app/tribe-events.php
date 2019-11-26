<?php

namespace App;

/**
 * Tell tribe how to find the compiled path of {template}.blade.php
 */
add_filter('tribe_events_template', function ($file, $template) {
  return template_path(locate_template(filter_templates(["views/tribe-events/{$template}", $template])) ?: $template);
}, 10, 2);

/**
 * Remove everything from the include, so we can render ourselfves afterwards.
 */
add_action('tribe_events_before_view', function() {
  ob_start();
}, PHP_INT_MIN); // This runs as lasts, prevent conflicts with others that hook in to there.
add_action('tribe_events_after_view', function() {
  ob_end_clean();
}, PHP_INT_MAX-10); // This runs as first, prevent conflicts with others that hook in to there.

/**
 * Render page using Blade
 */
array_map('add_filter', ['template_include', 'tribe_events_after_view'],
  array_fill(0, 2, function ($template) {
    $data = collect(get_body_class())->reduce(function ($data, $class) use ($template) {
      return apply_filters("sage/template/{$class}/data", $data, $template);
    }, []);

    if ($template) {
      echo template($template, $data);
      return get_stylesheet_directory().'/index.php';
    }
    return $template;
  }),
  array_fill(0, 2, PHP_INT_MAX)
);

/**
  * Changes the text labels for Google Calendar and iCal buttons on a single event page.
  * @link https://theeventscalendar.com/knowledgebase/change-the-text-for-ical-and-google-calendar-export-buttons/
*/
remove_action('tribe_events_single_event_after_the_content', [tribe( 'tec.iCal' ), 'single_event_links']);

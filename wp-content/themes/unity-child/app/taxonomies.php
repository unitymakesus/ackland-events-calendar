<?php

namespace App;

/**
 * Audiences
 */
$argsEventAudience = [
  'labels' => [
    'name'          => __('Audiences'),
    'singular_name' => __('Audience'),
  ],
  'slug'         => 'tribe_events_audience',
  'post_type'    => 'tribe_events',
  'hierarchical' => true,
];
register_taxonomy('tribe_events_audience', 'tribe_events', $argsEventAudience);

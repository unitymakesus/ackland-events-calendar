<?php

namespace App;

abstract class Base_Controller {
  /**
   * Constructor
   *
   * @param array $settings
   */
  public function __construct( $settings ) {
    $this->register_route($settings);
  }

  /**
   * Register a route with WP.
   *
   * @param  array $settings
   * @return void
   */
  public function register_route( $settings ) {
    $ns = trailingslashit( $settings['namespace'] );
    $v = trailingslashit( (string) $settings['version'] );
    $methods = array_key_exists('methods', $settings) ? $settings['methods'] : 'GET';
    register_rest_route( $ns.'v'.$v, $settings['endpoint'], [
      [
        'methods'   => $methods,
        'callback'  => array( $this, 'get_items' ),
      ],
    ]);
  }

  /**
   * A placeholder method for extending in child classes.
   * This will be the actual response from the API.
   *
   * @abstract
   *
   * @param  WP_REST_Request           $request Full details about the request.
   * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
   */
  abstract function get_items( $request );
}

class Post_Type_Controller extends Base_Controller {
  /**
   * The post type returned from endpoint.
   *
   * @var string
   */
  public $post_type;

  /**
   * Constructor.
   *
   * @param string $post_type
   * @param array  $settings
   */
  public function __construct( $post_type, $settings ) {
    $this->post_type = $post_type;
    parent::__construct($settings);
  }


  /**
   * Returns the HTML of the desired file.
   * This will pass the WP post_ID to the included file.
   *
   * @param integer $id      The post ID
   * @param string  $partial The php file to return
   */
  // private function get_template_part( $id, $partial ) {
  //   $path = get_template_directory().'/templates/partials/';
  //   $filename = $partial.'.php';

  //   // return null if file doesn't exist
  //   if( ! file_exists( $path.$filename ) ) return;

  //   // include the file
  //   ob_start();
  //   include( $path.$filename );
  //   return ob_get_clean();
  // }

  /**
   * Build the query args from a WP_REST_Response object.
   *
   * @param  object $request WP_REST_Response object
   * @return array
   */
  public function build_query_args( $params = null ) {
    // defaults
    $args = [
      'posts_per_page' => 100,
      'post_type' => $this->post_type
    ];

    // Loop through each of our url params and add them to our $args array.
    foreach( $params as $param => $value ) {

      // make sure taxonomies always use a tax_query
      if( taxonomy_exists( $param ) && $value !== '' ) {
        $args['tax_query'][] = [
          'taxonomy' => $param,
          'field' => 'slug',
          'terms' => explode(',', $value)
        ];

      // otherwise, just add params to $args
      } else {
        $args[$param] = is_string($value) ? htmlspecialchars($value) : $value;
      }
    }

    return $args;
  }

  /**
   * Do a DB query.
   * Use SearchWP for search queries, otherwise, use a
   * regular WP_Query.
   *
   * @param  array  $args
   * @return object
   */
  public function do_query( $args ) {
    // if( class_exists('SWP_Query') && isset($args['se']) && $args['se'] ) {
    //   $args['s'] = $args['se'];
    //   return new \SWP_Query( $args );
    // }

    return new \WP_Query( $args );
  }

  /**
   * Get items for our collection. This is the callback for
   * register_rest_route() called in parent::registerRoute method.
   *
   * @param  WP_REST_Request           $request Full details about the request.
   * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
   */
  public function get_items( $request ) {
    $type = $this->post_type;
    $request_params = $request->get_query_params();
    $partial = !is_array($type) ? 'partial-'.$type : 'partial-all';

    // get the args for the query
    $args = apply_filters( 'sage_rest_api_query_args', $this->build_query_args($request_params), $request_params );

    // do the query
    $results = $this->do_query( $args );
    $total_posts = $results->found_posts;
    $max_pages = ceil( $total_posts / (int) $args['posts_per_page'] );

    // prepare our results
    if( !empty($results->posts) ) {
      foreach( $results->posts as $result => $value ) {
        $filtered[] = apply_filters('sage_rest_cpt_result', array(
          'id' => $value->ID,
          'title' => get_the_title( $value->ID ),
          // 'html'  => $this->get_template_part( $value->ID, $partial )
        ), $value->ID);
      }
    } else {
      $filtered = [];
    }

    // Prepare the response.
    $response = rest_ensure_response( $filtered );
    $response->header( 'X-WP-Total', (int) $total_posts );
    $response->header( 'X-WP-TotalPages', (int) $max_pages );

    return $response;
  }
}

/**
 * WP REST API endpoint registration.
 */
function register_rest_routes() {
  $routes = [
    [
      'post_type' => 'tribe_events',
      'endpoint'  => 'events'
    ],
  ];

  foreach ($routes as $route) {
    new Post_Type_Controller($route['post_type'], [
      'namespace' => 'ackland',
      'version'   => 1,
      'endpoint'  => $route['endpoint']
    ]);
  }
}
add_action('rest_api_init', __NAMESPACE__ . '\\register_rest_routes');

/**
 * Return custom data in our response.
 */
function filter_api_result( $result, $post_id ) {
  $result = [
    'id'               => $post_id,
    'title'            => get_the_title($post_id),
    'url'              => get_the_permalink($post_id),
    'excerpt'          => wp_trim_words(get_the_excerpt($post_id), 12, '…'),
    'schedule_details' => tribe_events_event_schedule_details($post_id),
  ];

  /**
   * Append the featured image data if we have one.
   */
  if (has_post_thumbnail($post_id)) {
    $thumbnail_id = get_post_thumbnail_id($post_id);

    $result['featured_image'] = [
      'src'    => wp_get_attachment_image_src($thumbnail_id, 'medium')[0],
      'src_2x' => wp_get_attachment_image_src($thumbnail_id, 'medium_large')[0],
      'alt'    => get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true),
    ];
  }

  return $result;
}
add_filter('sage_rest_cpt_result', __NAMESPACE__ . '\\filter_api_result', 10, 2);

/**
 * Return only upcoming events in the response for The Event Calendar.
 *
 * @param array $args
 * @param array $params
 *
 * @link https://theeventscalendar.com/knowledgebase/custom-event-queries/
 * @link https://tommcfarlin.com/querying-event-post-types/
 */
add_filter('sage_rest_api_query_args', function ($args, $params) {
  $args['eventDisplay'] = 'list';
  $args['start_date'] = '2015-01-01'; // arbitrary date—will switch with a param

  return $args;
}, 10, 2);

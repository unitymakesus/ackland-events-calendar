<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Simple_Google_Rating_Shortcodes Class
 *
 * @class Simple_Google_Rating_Shortcodes
 * @version	1.0.0
 * @since 1.0.0
 * @package	RTP_Dir
 * @author Unity
 */
class Simple_Google_Rating_Shortcodes {
	/**
	 * RTP_Dir_Admin The single instance of Simple_Google_Rating_Shortcodes.
	 * @var 	  object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The array of templates that this plugin tracks.
   * @var     array
   * @access  protected
   * @since   1.0.0
	 */
	protected $templates;

	/**
	 * Main Simple_Google_Rating_Shortcodes Instance
	 *
	 * Ensures only one instance of Simple_Google_Rating_Shortcodes is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Main Simple_Google_Rating_Shortcodes instance
	 */
	public static function instance () {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	}

 	/**
 	 * Constructor function to set filters and admin functions
 	 * @access  public
 	 * @since   1.0.0
 	 */
 	public function __construct () {
    add_shortcode('google_rating', array($this, 'rating_shortcode'));
  }

  public function rating_shortcode() {
    $settings = Simple_Google_Rating()->settings->get_settings();
    $data = $this->get_place_data();
    if (!property_exists($data, 'error_message')) {
      $name = $data->result->name;
      $rating = $data->result->rating;
      ob_start();
      ?>
        <div class="simple-google-rating-badge">
          <a href="https://search.google.com/local/reviews?placeid=<?php echo $settings['place-id']; ?>" target="_blank" rel="noopener">
            <div class="google-g">
              <?php echo file_get_contents(__DIR__ . '/../images/g.svg'); ?>
            </div>
            <div class="simple-google-rating" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
              <div><?php echo __( 'Google Rating', 'simple-google-rating' ); ?></div>
              <span class="simple-google-rating-rating" itemprop="ratingValue"><?php echo $rating; ?></span>
              <span class="simple-google-rating-stars"><?php echo $this->get_stars($rating); ?></span>
              <meta itemprop="bestRating" content="5"/>
            </div>
          </a>
        </div>
      <?php
      return ob_get_clean();
    }
  }

  public function get_place_data() {
    $settings = Simple_Google_Rating()->settings->get_settings();

    if ( false === ( $result = get_transient( 'sgr_place_rating_' . $settings['place-id'] ) ) ) {
      $url = "https://maps.googleapis.com/maps/api/place/details/";
      $params = array(
        'key' => $settings['api-key'],
        'placeid' => $settings['place-id'],
        'fields' => 'name,rating,url'
      );

      $request_uri = $url . 'json?' . http_build_query($params, '', '&');

      $json = wp_remote_get($request_uri);

      $result = $json['body'];

      set_transient( 'sgr_place_rating_' . $settings['place-id'], $result, 12 * HOUR_IN_SECONDS );
    }

    $data = json_decode($result);

    return $data;
  }

  public function get_stars($rating) {
    ob_start();

    foreach (array(1,2,3,4,5) as $val) {
      $score = $rating - $val;
      if ($score >= 0) {
        ?>
        <span class="wp-star-full">
          <?php echo file_get_contents(__DIR__ . '/../images/star-full.svg'); ?>
        </span>
        <?php
      } else if ($score > -1 && $score < 0) {
        ?>
        <span class="wp-star-half">
          <?php echo file_get_contents(__DIR__ . '/../images/star-half.svg'); ?>
        </span>
        <?php
      } else {
        ?>
        <span class="wp-star-empty">
          <?php echo file_get_contents(__DIR__ . '/../images/star-empty.svg'); ?>
        </span>
        <?php
      }
    }

    return ob_get_clean();
  }

}

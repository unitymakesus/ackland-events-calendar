<?php
/**
 * Shortcode Tribe_Events.
 *
 * @package Tribe\Events\Pro\Views\V2\Shortcodes
 * @since   4.7.5
 */
namespace Tribe\Events\Pro\Views\V2\Shortcodes;

use Tribe__Utils__Array as Arr;
use Tribe\Events\Views\V2\View;
use Tribe\Events\Views\V2\Assets;

/**
 * Class for Shortcode Tribe_Events.
 * @since   4.7.5
 * @package Tribe\Events\Pro\Views\V2\Shortcodes
 */
class Tribe_Events extends Shortcode_Abstract {
	/**
	 * {@inheritDoc}
	 */
	protected $slug = 'tribe_events';

	/**
	 * {@inheritDoc}
	 */
	protected $default_arguments = [
		'view'          => null,

		// Legacy Params, registered for compatibility
		'date'          => null,
		'tribe-bar'     => true,
		'category'      => null,
		'cat'           => null,
		'featured'      => false,
		'main-calendar' => false,
	];

	/**
	 * {@inheritDoc}
	 */
	protected $arguments_validate_map = [
		'tribe-bar'     => 'tribe_is_truthy',
		'featured'      => 'tribe_is_truthy',
		'main-calendar' => 'tribe_is_truthy',
	];

	/**
	 * Toggles the filtering of URLs to match the place where.
	 * We tend to hook into P15 to allow other things to happen before shortcode.
	 *
	 * @since  4.7.5
	 *
	 * @param  bool   $toggle  Whether to turn the hooks on or off.
	 *
	 * @return void
	 */
	protected function toggle_view_hooks( bool $toggle ) {
		if ( $toggle ) {
			add_filter( 'tribe_events_views_v2_view_url', [ $this, 'filter_view_url' ], 15, 3 );
		} else {
			remove_filter( 'tribe_events_views_v2_view_url', [ $this, 'filter_view_url' ], 15 );
		}
	}

	/**
	 * Verifies if in this Shortcode we should allow view url managemet.
	 *
	 * @since  4.7.5
	 *
	 * @return bool
	 */
	public function should_manage_url() {
		// Defaults to true due to old behaviors on Views V1
		$should_manage_url = $this->get_argument( 'should_manage_url', true );

		$disallowed_locations = [
			'widget_text_content',
		];

		/**
		 * Allows filtering of the disallowed locations for URL management.
		 *
		 * @since  4.7.5
		 *
		 * @param  mixed  $disallowed_locations Which filters we dont allow URL management.
		 * @param  static $instance             Which instance of shortcode we are dealing with.
		 */
		$disallowed_locations = apply_filters( 'tribe_events_pro_shortcode_tribe_events_manage_url_disallowed_locations', $disallowed_locations, $this );

		// Block certain locations
		foreach ( $disallowed_locations as $location ) {
			// If any we are in any of the disallowed locations
			if ( doing_filter( $location ) ) {
				$should_manage_url = false;
			}
		}

		/**
		 * Allows filtering if a shortcode URL management is active.
		 *
		 * @since  4.7.5
		 *
		 * @param  mixed  $should_manage_url Should we manage the URL for this views shortcode instance.
		 * @param  static $instance          Which instance of shortcode we are dealing with.
		 */
		$should_manage_url = apply_filters( 'tribe_events_pro_shortcode_tribe_events_should_manage_url', $should_manage_url, $this );

		return $should_manage_url;
	}

	/**
	 * Changes the URL to match the Shortcode if needed.
	 *
	 * @todo  actually do the URL management.
	 *
	 * @since 4.7.5
	 *
	 * @param  string          $url       Current URL for this view.
	 * @param  bool            $canonical Whether this url is canonical.
	 * @param  View_Interface  $view      Which view we are dealign with.
	 *
	 * @return string
	 */
	public function filter_view_url( $url, $canonical, $view ) {
		// Dont touch the URL in case we are not managing it.
		if ( ! $this->should_manage_url() ) {
			return $url;
		}

		return $url;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_html() {
		$context   = tribe_context();
		$view_slug = $this->get_argument( 'view', $context->get( 'view' ) );

		// Make sure to enqueue assets
		tribe_asset_enqueue_group( Assets::$group_key );

		// Toggle the shortcode required modifications
		$this->toggle_view_hooks( true );

		/**
		 * @todo modify the context based on arguments passed to the method.
		 */
		// Setup the view instance.
		$view = View::make( $view_slug, $context );

		// Setup wether this view should manage url or not.
		$view->get_template()->set( 'should_manage_url', $this->should_manage_url() );

		$html = $view->get_html();

		// Toggle the shortcode required modifications
		$this->toggle_view_hooks( false );

		return $html;
	}
}

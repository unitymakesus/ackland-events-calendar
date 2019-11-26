<?php
/**
 * Handles hooking all the actions and filters used by the module.
 *
 * To remove a filter:
 * remove_filter( 'some_filter', [ tribe( Tribe\Events\Pro\Views\V2\Hooks::class ), 'some_filtering_method' ] );
 * remove_filter( 'some_filter', [ tribe( 'pro.views.v2.hooks' ), 'some_filtering_method' ] );
 *
 * To remove an action:
 * remove_action( 'some_action', [ tribe( Tribe\Events\Pro\Views\V2\Hooks::class ), 'some_method' ] );
 * remove_action( 'some_action', [ tribe( 'pro.views.v2.hooks' ), 'some_method' ] );
 *
 * @since 4.7.5
 *
 * @package Tribe\Events\Pro\Views\V2
 */

namespace Tribe\Events\Pro\Views\V2;

use Tribe\Events\Pro\Views\V2\Views\All_View;
use Tribe\Events\Pro\Views\V2\Views\Photo_View;
use Tribe\Events\Pro\Views\V2\Views\Week_View;
use Tribe\Events\Pro\Views\V2\Views\Map_View;
use Tribe\Events\Pro\Views\V2\Views\Partials\Day_Event_Recurring_Icon;
use Tribe\Events\Pro\Views\V2\Views\Partials\Hide_Recurring_Events_Toggle;
use Tribe\Events\Pro\Views\V2\Views\Partials\List_Event_Recurring_Icon;
use Tribe\Events\Pro\Views\V2\Views\Partials\Location_Search_Field;
use Tribe\Events\Pro\Views\V2\Views\Partials\Month_Calendar_Event_Recurring_Icon;
use Tribe\Events\Pro\Views\V2\Views\Partials\Month_Mobile_Event_Recurring_Icon;
use Tribe\Events\Views\V2\View_Interface;
use Tribe__Context as Context;

/**
 * Class Hooks.
 *
 * @since 4.7.5
 *
 * @package Tribe\Events\Pro\Views\V2
 */
class Hooks extends \tad_DI52_ServiceProvider {
	/**
	 * Binds and sets up implementations.
	 *
	 * @since 4.7.5
	 */
	public function register() {
		$this->add_actions();
		$this->add_filters();
	}

	/**
	 * Adds the actions required by each Pro Views v2 component.
	 *
	 * @since 4.7.5
	 */
	protected function add_actions() {
		add_action( 'init', [ $this, 'action_disable_shortcode_v1' ], 15 );
		add_action( 'init', [ $this, 'action_add_shortcodes' ], 20 );
		add_action( 'tribe_template_after_include:events/components/top-bar/actions/content', [ $this, 'action_include_hide_recurring_events' ], 10, 3 );
		add_action( 'tribe_template_after_include:events/events-bar/search/keyword', [ $this, 'action_include_location_form_field' ], 10, 3 );
		add_action( 'tribe_template_after_include:events/day/event/date/meta', [ $this, 'action_include_day_event_recurring_icon' ], 10, 3 );
		add_action( 'tribe_template_after_include:events/list/event/date/meta', [ $this, 'action_include_list_event_recurring_icon' ], 10, 3 );
		add_action( 'tribe_template_after_include:events/month/calendar-body/day/calendar-events/calendar-event/date/meta', [ $this, 'action_include_month_calendar_event_recurring_icon' ], 10, 3 );
		add_action( 'tribe_template_after_include:events/month/mobile-events/mobile-day/mobile-event/date/meta', [ $this, 'action_include_month_mobile_event_recurring_icon' ], 10, 3 );
	}

	/**
	 * Filters the list of folders TEC will look up to find templates to add the ones defined by PRO.
	 *
	 * @since 4.7.5
	 *
	 * @param array $folders The current list of folders that will be searched template files.
	 *
	 * @return array The filtered list of folders that will be searched for the templates.
	 */
	public function filter_template_path_list( array $folders = [] ) {
		$folders[] = [
			'id'       => 'events-pro',
			'priority' => 25,
			'path'     => \Tribe__Events__Pro__Main::instance()->pluginPath . 'src/views/v2',
		];

		return $folders;
	}

	/**
	 * Adds the filters required by each Pro Views v2 component.
	 *
	 * @since 4.7.5
	 */
	protected function add_filters() {
		add_filter( 'tribe_events_views', [ $this, 'filter_events_views' ] );
		add_filter( 'tribe_template_path_list', [ $this, 'filter_template_path_list' ] );
		add_filter( 'tribe_events_views_v2_view_repository_args', [
			$this,
			'filter_events_views_v2_view_repository_args',
		], 10, 2 );
		add_filter( 'tribe_events_views_v2_view_template_vars', [
			$this,
			'filter_events_views_v2_view_template_vars',
		], 10, 2 );
	}

	/**
	 * Filters the available Views to add the ones implemented in PRO.
	 *
	 * @since 4.7.5
	 *
	 * @param array $views An array of available Views.
	 *
	 * @return array The array of available views, including the PRO ones.
	 */
	public function filter_events_views( array $views = [] ) {
		$views['all'] = All_View::class;
		$views['photo'] = Photo_View::class;
		$views['week'] = Week_View::class;
		$views['map'] = Map_View::class;

		return $views;
	}

	/**
	 * Fires to include the hide recurring template on the end of the actions of the top-bar.
	 *
	 * @since 4.7.5
	 *
	 * @param string $file      Complete path to include the PHP File
	 * @param array  $name      Template name
	 * @param self   $template  Current instance of the Tribe__Template
	 */
	public function action_include_hide_recurring_events( $file, $name, $template ) {
		$this->container->make( Hide_Recurring_Events_Toggle::class )->render( $template );
	}

	/**
	 * Fires to include the location form field after the keyword form field of the events bar.
	 *
	 * @since 4.7.5
	 *
	 * @param string $file      Complete path to include the PHP File
	 * @param array  $name      Template name
	 * @param self   $template  Current instance of the Tribe__Template
	 */
	public function action_include_location_form_field( $file, $name, $template ) {
		$this->container->make( Location_Search_Field::class )->render( $template );
	}

	/**
	 * Fires to include the recurring icon on the day view event.
	 *
	 * @since 4.7.8
	 *
	 * @param string $file      Complete path to include the PHP File
	 * @param array  $name      Template name
	 * @param self   $template  Current instance of the Tribe__Template
	 */
	public function action_include_day_event_recurring_icon( $file, $name, $template ) {
		$this->container->make( Day_Event_Recurring_Icon::class )->render( $template );
	}

	/**
	 * Fires to include the recurring icon on the list view event.
	 *
	 * @since 4.7.8
	 *
	 * @param string $file      Complete path to include the PHP File
	 * @param array  $name      Template name
	 * @param self   $template  Current instance of the Tribe__Template
	 */
	public function action_include_list_event_recurring_icon( $file, $name, $template ) {
		$this->container->make( List_Event_Recurring_Icon::class )->render( $template );
	}

	/**
	 * Fires to include the recurring icon on the month view calendar event.
	 *
	 * @since 4.7.8
	 *
	 * @param string $file      Complete path to include the PHP File
	 * @param array  $name      Template name
	 * @param self   $template  Current instance of the Tribe__Template
	 */
	public function action_include_month_calendar_event_recurring_icon( $file, $name, $template ) {
		$this->container->make( Month_Calendar_Event_Recurring_Icon::class )->render( $template );
	}

	/**
	 * Fires to include the recurring icon on the month view mobile event.
	 *
	 * @since 4.7.8
	 *
	 * @param string $file      Complete path to include the PHP File
	 * @param array  $name      Template name
	 * @param self   $template  Current instance of the Tribe__Template
	 */
	public function action_include_month_mobile_event_recurring_icon( $file, $name, $template ) {
		$this->container->make( Month_Mobile_Event_Recurring_Icon::class )->render( $template );
	}

	/**
	 * Fires to disable V1 of shortcodes, normally they would be registered on `init@P10`
	 * so we will trigger this on `init@P15`.
	 *
	 * It's important to leave gaps on priority for better injection.
	 *
	 * @since 4.7.5
	 */
	public function action_disable_shortcode_v1() {
		$this->container->make( Shortcodes\Manager::class )->disable_v1();
	}

	/**
	 * Adds the new shortcodes, this normally will trigger on `init@P20` due to how we the
	 * v1 is added on `init@P10` and we remove them on `init@P15`.
	 *
	 * It's important to leave gaps on priority for better injection.
	 *
	 * @since 4.7.5
	 */
	public function action_add_shortcodes() {
		$this->container->make( Shortcodes\Manager::class )->add_shortcodes();
	}

	/**
	 * Filters the View repository args to parse and apply PRO specific View filters.
	 *
	 * @since 4.7.5
	 *
	 * @param array        $repository_args The current repository args.
	 * @param Context|null $context         An instance of the context the View is using or `null` to use the
	 *                                      global Context.
	 *
	 * @return array The filtered repository args.
	 */
	public function filter_events_views_v2_view_repository_args( array $repository_args = [], Context $context = null ) {
		/** @var View_Filters $view_filters */
		$view_filters = $this->container->make( View_Filters::class );

		return $view_filters->filter_repository_args( $repository_args, $context );
	}

	/**
	 * Filters the View template variables before the HTML is generated to add the ones related to this plugin filters.
	 *
	 * @since 4.7.5
	 *
	 * @param array          $template_vars The View template variables.
	 * @param View_Interface $view The current View instance.
	 */
	public function filter_events_views_v2_view_template_vars( array $template_vars, View_Interface $view ) {
		/** @var View_Filters $view_filters */
		$view_filters = $this->container->make( View_Filters::class );

		return $view_filters->filter_template_vars( $template_vars, $view->get_context() );
	}
}

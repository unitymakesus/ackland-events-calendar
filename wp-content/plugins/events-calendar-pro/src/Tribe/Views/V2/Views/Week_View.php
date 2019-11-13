<?php
/**
 * Renders the week view
 *
 * @since   4.7.5
 * @package Tribe\Events\PRO\Views\V2\Views
 */

namespace Tribe\Events\Pro\Views\V2\Views;

use DateInterval;
use Tribe\Events\Views\V2\Views\By_Day_View;
use Tribe__Context as Context;
use Tribe__Date_Utils as Dates;
use Tribe__Events__Timezones as Timezones;

/**
 * Class Week_View
 *
 * @since   4.7.5
 *
 * @package Tribe\Events\PRO\Views\V2\Views
 */
class Week_View extends By_Day_View {

	/**
	 * Slug for this view
	 *
	 * @since 4.7.5
	 *
	 * @var string
	 */
	protected $slug = 'week';

	/**
	 * Visibility for this view.
	 *
	 * @since 4.7.5
	 *
	 * @var bool
	 */
	protected $publicly_visible = true;

	/**
	 * {@inheritDoc}
	 */
	protected function setup_repository_args( Context $context = null ) {
		$context = null !== $context ? $context : $this->context;

		/*
		 * We'll not fetch the week events in one single sweep, but day-by-day.
		 * Here we just set up some common arguments for the repository that will be common to any day.
		 */

		$args = parent::setup_repository_args( $context );

		$date = $context->get( 'event_date', 'now' );

		$this->user_date = Dates::build_date_object( $date )->format( 'Y-m-d' );

		return $args;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function setup_template_vars() {
		$template_vars = parent::setup_template_vars();

		$user_date = $this->context->get( 'event_date', 'now' );

		list( $week_start, $week_end ) = $this->calculate_grid_start_end( $user_date );

		$stack_toggle_threshold = $this->get_stack_toggle_threshold();
		$stack                  = $this->get_stack( $user_date, $stack_toggle_threshold );
		// Prepare a modified version of the stack made of numeric arrays, not associative ones, to allow `list` calls.
		$list_ready_stack = array_combine( array_keys( $stack ), array_map( static function ( array $day_stack ) {
			return array_values( $day_stack );
		}, $stack ) );

		$today                                      = $this->context->get( 'today' );

		$template_vars['today']                     = tribe_beginning_of_day( $today );
		$template_vars['today_date']                = Dates::build_date_object( $today )->format( 'Y-m-d' );
		$template_vars['week_start']                = $week_start;
		$template_vars['week_end']                  = $week_end;
		$template_vars['week_start_date']           = $week_start->format( Dates::DBDATEFORMAT );
		$template_vars['week_end_date']             = $week_end->format( Dates::DBDATEFORMAT );
		$template_vars['days_of_week']              = $this->get_header_grid( $week_start, $week_end );
		$date_format                                = tribe_get_option( 'dateWithoutYearFormat', 'F Y' );
		$template_vars['formatted_week_start_date'] = $week_start->format( $date_format );
		$template_vars['formatted_week_end_date']   = $week_end->format( $date_format );
		$template_vars['mobile_days']               = $this->get_mobile_days( $user_date );
		$template_vars['days']                      = $this->get_grid_days( $user_date );
		$template_vars['multiday_events']           = $list_ready_stack;
		$template_vars['events']                    = $this->get_events( $user_date );

		$template_vars['multiday_min_toggle']     = $stack_toggle_threshold;

		// If any of the days in the stack has more events than the threshold, then show the toggle.
		$highest_stack = max( ... array_map( 'count', array_column( $stack, 'events' ) ) );
		$show_stack_toggle = $highest_stack > $stack_toggle_threshold;
		$template_vars['multiday_display_toggle']  = $show_stack_toggle;
		$template_vars['multiday_toggle_controls'] = $show_stack_toggle
			? $this->build_stack_toggle_controls( $stack )
			: '';

		return $template_vars;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function calculate_grid_start_end( $date ) {
		$week_start = Dates::build_date_object( $date );
		$week_start->setTime( 0, 0, 0 );

		// Sunday is 0.
		$week_start_day = $this->context->get( 'start_of_week', 0 );
		$offset         = (int) $week_start->format( 'N' ) >= $week_start_day
			? $week_start_day
			: $week_start->format( 'N' ) - $week_start_day;

		$week_start->setISODate(
			(int) $week_start->format( 'o' ),
			(int) $week_start->format( 'W' ),
			$offset
		);

		$week_start->format( 'Y-m-d' );

		$week_end = clone $week_start;
		$week_end->add( new DateInterval( 'P6D' ) );
		$week_end_string = tribe_end_of_day( $week_end->format( 'Y-m-d' ) );
		$week_end        = Dates::build_date_object( $week_end_string );

		return [ $week_start, $week_end ];
	}

	/**
	 * Returns the Week events, formatted as required by the mobile events template.
	 *
	 * @since 4.7.7
	 *
	 * @param string|int|\DateTime $user_date The user date, it might have been set to the default value or be set
	 *                                        explicitly.
	 *
	 * @return array An array of days of the week in the shape `[ <Y-m-d> => [ ...<day_mobile_data> ] ]`.
	 */
	protected function get_mobile_days( $user_date ) {
		$mobile_days = [];

		$grid_days = parent::get_grid_days( $user_date );

		// Events should appear once in the mobile version of the view.
		$acc = [];
		foreach ( $grid_days as $day => &$the_day_event_ids ) {
			$the_day_event_ids = array_values( array_diff( $the_day_event_ids, $acc ) );
			$acc               = array_merge( $acc, $the_day_event_ids );
		}
		unset( $the_day_event_ids );

		foreach ( $grid_days as $date_string => $event_ids ) {
			$mobile_days[ $date_string ] = [
				'date'        => $date_string,
				'found_events' => count( $event_ids ),
				'event_times' => $this->parse_event_times( $event_ids),
			];
		}

		return $mobile_days;
	}

	/**
	 * Parses and returns the times of a list of events to group them by start time, rounded to the half-hour.
	 *
	 * The half-hour rounding is as follows:
	 * - 0 to 14' is rounded to the start of the hour.
	 * - 15' to 45' is rounded to the half hour.
	 * - 46' to end of hour is rounded to the end of the hour.
	 *
	 * @since 4.7.7
	 *
	 * @param array $event_ids An array of event post IDs happening on the day.
	 *
	 * @return array The event post IDs, grouped by their start time, rounded by the criteria outlined above.
	 */
	protected function parse_event_times( array $event_ids ) {
		/*
		 * In this method the events are already ordered with respect to their start dates and timezone settings.
		 * Here we just group them by start time.
		 * The start time has, but, to take the timezone settings into account.
		 */
		$use_site_timezone = Timezones::is_mode( 'site' );
		$site_timezone     = Timezones::build_timezone_object();
		$time_format       = get_option( 'time_format', Dates::TIMEFORMAT );
		$event_times       = [];

		foreach ( $event_ids as $event_id ) {
			$event = tribe_get_event( $event_id );

			/** @var \DateTimeImmutable $start */
			$start = $use_site_timezone ? $event->dates->start->setTimezone( $site_timezone ) : $event->dates->start;

			$time = date_i18n( $time_format, $start->getTimestamp() );
			// ISO 8601 format, e.g. `2019-01-01T00:00:00+00:00`.
			$datetime = $start->format( 'c' );

			if ( ! isset( $event_times[ $time ] ) ) {
				$event_times[ $time ] = [ 'time' => $time, 'datetime' => $datetime, 'events' => [] ];
			}

			$event_times[ $time ]['events'][] = $event;
		}

		return $event_times;
	}

	/**
	 * Returns the events for each day of the week.
	 *
	 * This method overrides the base one to specialize the format of the returned events to the one required by the
	 * Week View.
	 *
	 * @since 4.7.8
	 *
	 * @param mixed|null $date The date, located anywhere in the week, that should be used to get the days.
	 * @param bool $force Whether to force a new fetch and ignore the cached events, or not.
	 *
	 * @return array An array of the Week events, by day, in the shape `[ <Y-m-d> => [ ...<events>] ]`.
	 */
	public function get_grid_days( $date = null, $force = false ) {
		$grid_days = [];

		$raw_grid_days = parent::get_grid_days( $date, $force );

		foreach ( $raw_grid_days as $date_string => $event_ids ) {
			$day_date = Dates::build_date_object($date_string);

			$grid_days[ $date_string ] = [
				'datetime' => $date_string,
				'weekday'  => date_i18n( 'D', $day_date->getTimestamp() ),
				'daynum' => $day_date->format('d'),
			];
		}

		return $grid_days;
	}

	/**
	 * Returns an array of the Week View stack events, or events otherwise belonging in the stack, by day.
	 *
	 * @since 4.7.8
	 *
	 * @param null|string|\DateTime $user_date The date to fetch the events for. The date can be in any position in the
	 *                                         week: the start and end date will be calculated to always return a week
	 *                                         long array.
	 * @param int $multiday_toggle How many events to show before "folding" the stack and showing a "Show more" control.
	 *
	 * @return array Each day stack, in the shape `[ <Y-m-> => [ ...$stack ] ]`.
	 */
	protected function get_stack( $user_date = null, $multiday_toggle = null ) {
		$grid_days = parent::get_grid_days( $user_date );

		$stack = $this->stack->build_from_events( $grid_days );
		$week_stack = [];

		foreach ( $stack as $day_date => $elements ) {
			$date_object = Dates::build_date_object( $day_date );
			$counter     = 0;

			foreach ( $elements as &$element ) {
				$counter ++;

				if ( ! is_numeric( $element ) ) {
					// It's a spacer, let it be.
					continue;
				}

				$element = tribe_get_event( $element, OBJECT, $date_object->format( 'Y-m-d' ) );
				// Set the `should_display` flag property depending on the multi-day toggle settings.
				$element->should_display = $counter <= $multiday_toggle;
			}

			$more_events = 0;

			if ( null !== $multiday_toggle ) {
				// There are `n` valid events after the toggle.
				$more_events = count( array_filter( array_slice( $elements, $multiday_toggle ), static function ( $el ) {
					return $el instanceof \WP_Post;
				} ) );
			}

			$week_stack[ $day_date ] = [ 'events' => $elements, 'more_events' => $more_events ];
		}

		return $week_stack;
	}

	/**
	 * Returns an array of the Week View events not belonging to the stack (at the top of the view), by day.
	 *
	 * @since 4.7.8
	 *
	 * @param null|string|\DateTime $user_date The date to fetch the events for. The date can be in any position in the
	 *                                         week: the start and end date will be calculated to always return a week
	 *                                         long array.
	 *
	 * @return array Each day events, in the shape `[ <Y-m-> => [ ...$events ] ]`.
	 */
	protected function get_events( $user_date = null ) {
		$days = parent::get_grid_days( $user_date );

		// Filter out multi-day and all-day events and cast each event to an decorated WP_Post event object.
		foreach ( $days as $day => &$day_events ) {
			$day_events = array_reduce( $day_events, function ( array $day_events, $event_id ) {
				$event = tribe_get_event( $event_id );

				if ( ! $event instanceof \WP_Post || $event->multiday || $event->all_day ) {
					return $day_events;
				}

				$prev_event = end( $day_events );
				$event->classes = $this->get_event_classes( $event, $prev_event ?: null );

				$day_events[] = $event;

				return $day_events;
			}, [] );
		}

		return $days;
	}

	/**
	 * Returns the Week View header grid, the one required to render the list of days at the top of the view.
	 *
	 * @since 4.7.8
	 *
	 * @param \DateTime $week_start The week start date object.
	 * @param \DateTime $week_end The week end date object.
	 *
	 * @return array
	 */
	protected function get_header_grid( \DateTime $week_start, \DateTime $week_end ) {
		$grid = [];

		$one_day = new \DateInterval( 'P1D' );
		try {
			$interval = new \DatePeriod( $week_start, $one_day, $week_end );
		} catch ( \Exception $e ) {
			// This should really not happen as we control the input of \DatePeriod, yet let's handle the case.
			return [];
		}

		/** @var \DateTime $day */
		foreach ( $interval as $day ) {
			$day_y_m_d          = $day->format( 'Y-m-d' );

			$grid[ $day_y_m_d ] = [
				'full_date' => $day->format( tribe_get_option( 'date_with_year', Dates::DATEONLYFORMAT ) ),
				'datetime'  => $day_y_m_d,
				'weekday'   => date_i18n( 'D', $day->getTimestamp() + $day->getOffset() ),
				'daynum'    => $day->format( 'd' ),
			];
		}

		return $grid;
	}

	/**
	 * Returns the filtered value for the threshold that will fold, in Week View, the stack and show a "Show more".
	 * control
	 *
	 * @since 4.7.8
	 *
	 * @return int The filtered value for the threshold that will fold, in Week View, the stack and show a "Show more".
	 */
	protected function get_stack_toggle_threshold() {
		/**
		 * Filters the threshold that will fold, in Week View, the stack and show a "Show more" control.
		 *
		 * E.g. returning `2` here will cause 2 events to show and a "Show more" link to expand any stack with more than
		 * 2 events in it.
		 *
		 * @since 4.7.8
		 *
		 * @param int $multiday_min_toggle The threshold that will fold, in Week View, the stack and show a
		 *                                 "Show more" control.
		 */
		$multiday_min_toggle = apply_filters( 'tribe_events_views_v2_week_multiday_toggle', 3 );

		return $multiday_min_toggle;
	}

	/**
	 * Builds the space-separated list of entries for the multi-day toggle `aria-controls`.
	 *
	 * @since 4.7.8
	 *
	 * @param array $stack The view stack, in the shape `[ <Y-m-d> => [ ...$events ] ]`.
	 *
	 * @return string A space-separated list of `aria-controls` entries.
	 */
	protected function build_stack_toggle_controls( array $stack ) {
		$days = array_filter( $stack, static function ( array $day_stack ) {
			return $day_stack['more_events'] > 0;
		} );

		$prefix = static function ( $index ) {
			return 'tribe-events-pro-multiday-toggle-day-' . $index;
		};

		return implode( ' ', array_map( $prefix, array_keys( $days ) ) );
	}

	/**
	 * Returns an array of the CSS classes that should be applied to the event to render correctly in the Week View.
	 *
	 * @since 4.7.8
	 *
	 * @param \WP_Post $event The event to get the classes for.
	 *
	 * @return array An array of classes that should be applied to the event to render correctly in the Week View.
	 */
	protected function get_event_classes( \WP_Post $event, \WP_Post $prev = null ) {
		$event_classes = array_filter( [
			'vertical_position' => $this->get_event_vertical_position_class( $event ),
			'duration'          => $this->get_event_duration_class( $event ),
			'sequence'          => $this->get_event_sequence_class( $event, $prev ),
		] );

		/**
		 * Filters the classes that will be assigned to the event, in the `classes` array property, in the context
		 * of the Week View.
		 *
		 * Note this filter will only be called on events part of the day stack, the one appearing below the multi-day,
		 * all-day stack.
		 *
		 * @since 4.7.8
		 *
		 * @param array $event_classes         An array of all the classes that will be assinged to this event when
		 *                                     rendering it in the context of the week view day stack.
		 * @param \WP_Post      $event         The event the classes are being calculated for.
		 * @param \WP_Post|null $prev          The event preceding this one in the day stack or `null` if this is the
		 *                                     first event in the day stack.
		 */
		$event_classes = apply_filters( 'tribe_events_views_v2_week_event_classes', $event_classes, $event, $prev );

		return $event_classes;
	}

	/**
	 * Calculates and returns, if any, the vertical position CSS class that should be applied to the event.
	 *
	 * @since 4.7.8
	 *
	 * @param \WP_Post $event The event to calculate the vertical position class for.
	 *
	 * @return string The full vertical position CSS class for the event, if required, else an empty string.
	 */
	protected function get_event_vertical_position_class( \WP_Post $event ) {
		$start_hour    = (int) $event->dates->start->format( 'H' );
		$start_minutes = (int) $event->dates->start->format( 'i' );

		if ( 0 === $start_hour && $start_minutes <= 15 ) {
			// Starts between `00:00` and `00:15`.
			return '';
		}

		// Round to the hour.
		$pos = $start_hour;
		if ( $start_minutes >= 45 ) {
			// Round up to the next hour.
			$pos = $start_hour + 1;
		} else {
			// Round to the half hour if it starts after `:15`.
			$pos .= $start_minutes <= 15 ? '' : '-5';
		}

		return 'tribe-events-pro-week-grid__event--t-' . $pos;
	}

	/**
	 * Returns the event CSS duration class.
	 *
	 * @since 4.7.8
	 *
	 * @param \WP_Post $event The event to calculate the CSS duration class for.
	 *
	 * @return string The event CSS duration class, if required, depending on the event duration, or an empty string.
	 */
	protected function get_event_duration_class( \WP_Post $event ) {
		$hours = (int) floor( $event->duration / 3600 );

		if ( $hours < 1 ) {
			// Do not add any class if the event lasts less than 1 hour.
			return '';
		}

		$decimal_minutes = ( $event->duration % 3600 ) / 3600;

		$duration_string = $hours;

		if ( $decimal_minutes > .7 ) {
			$duration_string = $hours + 1;
		} elseif ( $decimal_minutes > .3 ) {
			$duration_string .= '-5';
		}

		return 'tribe-events-pro-week-grid__event--h-' . $duration_string;
	}

	/**
	 * Returns the event CSS sequence class, based on the event coming before it.
	 *
	 * @since 4.7.8
	 *
	 * @param \WP_Post      $event The event object to calculate the CSS class for.
	 * @param \WP_Post|null $prev The previous event object, if any.
	 *
	 * @return string The CSS class that should be added to the event for the sequence or an empty string.
	 */
	protected function get_event_sequence_class( \WP_Post $event, \WP_Post $prev = null ) {
		if ( null === $prev ) {
			return '';
		}

		/*
		 * Two events overlap if the start of the first is before the end of the second and if the start of the second
		 * is before the end of the first.
		 */
		$ends_after_prev_starts  = $prev->dates->start < $event->dates->end;
		$starts_before_prev_ends = $event->dates->start < $prev->dates->end;
		$overlap                 = $ends_after_prev_starts && $starts_before_prev_ends;

		if ( ! $overlap ) {
			return '';
		}

		$class_base = 'tribe-events-pro-week-grid__event--seq-';

		$sequence = isset( $prev->classes['sequence'] ) ?
			(int) str_replace( $class_base, '', $prev->classes['sequence'] ) + 1
			: 2;

		return $class_base . $sequence;
	}

	/**
	 * {@inheritDoc}
	 */
	public function prev_url( $canonical = false, array $passthru_vars = [] ) {
		// Setup the Default date for the Week view here.
		$default_date = 'today';
		$date         = $this->context->get( 'event_date', $default_date );
		$current_date = Dates::build_date_object( $date );
		list( $week_start ) = $this->calculate_grid_start_end( $current_date );

		$prev_date = clone $week_start;
		$prev_date->sub( new \DateInterval( 'P1W' ) );
		// Let's make sure to prevent users from paginating endlessly back when we know there are no more events.
		$earliest = tribe_get_option( 'earliest_date', $prev_date );
		if ( $week_start <= Dates::build_date_object( $earliest ) ) {
			// The earliest event happens on this week, stop.
			return $this->filter_prev_url( $canonical, '' );
		}

		$url = $this->removing_week_number_rule( function () use ( $prev_date, $canonical, $passthru_vars ) {
			return $this->build_url_for_date( $prev_date, $canonical, $passthru_vars );
		} );

		return $this->filter_prev_url( $canonical, $url );
	}

	/**
	 * {@inheritDoc}
	 */
	public function next_url( $canonical = false, array $passthru_vars = [] ) {
		// Setup the Default date for the Week view here.
		$default_date = 'today';
		$date         = $this->context->get( 'event_date', $default_date );
		$current_date = Dates::build_date_object( $date );
		list( $week_start ) = $this->calculate_grid_start_end( $current_date );

		$utc           = new \DateTimeZone( 'UTC' );
		$next_date     = clone $week_start;
		$next_date     = $next_date->add( new \DateInterval( 'P1W' ) );
		$next_date_utc = clone $next_date;
		$next_date_utc->setTimezone( $utc );
		// Let's make sure to prevent users from paginating endlessly forward when we know there are no more events.
		$latest_ids          = tribe_get_option( 'latest_date_markers', [] );
		$latest_end_date_utc = array_reduce( $latest_ids, static function ( \DateTime $latest_end, $event_id ) use ( $utc ) {
			$event_end_date_utc = get_post_meta( $event_id, '_EventEndDateUTC', true );
			$date               = Dates::build_date_object( $event_end_date_utc, $utc );

			return $date > $latest_end ? $date : $latest_end;
		}, $next_date_utc );

		if ( $next_date_utc >= $latest_end_date_utc ) {
			// Next week starts after the latest event finished.
			return $this->filter_prev_url( $canonical, '' );
		}

		$url = $this->removing_week_number_rule( function () use ( $next_date, $canonical, $passthru_vars ) {
			return $this->build_url_for_date( $next_date, $canonical, $passthru_vars );
		} );

		return $this->filter_next_url( $canonical, $url );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_url_date_format() {
		return 'Y-m-d';
	}

	/**
	 * Removes the week number rules from the list of rewrite rules we handle and calls a callable.
	 *
	 * We remove the week number rule as it is ambiguous in its resolution and we're not currently supporting it.
	 *
	 * @since 4.7.8
	 *
	 * @param \Closure $fn The closure to call removing the week number rewrite rules.
	 *
	 * @return mixed The return value of the closure.
	 */
	protected function removing_week_number_rule( \Closure $fn ) {
		$remove_week_number_rule = static function ( array $rules ) {
			unset(
				$rules['(?:events)/(?:week)/(\d{2})/?$'],
				$rules['(?:events)/(?:week)/(\d{2})/(?:featured)/?$']
			);

			return $rules;
		};

		add_filter( 'tribe_rewrite_handled_rewrite_rules', $remove_week_number_rule );
		$result = $fn();
		remove_filter( 'tribe_rewrite_handled_rewrite_rules', $remove_week_number_rule );

		return $result;
	}
}

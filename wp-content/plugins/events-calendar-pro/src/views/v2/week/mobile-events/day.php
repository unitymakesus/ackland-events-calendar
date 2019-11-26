<?php
/**
 * View: Week View Mobile Events Day
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week/mobile-events/day.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 *
 * @version 4.7.7
 *
 * @var string $day_date The day date, in the `Y-m-d` format.
 * @var array $day The data for the day.
 */

$hidden      = 'true';
$day_classes = [ 'tribe-events-pro-week-mobile-events__day' ];

if ( $day[ 'found_events' ] ) {
	$hidden        = 'false';
	$day_classes[] = 'tribe-events-pro-week-mobile-events__day--active';
}
?>

<div
	class="<?php echo esc_attr( implode( ' ', $day_classes ) ); ?>"
	id="tribe-events-pro-week-mobile-events-day-<?php echo esc_attr( $day_date ); ?>"
	aria-hidden="<?php echo esc_attr( $hidden ); ?>"
>

	<?php
	 foreach ( $day[ 'event_times' ] as $event_time ) {
		 $this->template(
			 'week/mobile-events/day/time-separator',
			 [ 'time' => $event_time['time'], 'datetime' => $event_time['datetime'] ]
		 );

		 foreach( $event_time[ 'events' ] as $event ) {
	 		$this->template( 'week/mobile-events/day/event', [ 'event' => $event ] );
	 	}
	 }
	?>

</div>

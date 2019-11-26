<?php
/**
 * View: Week View - Multiday Event
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week/grid-body/multiday-events-day/multiday-event.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.8
 *
 * @var WP_Post $event The current event post object.
 * @var string $week_start_date The week start date, in `Y-m-d` format.
 * @var string $today_date Today's date, in the `Y-m-d` format.
 *
 * @see tribe_get_event() for the additional properties added to the event post object.
 */

use Tribe__Date_Utils as Dates;

// Either it starts today or it starts before and this day is the first day of the week.
$should_display = $event->dates->start->format( 'Y-m-d' ) === $day
                  || ( ! $event->starts_this_week && $week_start_date === $day );

$classes = [ 'tribe-events-pro-week-grid__multiday-event' ];

if ( $event->featured ) {
	$classes[] = 'tribe-events-pro-week-grid__multiday-event--featured';
}

// An event is considered "past" when it ends before the start of today.
if ( $event->dates->end->format( 'Y-m-d' ) < $today_date ) {
	$classes[] = 'tribe-events-pro-week-grid__multiday-event--past';
}

if ( $should_display ) {
	$classes[] = 'tribe-events-pro-week-grid__multiday-event--width-' . $event->this_week_duration;

	if ( $event->starts_this_week ) {
		$classes[] = 'tribe-events-pro-week-grid__multiday-event--start';
	}

	if ( $event->ends_this_week ) {
		$classes[] = 'tribe-events-pro-week-grid__multiday-event--end';
	}
} else {
	$classes[] = 'tribe-events-pro-week-grid__multiday-event--hidden';
}
?>
<div class="tribe-events-pro-week-grid__multiday-event-wrapper">

	<article <?php tribe_classes( $classes ) ?> data-event-id="<?php echo esc_attr( $event->ID ); ?>">
		<time datetime="<?php echo esc_attr( $event->dates->start->format( Dates::DBDATEFORMAT ) ); ?>"
		      class="tribe-common-a11y-visual-hide">
			<?php echo esc_html( $event->plain_schedule_details->value() ); ?>
		</time>
		<a href="<?php echo esc_attr( $event->permalink ); ?>" class="tribe-events-pro-week-grid__multiday-event-inner">
			<?php if ( $event->featured ) : ?>
				<em
					class="tribe-events-pro-week-grid__multiday-event-featured-icon tribe-common-svgicon tribe-common-svgicon--featured"
					aria-label="<?php esc_attr_e( 'Featured', 'tribe-events-calendar-pro' ); ?>"
					title="<?php esc_attr_e( 'Featured', 'tribe-events-calendar-pro' ); ?>"
				>
				</em>
			<?php endif; ?>
			<h3 class="tribe-events-pro-week-grid__multiday-event-title tribe-common-h8 tribe-common-h--alt">
				<?php echo esc_html( get_the_title( $event->ID ) ); ?>
			</h3>
		</a>
	</article>

</div>

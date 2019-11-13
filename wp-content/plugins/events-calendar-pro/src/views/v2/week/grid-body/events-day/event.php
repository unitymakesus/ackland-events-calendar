<?php
/**
 * View: Week View - Event
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week/grid-body/events-day/event.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.8
 *
 * @var WP_Post $event The event post object with properties added by the `tribe_get_event` function.
 *
 * @see tribe_get_event() For the format of the event object.
 *
 */

$classes = [ 'tribe-events-pro-week-grid__event' ];

if ( ! empty( $event->featured ) ) {
	$classes[] = 'tribe-events-pro-week-grid__event--featured';
}

/*
 * Some CSS classes (i.e. vertical position, duration and sequence) have been calculated in the Week View.
 * Here we add them to the ones that should be applied to the event element.
 */
$classes = array_merge( $classes, array_values( $event->classes ) );

?>
<article <?php tribe_classes( $classes ) ?> data-event-id="<?php echo esc_attr( $event->ID ); ?>">
	<a
		href="<?php echo esc_url( $event->permalink ); ?>"
		class="tribe-events-pro-week-grid__event-link"
		data-js="tribe-events-tooltip"
		data-tooltip-content="#tribe-events-tooltip-content-<?php echo esc_attr( $event->ID ); ?>"
		aria-describedby="tribe-events-tooltip-content-<?php echo esc_attr( $event->ID ); ?>"
	>
		<div class="tribe-events-pro-week-grid__event-link-inner">

			<?php $this->template( 'week/grid-body/events-day/event/featured-image', [ 'event' => $event ] ); ?>
			<?php $this->template( 'week/grid-body/events-day/event/date', [ 'event' => $event ] ); ?>
			<?php $this->template( 'week/grid-body/events-day/event/title', [ 'event' => $event ] ); ?>

		</div>
	</a>
</article>

<?php $this->template( 'week/grid-body/events-day/event/tooltip', [ 'event' => $event ] ); ?>

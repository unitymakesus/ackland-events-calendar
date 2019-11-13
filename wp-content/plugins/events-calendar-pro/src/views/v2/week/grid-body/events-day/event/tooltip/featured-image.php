<?php
/**
 * View: Week View - Event Tooltip Featured Image
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/views/v2/week/grid-body/events-day/event/tooltip/featured-image.php
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

if ( ! $event->thumbnail->exists ) {
	return;
}

?>
<div class="tribe-events-pro-week-grid__event-tooltip-featured-image-wrapper">
	<a
		href="<?php echo esc_url( $event->permalink ); ?>"
		title="<?php echo esc_attr( get_the_title( $event ) ); ?>"
		rel="bookmark"
		class="tribe-events-pro-week-grid__event-tooltip-featured-image-link"
	>
		<div class="tribe-events-pro-week-grid__event-tooltip-featured-image tribe-common-c-image tribe-common-c-image--bg">
			<div
				class="tribe-common-c-image__bg"
				style="background-image: url('<?php echo esc_url( $event->thumbnail->full->url ); ?>');"
				role="img"
				aria-label="<?php echo esc_attr( get_the_title( $event ) ); ?>"
			>
			</div>
		</div>
	</a>
</div>

<?php
/**
 * View: Week View - Mobile Event Featured Image
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week/mobile-events/day/event/featured-image.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.8
 *
 * @var WP_Post $event The event post object, decorated with additional properties by the `tribe_get_event` function.
 *
 * @see tribe_get_event() for the additional properties added to the event post object.
 */

if ( ! $event->thumbnail->exists ) {
	return;
}

?>
<div class="tribe-events-pro-week-mobile-events__event-featured-image-wrapper tribe-common-g-col">
	<a
		href="<?php echo esc_url( $event->permalink ); ?>"
		title="<?php echo esc_attr( get_the_title( $event->ID ) ); ?>"
		rel="bookmark"
		class="tribe-events-pro-week-mobile-events__event-featured-image-link"
	>
		<div class="tribe-events-pro-week-mobile-events__event-featured-image tribe-common-c-image tribe-common-c-image--bg">
			<div
				class="tribe-common-c-image__bg"
				style="background-image: url('<?php echo esc_attr( $event->thumbnail->full->url ); ?>');"
				role="img"
				aria-label="<?php echo esc_attr( get_the_title( $event ) ); ?>"
			>
			</div>
		</div>
	</a>
</div>

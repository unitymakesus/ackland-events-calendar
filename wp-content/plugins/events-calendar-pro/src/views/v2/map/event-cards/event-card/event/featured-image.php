<?php
/**
 * View: Map View - Single Event Featured Image
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/event-cards/event-card/event/featured-image.php
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
<div class="tribe-events-pro-map__event-featured-image-wrapper tribe-common-g-col">
	<div class="tribe-events-pro-map__event-featured-image tribe-common-c-image tribe-common-c-image--bg">
		<div
			class="tribe-common-c-image__bg"
			style="background-image: url('<?php echo esc_url( $event->thumbnail->full->url ); ?>');"
			role="img"
			aria-label="<?php echo esc_attr( get_the_title( $event ) ); ?>"
		>
		</div>
	</div>
</div>

<?php
/**
 * View: Photo View - Single Event Featured Image
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/photo/event/featured-image.php
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

$placeholder = trailingslashit( Tribe__Events__Pro__Main::instance()->pluginUrl ) . 'src/resources/images/tribe-event-placeholder-image.svg';
$image_url = $event->thumbnail->exists ? $event->thumbnail->full->url : $placeholder;

?>
<div class="tribe-events-pro-photo__event-featured-image-wrapper">
	<a
		href="<?php echo esc_url( $event->permalink ); ?>"
		title="<?php echo esc_attr( get_the_title( $event ) ); ?>"
		rel="bookmark"
		class="tribe-events-pro-photo__event-featured-image-link"
	>
		<div class="tribe-events-pro-photo__event-featured-image tribe-common-c-image tribe-common-c-image--bg">
			<div
				class="tribe-common-c-image__bg"
				style="background-image: url('<?php echo esc_url( $image_url ); ?>');"
				role="img"
				aria-label="<?php echo esc_attr( get_the_title( $event ) ); ?>"
			>
			</div>
		</div>
	</a>
</div>

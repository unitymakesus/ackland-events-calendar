<?php
/**
 * View: Photo Event
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/photo/event.php
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

$classes = [ 'tribe-common-g-col', 'tribe-events-pro-photo__event' ];

if ( $event->featured ) {
	$classes[] = 'tribe-events-pro-photo__event--featured';
}

?>
<article <?php tribe_classes( $classes ) ?>>

	<?php $this->template( 'photo/event/featured-image', [ 'event' => $event ] ); ?>

	<div class="tribe-events-pro-photo__event-details-wrapper">
		<?php $this->template( 'photo/event/date-tag', [ 'event' => $event ] ); ?>
		<div class="tribe-events-pro-photo__event-details">
			<?php $this->template( 'photo/event/date-time', [ 'event' => $event ] ); ?>
			<?php $this->template( 'photo/event/title', [ 'event' => $event ] ); ?>
			<?php $this->template( 'photo/event/cost', [ 'event' => $event ] ); ?>
		</div>
	</div>

</article>
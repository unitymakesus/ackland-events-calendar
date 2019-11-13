<?php
/**
 * View: Map View - Event Card
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/event-cards/event-card.php
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
$wrapper_classes = [ 'tribe-events-pro-map__event-card-wrapper' ];
$wrapper_classes['tribe-events-pro-map__event-card-wrapper--featured'] = $event->featured;

$classes = [ 'tribe-common-g-row', 'tribe-events-pro-map__event-row', 'tribe-events-pro-map__event-row--gutters' ];

$data_src_attr = '';

if ( empty( $map_provider->is_premium ) && $event->venues->count() ) {
	$iframe_url = add_query_arg( [
		'key' => $map_provider->api_key,
		'q'   => urlencode( $event->venues->first()->geolocation->address ),
	], $map_provider->iframe_url );

	$data_src_attr = 'data-src="' . esc_url( $iframe_url ) . '"';
	$wrapper_classes['tribe-events-pro-map__event-card-wrapper--active'] = 0 === $index;
}
?>
<div
	<?php tribe_classes( $wrapper_classes ) ?>
	<?php echo $data_src_attr; ?>
	data-js="tribe-events-pro-map-event-card-wrapper"
	data-event-id="<?php echo esc_attr( $event->ID ); ?>"
>

	<?php $this->template( 'map/event-cards/event-card/event-button', [ 'event' => $event, 'index' => $index ] ); ?>

	<div class="tribe-events-pro-map__event-card">
		<div <?php tribe_classes( $classes ) ?>>

			<?php $this->template( 'map/event-cards/event-card/date-tag', [ 'event' => $event ] ); ?>

			<?php $this->template( 'map/event-cards/event-card/event', [ 'event' => $event, 'index' => $index ] ); ?>

		</div>
	</div>

	<?php $this->template( 'map/event-cards/event-card/tooltip', [ 'event' => $event ] ); ?>

</div>

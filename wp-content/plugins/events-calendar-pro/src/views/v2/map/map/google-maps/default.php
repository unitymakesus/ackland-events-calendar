<?php
/**
 * View: Map View - Google Maps Default
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/map/google-maps/default.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.7
 *
 */

// Gets the first event.
$event = reset( $events );

// Verifies if that event has a venue.
if ( ! isset( $event->venues ) || ! $event->venues->count() ) {
	return;
}

$url = add_query_arg( [
	'key' => $map_provider->api_key,
	'q'   => urlencode( $event->venues->first()->geolocation->address ),
], $map_provider->iframe_url );
?>
<iframe
	class="tribe-events-pro-map__google-maps-default"
	data-js="tribe-events-pro-map-google-maps-default"
	src="<?php echo esc_url( $url ); ?>"
>
</iframe>

<?php
/**
 * View: Map View - Event Button
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/event-cards/event-card/event-button.php
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
$aria_selected = $aria_expanded = ! $map_provider->is_premium && ( 0 === $index ) ? 'true' : 'false';
$aria_controls = 'tribe-events-pro-map-event-actions-' . $event->ID;

?>
<button
	class="tribe-events-pro-map__event-card-button"
	data-js="tribe-events-pro-map-event-card-button"
	aria-selected="<?php echo esc_attr( $aria_selected ); ?>"
	aria-controls="<?php echo esc_attr( $aria_controls ); ?>"
	aria-expanded="<?php echo esc_attr( $aria_expanded ); ?>"
>
	<span class="tribe-common-a11y-visual-hide"><?php echo get_the_title( $event->ID ); ?></span>
</button>

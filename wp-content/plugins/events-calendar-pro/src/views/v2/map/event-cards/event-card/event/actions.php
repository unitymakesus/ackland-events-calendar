<?php
/**
 * View: Map View - Single Event Actions
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/event-cards/event-card/event/actions.php
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
$aria_hidden = ! $map_provider->is_premium && ( 0 === $index ) ? 'false' : 'true';
?>
<div
	id="tribe-events-pro-map-event-actions-<?php echo esc_attr( $event->ID );?>"
	class="tribe-events-pro-map__event-actions tribe-common-b3 tribe-events-c-small-cta"
	aria-hidden="<?php echo esc_attr( $aria_hidden ); ?>"
	data-js="tribe-events-pro-map-event-actions-accordion"
>
	<?php $this->template( 'map/event-cards/event-card/event/actions/cost', [ 'event' => $event ] ); ?>
	<?php $this->template( 'map/event-cards/event-card/event/actions/details', [ 'event' => $event ] ); ?>
	<?php $this->template( 'map/event-cards/event-card/event/actions/directions', [ 'event' => $event ] ); ?>
</div>

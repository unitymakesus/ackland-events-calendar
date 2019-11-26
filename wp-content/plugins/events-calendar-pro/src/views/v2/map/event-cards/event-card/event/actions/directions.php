<?php
/**
 * View: Map View - Single Event Actions - Directions
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/event-cards/event-card/event/actions/directions.php
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
// @todo @be @bordoni: The map directions to the event venue.
?>
<a href="<?php echo esc_url( $event->get_directions ); ?>" class="tribe-events-c-small-cta__link tribe-common-cta tribe-common-cta--thin-alt">
	<?php esc_html_e( 'Get Directions', 'tribe-events-calendar-pro' ); ?>
</a>

<?php
/**
 * View: Week View - Mobile Event Venue
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week/mobile-events/day/event/venue.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.5
 *
 */
$event    = $this->get( 'event' );
$event_id = $event->ID;

// Setup an array of venue details for use later in the template
$venue_details = tribe_get_venue_details( $event_id );

if ( ! $venue_details ) {
	return;
}
?>
<address class="tribe-events-pro-week-mobile-events__event-venue tribe-common-b2">
	<span class="tribe-events-pro-week-mobile-events__event-venue-title tribe-common-b2--bold">
		<?php echo isset( $venue_details['linked_name'] ) ? $venue_details['linked_name'] : esc_html__( 'Venue Name', 'tribe-events-calendar-pro' ); ?>
	</span>
	<span class="tribe-events-pro-week-mobile-events__event-venue-address">
		<?php echo isset( $venue_details['address'] ) ? $venue_details['address'] : ''; ?>
	</span>
</address>

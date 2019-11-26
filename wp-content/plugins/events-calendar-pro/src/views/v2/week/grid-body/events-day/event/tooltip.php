<?php
/**
 * View: Week View - Event Tooltip
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/views/v2/week/grid-body/events-day/event/tooltip.php
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
?>
<div
	class="tribe-events-pro-week-grid__event-tooltip"
	data-js="tribe-events-tooltip-content"
	role="tooltip"
>
	<div id="tribe-events-tooltip-content-<?php echo esc_attr( $event->ID ); ?>">
		<?php $this->template( 'week/grid-body/events-day/event/tooltip/featured-image', [ 'event' => $event ] ); ?>
		<?php $this->template( 'week/grid-body/events-day/event/tooltip/description', [ 'event' => $event ] ); ?>
		<?php $this->template( 'week/grid-body/events-day/event/tooltip/cost', [ 'event' => $event ] ); ?>
	</div>
</div>

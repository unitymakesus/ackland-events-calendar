<?php
/**
 * View: Week View - Event Tooltip Description
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/views/v2//week/grid-body/events-day/event/tooltip/description.php
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
$description = get_the_excerpt( $event->ID ) ?: get_the_title( $event->ID );

?>
<p class="tribe-events-pro-week-grid__event-tooltip-description tribe-common-b3">
	<?php echo wp_kses_post( $description ) ?>
</p>

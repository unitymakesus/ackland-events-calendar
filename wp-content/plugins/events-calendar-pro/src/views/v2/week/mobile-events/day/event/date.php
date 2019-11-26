<?php
/**
 * View: Week View - Mobile Event Date
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week/mobile-events/day/event/date.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.8
 *
 * @var WP_Post $event The event post object, decorated with additional properties by the `tribe_get_event` function.
 *
 * @see tribe_get_event() for the additional properties added to the event post object.
 */

?>
<div class="tribe-events-pro-week-mobile-events__event-datetime-wrapper tribe-common-b2">
	<?php if ( ! empty( $event->featured ) ) : ?>
		<em
			class="tribe-events-pro-week-mobile-events__event-datetime-featured-icon tribe-common-svgicon tribe-common-svgicon--featured"
			aria-label="<?php esc_attr_e( 'Featured', 'tribe-events-calendar-pro' ); ?>"
			title="<?php esc_attr_e( 'Featured', 'tribe-events-calendar-pro' ); ?>"
		>
		</em>
	<?php endif; ?>
	<time
		class="tribe-events-pro-week-mobile-events__event-datetime"
		datetime="<?php echo esc_attr( $event->dates->start->format( 'c' ) ); ?>"
	>
		<?php echo $event->schedule_details->escaped(); // Already escaped. ?>
	</time>
	<?php if ( ! empty( $event->recurring ) ) : ?>
		<em
			class="tribe-events-pro-week-mobile-events__event-datetime-recurring-icon tribe-common-svgicon tribe-common-svgicon--recurring"
			aria-label="<?php esc_attr_e( 'Recurring', 'tribe-events-calendar-pro' ) ?>"
			title="<?php esc_attr_e( 'Recurring', 'tribe-events-calendar-pro' ) ?>"
		>
		</em>
	<?php endif; ?>
</div>

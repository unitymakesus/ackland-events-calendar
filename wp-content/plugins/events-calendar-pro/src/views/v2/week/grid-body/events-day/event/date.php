<?php
/**
 * View: Week View - Event Date
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week/grid-body/events-day/event/date.php
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

<div class="tribe-events-pro-week-grid__event-datetime">
	<?php if ( ! empty( $event->featured ) ) : ?>
		<em
			class="tribe-events-pro-week-grid__event-datetime-featured-icon tribe-common-svgicon tribe-common-svgicon--featured"
			aria-label="<?php esc_attr_e( 'Featured', 'tribe-events-calendar-pro' ) ?>"
			title="<?php esc_attr_e( 'Featured', 'tribe-events-calendar-pro' ) ?>"
		>
		</em>
	<?php endif; ?>
	<time
		datetime="<?php echo $event->dates->start->format( 'H:i' ); ?>">
		<?php echo $event->dates->start->format( 'g:i a' ); ?>
	</time>
	<span class="tribe-events-pro-week-grid__event-datetime-separator"> &mdash; </span>
	<time
		datetime="<?php echo $event->dates->end->format( 'H:i' ) ?>">
		<?php echo $event->dates->end->format( 'g:i a' ); ?>
	</time>
	<?php if ( ! empty( $event->recurring ) ) : ?>
		<em
			class="tribe-events-pro-week-grid__event-datetime-recurring-icon tribe-common-svgicon tribe-common-svgicon--recurring"
			aria-label="<?php esc_attr_e( 'Recurring', 'tribe-events-calendar-pro' ); ?>"
			title="<?php esc_attr_e( 'Recurring', 'tribe-events-calendar-pro' ); ?>"
		>
		</em>
	<?php endif; ?>
</div>

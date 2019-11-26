<?php
/**
 * View: Week View - Event Title
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week/grid-body/events-day/event/title.php
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
<h3 class="tribe-events-pro-week-grid__event-title tribe-common-h8 tribe-common-h--alt">
	<?php if ( ! empty( $event->featured ) ) : ?>
		<em
			class="tribe-events-pro-week-grid__event-title-featured-icon tribe-common-svgicon tribe-common-svgicon--featured"
			aria-label="<?php esc_attr_e( 'Featured', 'tribe-events-calendar-pro' ) ?>"
			title="<?php esc_attr_e( 'Featured', 'tribe-events-calendar-pro' ) ?>"
		>
		</em>
	<?php endif; ?>
	<?php echo esc_html( get_the_title( $event->ID ) ); ?>
</h3>

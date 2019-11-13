<?php
/**
 * View: Week View - Day Selector Days
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week/day-selector/days.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @var array $day Array of data of the day
 *
 * @version 4.7.7
 *
 */

/**
 * @todo: @be Luca or Gustavo
 *        Add active class.
 *        If base URL (/events/week/), then active class should be on today.
 *        If on specific week (/events/week/?tribe-bar-date=2019-07-14), then active class should be first day.
 */
$selected    = 'false';
$day_classes = [ 'tribe-events-pro-week-day-selector__day' ];

if ( ! empty( $day[ 'is_active' ] ) ) {
	$selected      = 'true';
	$day_classes[] = 'tribe-events-pro-week-day-selector__day--active';
}

?>
<li class="tribe-events-pro-week-day-selector__days-list-item">
	<button
		class="<?php echo esc_attr( implode( ' ', $day_classes ) ); ?>"
		aria-expanded="<?php echo esc_attr( $selected ); ?>"
		aria-selected="<?php echo esc_attr( $selected ); ?>"
		aria-controls="tribe-events-pro-week-mobile-events-day-<?php echo esc_attr( $day[ 'datetime' ] ); ?>"
		data-js="tribe-events-pro-week-day-selector-day"
	>

		<?php if ( ! empty( $day['found_events'] ) ) : ?>
			<em
				class="tribe-events-pro-week-day-selector__events-icon"
				aria-label="<?php esc_attr_e( 'Has events', 'the-events-calendar' ); ?>"
				title="<?php esc_attr_e( 'Has events', 'the-events-calendar' ); ?>"
			>
			</em>
		<?php endif; ?>

		<time class="tribe-events-pro-week-day-selector__day-datetime" datetime="<?php echo esc_attr( $day[ 'datetime' ] ); ?>">

			<span class="tribe-events-pro-week-day-selector__day-weekday tribe-common-b3">
				<?php echo esc_html( $day[ 'weekday' ] ); ?>
			</span>

			<span class="tribe-events-pro-week-day-selector__day-daynum tribe-common-h4">
				<?php echo esc_html( $day[ 'daynum' ] ); ?>
			</span>

		</time>

	</button>
</li>

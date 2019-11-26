<?php
/**
 * View: Week View - Grid Header
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week/grid-header.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.8
 *
 * @var array $day Array of data for the day.
 * @var string $today_date Today's date in the `Y-m-d` format.
 */
$day_classes = [ 'tribe-events-pro-week-grid__header-column-daynum', 'tribe-common-h4' ];

if ( $today_date === $day['datetime'] ) {
	$day_classes[] = 'tribe-events-pro-week-grid__header-column-daynum--current';
}
?>
<div
	class="tribe-events-pro-week-grid__header-column"
	role="columnheader"
	aria-label="<?php echo esc_attr( $day[ 'full_date' ] ); ?>"
>
	<h3 class="tribe-events-pro-week-grid__header-column-title">
		<time
			class="tribe-events-pro-week-grid__header-column-datetime"
			datetime="<?php echo esc_attr( $day[ 'datetime' ] ); ?>"
		>
			<span class="tribe-events-pro-week-grid__header-column-weekday tribe-common-h8 tribe-common-h--alt">
				<?php echo esc_html( $day[ 'weekday' ] ); ?>
			</span>
			<span <?php tribe_classes( $day_classes ); ?>>
				<?php echo esc_html( $day[ 'daynum' ] ); ?>
			</span>
		</time>
	</h3>
</div>

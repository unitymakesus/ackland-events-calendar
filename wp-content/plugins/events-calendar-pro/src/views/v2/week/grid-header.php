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
 */
?>
<header class="tribe-events-pro-week-grid__header" role="rowgroup">

	<h2 class="tribe-common-a11y-visual-hide" id="tribe-events-pro-week-header">
		<?php printf( esc_html__( 'Week of %s', 'tribe-events-calendar-pro' ), tribe_get_event_label_plural() ); ?>
	</h2>

	<div class="tribe-events-pro-week-grid__header-row" role="row">

		<div
			class="tribe-events-pro-week-grid__header-column tribe-events-pro-week-grid__header-column--empty"
			role="gridcell"
		>
		</div>

		<?php foreach ( $days_of_week as $day ) : ?>

			<?php $this->template( 'week/grid-header/header-column', [ 'day' => $day ] ); ?>

		<?php endforeach; ?>
	</div>
</header>

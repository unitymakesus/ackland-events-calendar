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
 * @version 4.7.8
 *
 *
 * @var array $days An array of each day data.
 */
?>
<ul class="tribe-events-pro-week-day-selector__days-list">

	<?php foreach ( $days as $day ) : ?>
		<?php $this->template( 'week/day-selector/days/day', [ 'day' => $day ] ); ?>
	<?php endforeach; ?>

</ul>

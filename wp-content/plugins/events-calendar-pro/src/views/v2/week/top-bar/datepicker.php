<?php
/**
 * View: Top Bar - Date Picker
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week/top-bar/datepicker.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.7
 *
 *
 * @var string $week_start_date The week start date, in `Y-m-d` format.
 * @var string $formatted_week_start_date The week start date, formatted to the user-selected format.
 * @var string $week_end_date The week end date, in `Y-m-d` format.
 * @var string $formatted_week_end_date The week end date, formatted to the user-selected format.
 */
?>
<div class="tribe-events-c-top-bar__datepicker">
	<button
		class="tribe-common-h2 tribe-common-h3--min-medium tribe-common-h--alt tribe-events-c-top-bar__datepicker-button"
		data-js="tribe-events-top-bar-datepicker-button"
	>
		<time datetime="<?php echo esc_attr( $week_start_date ); ?>">
			<?php echo esc_html( $formatted_week_start_date ); ?>
		</time>
		&mdash;
		<time datetime="<?php echo esc_attr( $week_end_date ); ?>">
			<?php echo esc_html( $formatted_week_end_date ); ?>
		</time>
	</button>
	<label
		class="tribe-events-c-top-bar__datepicker-label tribe-common-a11y-visual-hide"
		for="tribe-events-top-bar-date"
	>
		<?php esc_html_e( 'Select date.', 'the-events-calendar' ); ?>
	</label>
	<input
		type="text"
		class="tribe-events-c-top-bar__datepicker-input tribe-common-a11y-visual-hide"
		data-js="tribe-events-top-bar-date"
		id="tribe-events-top-bar-date"
		name="tribe-events-views[tribe-bar-search]"
		value="<?php echo esc_attr( tribe_events_template_var( [ 'bar', 'date' ], '' ) ); ?>"
		tabindex="-1"
		autocomplete="off"
	/>
	<div class="tribe-events-c-top-bar__datepicker-container" data-js="tribe-events-top-bar-datepicker-container"></div>
</div>

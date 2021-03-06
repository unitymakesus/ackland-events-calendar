<?php
/**
 * View: Top Bar - Date Picker
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/views/v2/list/top-bar/datepicker.php
 *
 * See more documentation about our views templating system.
 *
 * @link    {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.9.11
 *
 * @var bool   $is_now                     Whether the date selected in the datepicker is "now" or not.
 * @var bool   $show_now                   Whether to show the "Now" label as range start or not.
 * @var string $now_label                  The "Now" text label.
 * @var bool   $show_end                   Whether to show the end part of the range or not.
 * @var string $selected_start_datetime    The selected start date and time in the localized `Y-m-d` format.
 * @var string $selected_start_date_mobile The formatted date that will show in the datepicker mobile version.
 * @var string $selected_start_date_label  The label of the datepicker interval start.
 * @var string $selected_end_datetime      The selected end date and time in the localized `Y-m-d` format.
 * @var string $selected_end_date_mobile   The formatted date that will show in the datepicker mobile version.
 * @var string $selected_end_date_label    The label of the datepicker interval end.
 * @var string $datepicker_date            The datepicker selected date, in the `Y-m-d` format.
 */

?>
<div class="tribe-events-c-top-bar__datepicker">
	<button
		class="tribe-common-h3 tribe-common-h--alt tribe-events-c-top-bar__datepicker-button"
		data-js="tribe-events-top-bar-datepicker-button"
	>
		<?php if ( $show_now ) : ?>
			<?php echo esc_html( $now_label ); ?>
		<?php else : ?>
			<time
				datetime="<?php echo esc_attr( $selected_start_datetime ); ?>"
				class="tribe-events-c-top-bar__datepicker-time"
			>
				<span class="tribe-events-c-top-bar__datepicker-mobile">
					<?php echo esc_html( $selected_start_date_mobile ); ?>
				</span>
				<span class="tribe-events-c-top-bar__datepicker-desktop tribe-common-a11y-hidden">
					<?php echo esc_html( $selected_start_date_label ); ?>
				</span>
			</time>
		<?php endif; ?>
		<?php if ( $show_end ) : ?>
			<span class="tribe-events-c-top-bar__datepicker-separator">&mdash;</span>
			<time
				datetime="<?php echo esc_attr( $selected_end_datetime ); ?>"
				class="tribe-events-c-top-bar__datepicker-time"
			>
				<span class="tribe-events-c-top-bar__datepicker-mobile">
					<?php echo esc_html( $selected_end_date_mobile ); ?>
				</span>
				<span class="tribe-events-c-top-bar__datepicker-desktop tribe-common-a11y-hidden">
					<?php echo esc_html( $selected_end_date_label ); ?>
				</span>
			</time>
		<?php endif; ?>
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
		value="<?php echo esc_attr( $datepicker_date ); ?>"
		tabindex="-1"
		autocomplete="off"
	/>
	<div class="tribe-events-c-top-bar__datepicker-container" data-js="tribe-events-top-bar-datepicker-container"></div>
</div>

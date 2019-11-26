<?php
/**
 * View: Top Bar - Today
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/photo/top-bar/today.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.7
 *
 * @var string $today_url The URL to the today, current, version of the View.
 */

?>
<a
	href="<?php echo esc_url( $today_url ); ?>"
	class="tribe-common-c-btn-border tribe-events-c-top-bar__today-button"
	data-js="tribe-events-view-link"
>
	<?php esc_html_e( 'Today', 'tribe-events-calendar-pro' ); ?>
</a>

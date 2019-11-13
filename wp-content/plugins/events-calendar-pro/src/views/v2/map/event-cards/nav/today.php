<?php
/**
 * View: Map View Nav Today Button
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/event-cards/nav/today.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @var string $link The URL to the today page, if any, or an empty string.
 *
 * @version 4.7.8
 *
 * @var string $today_url The URL to the today, current, version of the View.
 */

?>
<li class="tribe-events-c-nav__list-item tribe-events-c-nav__list-item--today">
	<a
		href="<?php echo esc_url( $link ); ?>"
		class="tribe-events-c-nav__today tribe-common-b3"
		data-js="tribe-events-view-link"
	>
		<?php esc_html_e( 'Today', 'tribe-events-calendar-pro' ); ?>
	</a>
</li>

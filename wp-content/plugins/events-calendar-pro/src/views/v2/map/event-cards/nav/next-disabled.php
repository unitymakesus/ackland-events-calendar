<?php
/**
 * View: Map View Nav Disabled Next Button
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/event-cards/nav/next-disabled.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @var string $link The URL to the next page, if any, or an empty string.
 *
 * @version 4.7.8
 *
 */
?>
<li class="tribe-events-c-nav__list-item tribe-events-c-nav__list-item--next">
	<button class="tribe-events-c-nav__next tribe-common-b3" disabled>
		<?php esc_html_e( 'Next', 'tribe-events-calendar-pro' ); ?>
	</button>
</li>

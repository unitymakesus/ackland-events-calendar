<?php
/**
 * View: Map View Nav Disabled Previous Button
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/event-cards/nav/prev-disabled.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @var string $link The URL to the previous page, if any, or an empty string.
 *
 * @version 4.7.8
 *
 */
?>
<li class="tribe-events-c-nav__list-item tribe-events-c-nav__list-item--prev">
	<button class="tribe-events-c-nav__prev tribe-common-b3" disabled>
		<?php esc_html_e( 'Prev', 'tribe-events-calendar-pro' ); ?>
	</button>
</li>

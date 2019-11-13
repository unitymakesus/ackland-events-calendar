<?php
/**
 * View: Week View Nav Previous Button
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week/nav/prev.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTICLE_LINK_HERE}
 *
 * @var string $link The URL to the previous page, if any, or an empty string.
 * @var string $label The label for the previous link.
 *
 * @version 4.7.6
 *
 */
?>
<li class="tribe-events-c-nav__list-item tribe-events-c-nav__list-item--prev">
	<a
		href="<?php echo esc_url( $link ); ?>"
		rel="prev"
		class="tribe-events-c-nav__prev tribe-common-b2"
		data-js="tribe-events-view-link"
	>
		<?php echo esc_html( $label ); ?>
	</a>
</li>

<?php
/**
 * View: Week View Nav Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week/nav.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTICLE_LINK_HERE}
 *
 * @var string $prev_url The URL to the previous page, if any, or an empty string.
 * @var string $prev_label The label for the previous link.
 * @var string $next_url The URL to the next page, if any, or an empty string.
 * @var string $next_label The label for the next link.
 * @var string $today_url The URL to the today page, if any, or an empty string.
 * @var string $location The location of the nav.
 *
 * @version 4.7.8
 *
 */
?>
<nav class="tribe-events-pro-week-nav tribe-events-pro-week-nav--<?php echo esc_attr( $location ); ?> tribe-events-c-nav">
	<ul class="tribe-events-c-nav__list">
		<?php
		if ( ! empty( $prev_url ) ) {
			$this->template( 'week/nav/prev', [ 'label' => __( 'Previous', 'tribe-events-calendar-pro' ), 'link' => $prev_url ] );
		} else {
			$this->template( 'week/nav/prev-disabled', [ 'label' => __( 'Previous', 'tribe-events-calendar-pro' ) ] );
		}
		?>

		<?php $this->template( 'week/nav/today', [ 'link' => $today_url ] ); ?>

		<?php
		if ( ! empty( $next_url ) ) {
			$this->template( 'week/nav/next', [ 'label' => __( 'Next', 'tribe-events-calendar-pro' ), 'link' => $next_url ] );
		} else {
			$this->template( 'week/nav/next-disabled', [ 'label' => __( 'Next', 'tribe-events-calendar-pro' ) ] );
		}
		?>
	</ul>
</nav>

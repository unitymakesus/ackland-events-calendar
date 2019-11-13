<?php
/**
 * View: Photo View Nav Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/photo/nav.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @var string $prev_url The URL to the previous page, if any, or an empty string.
 * @var string $next_url The URL to the next page, if any, or an empty string.
 * @var string $today_url The URL to the today page, if any, or an empty string.
 *
 * @version 4.7.8
 *
 */
?>
<nav class="tribe-events-pro-photo-nav tribe-events-c-nav">
	<ul class="tribe-events-c-nav__list">
		<?php
		if ( ! empty( $prev_url ) ) {
			$this->template( 'photo/nav/prev', [ 'link' => $prev_url ] );
		} else {
			$this->template( 'photo/nav/prev-disabled' );
		}
		?>

		<?php $this->template( 'photo/nav/today', [ 'link' => $today_url ] ); ?>

		<?php
		if ( ! empty( $next_url ) ) {
			$this->template( 'photo/nav/next', [ 'link' => $next_url ] );
		} else {
			$this->template( 'photo/nav/next-disabled' );
		}
		?>
	</ul>
</nav>

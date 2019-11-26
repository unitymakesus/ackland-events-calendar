<?php
/**
 * View: Map View
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.8
 *
 * @var  string  $rest_url The REST URL.
 * @var  string  $rest_nonce The REST nonce.
 * @var  int     $should_manage_url int containing if it should manage the URL.
 * @var  array   $events An array of the week events, in sequence.
 * @var  string  $today_url URL pointing to the today link for this view.
 * @var  string  $prev_url URL pointing to the prev page link for this view.
 * @var  string  $next_url URL pointing to the next page link for this view.
 * @var  array   $providers Array with all the possible map providers available to the view.
 */
?>
<div
	class="tribe-common tribe-events tribe-events-view tribe-events-pro tribe-events-view--map"
	data-js="tribe-events-view"
	data-view-rest-nonce="<?php echo esc_attr( $rest_nonce ); ?>"
	data-view-rest-url="<?php echo esc_url( $rest_url ); ?>"
	data-view-manage-url="<?php echo esc_attr( $should_manage_url ); ?>"
>
	<div class="tribe-common-l-container tribe-events-l-container">
		<?php $this->template( 'loader', [ 'text' => 'Loading...' ] ); ?>

		<?php $this->template( 'data' ); ?>

		<header class="tribe-events-header">
			<?php $this->template( 'events-bar' ); ?>
			<?php // @todo: Add $this->template( 'top-bar' ); ?>
		</header>

		<div class="tribe-events-pro-map tribe-common-g-row">
			<?php $this->template( 'map/map' ); ?>
			<?php $this->template( 'map/event-cards' ); ?>
		</div>
	</div>
</div>

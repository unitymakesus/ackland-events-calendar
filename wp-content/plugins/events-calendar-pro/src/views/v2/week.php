<?php
/**
 * View: Week View
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.7
 *
 * @var string $rest_url The REST URL.
 * @var string $rest_nonce The REST nonce.
 * @var int    $should_manage_url int containing if it should manage the URL.
 * @var array $events An array of the week events, in sequence.
 * @var array $mobile_days An array of the week events, formatted to the requirements of the mobile version of the View.
 */

?>

<div
	class="tribe-common tribe-events tribe-events-view tribe-events-pro tribe-events-view--week"
	data-js="tribe-events-view"
	data-view-rest-nonce="<?php echo esc_attr( $rest_nonce ); ?>"
	data-view-rest-url="<?php echo esc_url( $rest_url ); ?>"
	data-view-manage-url="<?php echo esc_attr( $should_manage_url ); ?>"
>
	<div class="tribe-common-l-container tribe-events-l-container">

		<?php $this->template( 'loader', [ 'text' => __( 'Loading...', 'tribe-events-calendar-pro' ) ] ); ?>

		<?php $this->template( 'data' ); ?>

		<header class="tribe-events-header">
			<?php $this->template( 'events-bar' ); ?>

			<?php $this->template( 'week/top-bar' ); ?>
		</header>


		<?php $this->template( 'week/day-selector' ); ?>

		<?php $this->template( 'week/mobile-events', [ 'days' => $mobile_days ] ); ?>

		<div
			class="tribe-events-pro-week-grid"
			role="grid"
			aria-labelledby="tribe-events-pro-week-header"
			aria-readonly="true"
		>

			<?php $this->template( 'week/grid-header' ); ?>

			<?php $this->template( 'week/grid-body' ); ?>

		</div>

		<?php
		/**
		 * @todo @fe: add navigation here
		 */
		?>

	</div>
</div>

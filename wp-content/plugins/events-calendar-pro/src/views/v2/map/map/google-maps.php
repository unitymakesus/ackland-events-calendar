
<?php
/**
 * View: Map View - Google Maps
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/map/google-maps.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.8
 *
 * @var  array   $events An array of the week events, in sequence.
 * @var  array   $providers Array with all the possible map providers available to the view.
 */
?>
<?php if ( empty( $map_provider->is_premium ) ) : ?>
	<?php if ( ! empty( $events ) ) : ?>
		<?php $this->template( 'map/map/google-maps/default' ); ?>
	<?php endif; ?>
<?php else : ?>
	<?php $this->template( 'map/map/google-maps/premium' ); ?>
<?php endif; ?>

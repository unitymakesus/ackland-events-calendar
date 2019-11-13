<?php
/**
 * View: Map View - Single Event
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/event-cards/event-card/event.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.8
 *
 * @var WP_Post $event The event post object with properties added by the `tribe_get_event` function.
 *
 * @see tribe_get_event() For the format of the event object.
 *
 */
?>
<div class="tribe-events-pro-map__event-wrapper tribe-common-g-col">
	<article class="tribe-events-pro-map__event tribe-common-g-row tribe-events-pro-map__event-row--gutters">

		<div class="tribe-events-pro-map__event-details tribe-common-g-col">

			<?php $this->template( 'map/event-cards/event-card/event/date-time', [ 'event' => $event ] ); ?>
			<?php $this->template( 'map/event-cards/event-card/event/title', [ 'event' => $event ] ); ?>
			<?php $this->template( 'map/event-cards/event-card/event/venue', [ 'event' => $event ] ); ?>
			<?php $this->template( 'map/event-cards/event-card/event/distance', [ 'event' => $event ] ); ?>

			<?php $this->template( 'map/event-cards/event-card/event/actions', [ 'event' => $event, 'index' => $index ] ); ?>

		</div>

		<?php $this->template( 'map/event-cards/event-card/event/featured-image', [ 'event' => $event ] ); ?>

	</article>
</div>

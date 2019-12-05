<?php

class Tribe__Tickets_Plus__Promoter_Observer {
	/**
	 * Hooks on which this obseverver notifies promoter
	 *
	 * @since 4.10.1.2
	 */
	public function hook() {
		// Older versions of ET do not have the class registered.
		if ( ! tribe()->isBound( 'tickets.promoter.observer' ) ) {
			return;
		}

		// WOO
		add_action( 'event_tickets_woocommerce_tickets_generated_for_product', array( $this, 'woocommerce_tickets_generated_for_product' ), 10, 4 );
		add_action( 'wootickets_ticket_deleted', tribe_callback( 'tickets.promoter.observer', 'notify_event_id' ), 10, 2 );
		add_action( 'tribe_tickets_plus_woo_reset_attendee_cache', tribe_callback( 'tickets.promoter.observer', 'notify' ) );
		// EDD
		add_action( 'event_ticket_edd_attendee_created', tribe_callback( 'tickets.promoter.observer', 'notify_event_id' ), 10, 2 );
		add_action( 'eddtickets_ticket_deleted', tribe_callback( 'tickets.promoter.observer', 'notify_event_id' ), 10, 2 );
	}

	/**
	 * Notify the post ID where the attendees were created
	 *
	 * @since 4.10.1.2
	 *
	 * @param $product_id
	 * @param $order_id
	 * @param $quantity
	 * @param $post_id
	 */
	public function woocommerce_tickets_generated_for_product( $product_id, $order_id, $quantity, $post_id ) {
		tribe( 'tickets.promoter.observer' )->notify( $post_id );
	}
}
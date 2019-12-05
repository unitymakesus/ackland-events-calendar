<?php
/**
 * EDD cart class
 *
 * @since 4.9
 */
class Tribe__Tickets_Plus__Commerce__EDD__Cart extends Tribe__Tickets_Plus__Commerce__Abstract_Cart {
	/**
	 * Hook relevant actions and filters
	 *
	 * @since 4.9
	 */
	public function hook() {
		parent::hook();

		add_filter( 'tribe_tickets_attendee_registration_checkout_url', [ $this, 'maybe_filter_attendee_registration_checkout_url' ], 9 );
		add_filter( 'tribe_tickets_tickets_in_cart', [ $this, 'get_tickets_in_cart' ] );
		add_filter( 'tribe_providers_in_cart', [ $this, 'providers_in_cart' ], 11 );
	}

	/**
	 * Hijack URL if on cart and there
	 * are attendee registration fields that need to be filled out
	 *
	 * @since 4.9
	 *
	 * @param string $checkout_url
	 *
	 * @return string
	 */
	public function maybe_filter_attendee_registration_checkout_url( $checkout_url ) {
		if ( empty( edd_get_cart_contents() ) ) {
			return $checkout_url;
		}

		/** @var \Tribe__Tickets_Plus__Commerce__EDD__Main $commerce_edd */
		$commerce_edd = tribe( 'tickets-plus.commerce.edd' );

		if ( $commerce_edd->attendee_object !== tribe_get_request_var( 'provider' ) ) {
			return $checkout_url;
		}

		$attendee_reg = tribe( 'tickets.attendee_registration' );
		$on_registration_page = $attendee_reg->is_on_page() || $attendee_reg->is_using_shortcode();

		// we only want to override if we are on the cart page or the attendee registration page
		if ( ! $on_registration_page ) {
			return $checkout_url;
		}

		return edd_get_checkout_uri();
	}

	/**
	 * Hooked to tribe_providers_in_cart adds EDD as a provider for checks if there are EDD items in the "cart"
	 *
	 * @since 4.10.2
	 *
	 * @param array $providers
	 * @return array providers, with EDD optionally added
	 */
	public function providers_in_cart( $providers ) {
		if ( empty( edd_get_cart_contents() ) ) {
			return $providers;
		}

		$providers[] = 'EDD';

		return $providers;
	}

	/**
	 * Get all tickets currently in the cart.
	 *
	 * @since 4.9
	 *
	 * @param array $tickets Array indexed by ticket id with quantity as the value
	 *
	 * @return array
	 */
	public function get_tickets_in_cart( $tickets = array() ) {
		$contents = edd_get_cart_contents();

		if ( empty( $contents ) ) {
			return $tickets;
		}

		foreach ( $contents as $item ) {
			$edd_check = get_post_meta( $item['id'], tribe( 'tickets-plus.commerce.edd' )->event_key, true );

			if ( empty( $edd_check ) ) {
				continue;
			}

			$tickets[ $item['id'] ] = $item['quantity'];
		}

		/**
		 * Allows for filtering the returned tickets for easier third-party plugin compatibility.
		 *
		 * @since 4.10.8
		 *
		 * @param array $tickets  List of tickets currently in the cart.
		 * @param array $contents The EDD cart contents.
		 */
		return apply_filters( 'tribe_tickets_plus_edd_tickets_in_cart', $tickets, $contents );
	}
}

<?php

/**
 * WooCommerce cart class
 *
 * @since 4.9
 */
class Tribe__Tickets_Plus__Commerce__WooCommerce__Cart extends Tribe__Tickets_Plus__Commerce__Abstract_Cart {
	/**
	 * Hook relevant actions and filters
	 *
	 * @since 4.9
	 */
	public function hook() {
		parent::hook();

		add_filter( 'tribe_tickets_attendee_registration_checkout_url', [ $this, 'maybe_filter_attendee_registration_checkout_url' ], 8 );
		add_filter( 'woocommerce_get_checkout_url', [ $this, 'maybe_filter_checkout_url_to_attendee_registration' ], 12 );
		add_filter( 'tribe_tickets_tickets_in_cart', [ $this, 'get_tickets_in_cart' ] );
		add_filter( 'tribe_providers_in_cart', [ $this, 'providers_in_cart' ], 15 );
		add_filter( 'tribe_tickets_woo_cart_url', [ $this, 'add_provider_to_cart_url' ] );
	}

	/**
	 * Hooked to the tribe_tickets_attendee_registration_checkout_url filter to hijack URL if on cart and there
	 * are attendee registration fields that need to be filled out.
	 *
	 * @since 4.9
	 *
	 * @param string $checkout_url
	 *
	 * @return string
	 */
	public function maybe_filter_attendee_registration_checkout_url( $checkout_url ) {
		return $this->maybe_filter_checkout_url_to_attendee_registration( $checkout_url );
	}

	/**
	 * Set WooCommerce cart/checkout URL for Attendee Registration Checkout and elsewhere and add 'provider' query arg.
	 *
	 * @since 4.9
	 *
	 * @see   \wc_get_checkout_url()
	 * @see   \Tribe__Tickets__Attendee_Registration__Main::get_checkout_url()
	 *
	 * @param string $checkout_url
	 *
	 * @return string
	 */
	public function maybe_filter_checkout_url_to_attendee_registration( $checkout_url ) {
		/** @var \Tribe__Tickets__Attendee_Registration__Main $a_reg */
		$a_reg = tribe( 'tickets.attendee_registration' );

		$cart_tickets = $this->get_tickets_in_cart();

		/** @var \Tribe__Tickets_Plus__Meta $tickets_meta */
		$tickets_meta  = tribe( 'tickets-plus.main' )->meta();
		$cart_has_meta = $tickets_meta->cart_has_meta( $cart_tickets );

		if (
			$a_reg->is_on_page()
			|| $a_reg->is_using_shortcode()
		) {
			if ( empty( $checkout_url ) ) {
				remove_filter( 'woocommerce_get_checkout_url', [ $this, 'maybe_filter_checkout_url_to_attendee_registration' ], 12 );
				$checkout_url = wc_get_checkout_url();
				add_filter( 'woocommerce_get_checkout_url', [ $this, 'maybe_filter_checkout_url_to_attendee_registration' ], 12 );
			}

			return $checkout_url;
		} elseif ( $cart_has_meta ) {
			return add_query_arg( 'provider', tribe( 'tickets-plus.commerce.woo' )->attendee_object, tribe( 'tickets.attendee_registration' )->get_url() );
		} else {
			return $checkout_url;
		}

	}

	/**
	 * Adds a 'provider' query argument set to the ticket type to the passed URL (e.g. cart or checkout), if a ticket
	 * with Attendee Information is in the cart, to assist with keeping tickets from different providers separate.
	 *
	 * @since 4.10.4
	 *
	 * @see   \Tribe__Tickets_Plus__Commerce__WooCommerce__Main::get_cart_url()
	 *
	 * @param string $url Cart or Checkout URL.
	 *
	 * @return string The URL after potentially being modified.
	 */
	public function add_provider_to_cart_url( $url = '' ) {
		if ( empty( $url ) ) {
			return $url;
		}

		$cart_tickets = $this->get_tickets_in_cart();

		/** @var \Tribe__Tickets_Plus__Meta $tickets_meta */
		$tickets_meta  = tribe( 'tickets-plus.main' )->meta();
		$cart_has_meta = $tickets_meta->cart_has_meta( $cart_tickets );

		if ( ! $cart_has_meta ) {
			return $url;
		}

		/** @var \Tribe__Tickets_Plus__Commerce__WooCommerce__Main $woo */
		$woo = tribe( 'tickets-plus.commerce.woo' );

		$url = add_query_arg( 'provider', $woo->attendee_object, $url );

		return $url;
	}

	/**
	 * Filter the tickets in the Cart to have the WooCommerce class as a provider so Attendee Registration works.
	 *
	 * @since 4.9
	 *
	 * @param array $tickets Array indexed by ticket id with quantity as the value.
	 *
	 * @return array
	 */
	public function get_tickets_in_cart( $tickets = [] ) {
		// If the cart is null, we need to bail to prevent any "Call to a member function on null" errors
		if ( is_null( WC()->cart ) ) {
			return [];
		}

		$contents = WC()->cart->get_cart_contents();
		if ( empty( $contents ) ) {
			return $tickets;
		}

		$event_key = tribe( 'tickets-plus.commerce.woo' )->event_key;

		foreach ( $contents as $item ) {
			$product_id = $item['product_id'];
			$woo_check  = get_post_meta( $product_id, $event_key, true );

			if ( empty( $woo_check ) ) {
				continue;
			}

			if ( isset( $tickets[ $product_id ] ) ) {
				$tickets[ $product_id ] += $item['quantity'];
			} else {
				$tickets[ $product_id ] = $item['quantity'];
			}
		}

		/**
		 * Allows for filtering the returned tickets for easier third-party plugin compatibility.
		 *
		 * @since 4.10.8
		 *
		 * @param array $tickets  List of tickets currently in the cart.
		 * @param array $contents The WooCommerce cart contents.
		 */
		return apply_filters( 'tribe_tickets_plus_woocommerce_tickets_in_cart', $tickets, $contents );
	}

	/**
	 * Identify WooCommerce as a provider for checks if there are WooCommerce tickets in the cart.
	 *
	 * @since 4.10.2
	 *
	 * @see   \Tribe__Tickets__Attendee_Registration__Main::providers_in_cart()
	 * @see   \Tribe__Tickets__Attendee_Registration__View::display_attendee_registration_page()
	 *
	 * @param array $providers
	 *
	 * @return array List of providers, with others optionally added.
	 */
	public function providers_in_cart( $providers ) {
		if ( empty( $this->get_tickets_in_cart() ) ) {
			return $providers;
		}

		$providers[] = 'woo';

		return $providers;
	}
}

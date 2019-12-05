<?php
/**
 * Add WooCommerce settings to the tickets settings, and automatically pull some settings from Woo.
 */
class Tribe__Tickets_Plus__Commerce__WooCommerce__Settings {
	public function __construct() {
		add_filter( 'tribe_tickets_settings_tab_fields', array( $this, 'add_settings' ) );

		// Use Woo's decimal separator in the Add Ticket Cost field.
		add_filter( 'tribe_event_ticket_decimal_point', 'wc_get_price_decimal_separator' );

		// Conditionally add settings for paypal delay
		add_filter( 'tribe_tickets_woo_settings', array( $this, 'maybe_add_paypal_delay_settings' ) );
	}

	/**
	 * Append WooCommerce-specific settings section to tickets settings tab
	 * @param array $settings_fields
	 *
	 * @since 4.7
	 *
	 * @return array
	 */
	public function add_settings( array $settings_fields ) {
		$extra_settings = $this->additional_settings();
		return Tribe__Main::array_insert_before_key( 'tribe-form-content-end', $settings_fields, $extra_settings );
	}

	/**
	 * Inserts additional settings fields to the Tickets tab
	 *
	 * @return array $fields
	 */
	protected function additional_settings() {
		$dispatch_options = $generation_options = $this->get_trigger_statuses();

		$section_label      = esc_html__( 'Event Tickets uses WooCommerce order statuses to control when attendee records should be generated and when tickets are sent to customers. The first enabled status reached by an order will trigger the action.', 'event-tickets-plus' );
		$dispatch_label     = esc_html__( 'When should tickets be emailed to customers?', 'event-tickets-plus' );
		$dispatch_tooltip   = esc_html__( 'If no status is selected, no ticket emails will be sent.', 'event-tickets-plus' );
		$generation_label   = esc_html__( 'When should attendee records be generated?', 'event-tickets-plus' );
		$generation_tooltip = esc_html__( 'Please select at least one status.', 'event-tickets-plus' );

		$dispatch_defaults = $this->get_default_ticket_dispatch_statuses();
		$generation_defaults = $this->get_default_ticket_generation_statuses();

		$fields = array(
			'tickets-woo-options-title' => array(
				'type' => 'html',
				'html' => '<h3>' . esc_html__( 'WooCommerce Support', 'event-tickets-plus' ) . '</h3>',
			),
			'tickets-woo-options-intro' => array(
				'type' => 'html',
				'html' => "<p> $section_label </p>",
			),
			'tickets-woo-generation-status' => array(
				'type'            => 'checkbox_list',
				'validation_type' => 'options_multi',
				'label'           => $generation_label,
				'tooltip'         => $generation_tooltip,
				'options'         => $generation_options,
				'default'         => $generation_defaults,
				'can_be_empty'    => true,
			),
			'tickets-woo-dispatch-status' => array(
				'type'            => 'checkbox_list',
				'validation_type' => 'options_multi',
				'label'           => $dispatch_label,
				'tooltip'         => $dispatch_tooltip,
				'options'         => $dispatch_options,
				'default'         => $dispatch_defaults,
				'can_be_empty'    => true,
			),
		);

		/**
		 * Allows other plugins to alter the settings fields added to the tickets tab
		 *
		 * @since 4.10.1
		 *
		 * @param array $fields - the additional fields
		 */
		$fields = apply_filters( 'tribe_tickets_woo_settings', $fields );

		return $fields;
	}

	/**
	 * Conditionally adds paypal fields if WooCommerce has a PayPal gateway active
	 *
	 * @since 4.10.1
	 *
	 * @param array $fields
	 *
	 * @return array $fields
	 */
	public function maybe_add_paypal_delay_settings( $fields ) {
		$paypal = Tribe__Tickets_Plus__Commerce__WooCommerce__Main::is_wc_paypal_gateway_active();

		// bail if we don't have one
		if ( empty( $paypal ) ) {
			return $fields;
		}

		// add paypal checkbox
		$fields['tickets-woo-paypal-delay'] = array(
			'type'            => 'radio',
			'default'         => 'delay',
			'validation_type' => 'options',
			'label'           => __( 'Handling PayPal orders:', 'event-tickets-plus' ),
			'options'         => array(
				'delay'     => __( 'Wait at least 5 seconds after WooCommerce order status change before generating attendees and tickets in order to prevent unwanted duplicates. <i>Recommended for anyone using PayPal with WooCommerce.</i>', 'event-tickets-plus' ),
				'immediate' => __( 'Generate attendees and tickets immediately upon WooCommerce order status change. Depending on your PayPal settings, this can result in duplicated attendees.', 'event-tickets-plus' ),
			),
		);

		return $fields;
	}

	/**
	 * Returns a map of order statuses (and labels).
	 *
	 * @return string[string]
	 */
	protected function get_trigger_statuses() {
		$statuses = array( 'immediate' => __( 'As soon as an order is created', 'event-tickets-plus' ) )
			+ (array) tribe( 'tickets.status' )->get_trigger_statuses( 'woo' );

		/**
		 * Lists the possible options for generating and dispatching tickets.
		 *
		 * This is typically a map of all the WooCommerce order statuses, plus an additional
		 * option to generate them immediately an order is created.
		 *
		 * @param array $dispatch_options
		 */
		return (array) apply_filters( 'tribe_tickets_plus_woo_trigger_statuses', $statuses );
	}

	/**
	 * @return array
	 */
	public function get_default_ticket_dispatch_statuses() {
		return tribe( 'tickets.status' )->get_statuses_by_action( 'attendee_dispatch', 'woo' );
	}

	/**
	 * @return array
	 */
	public function get_default_ticket_generation_statuses() {
		return tribe( 'tickets.status' )->get_statuses_by_action( 'attendee_generation', 'woo' );
	}
}

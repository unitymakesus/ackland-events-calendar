<?php
/**
 * Handles the filtering of the Context to add PRO specific locations.
 *
 * @since   4.7.5
 * @package Tribe\Events\Service_Providers
 */

namespace Tribe\Events\PRO\Service_Providers;

use Tribe__Context;

class Context extends \tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since 4.7.5
	 */
	public function register() {
		add_filter( 'tribe_context_locations', [ $this, 'filter_context_locations' ] );
	}

	/**
	 * Filters the context locations to add the ones used by The Events Calendar PRO.
	 *
	 * @since 4.7.5
	 *
	 * @param array $locations The array of context locations.
	 *
	 * @return array The modified context locations.
	 */
	public function filter_context_locations( array $locations = [] ) {
		$locations = array_merge( $locations, [
			'hide_subsequent_recurrences' => [
				'read'  => [
					Tribe__Context::WP_MATCHED_QUERY => [ 'hide_subsequent_recurrences' ],
					Tribe__Context::WP_PARSED        => [ 'hide_subsequent_recurrences' ],
					Tribe__Context::REQUEST_VAR      => [ 'hide_subsequent_recurrences' ],
					Tribe__Context::QUERY_VAR        => [ 'hide_subsequent_recurrences' ],
					Tribe__Context::TRIBE_OPTION     => [ 'hideSubsequentRecurrencesDefault' ],
				],
				'write' => [
					Tribe__Context::REQUEST_VAR => [ 'hide_subsequent_recurrences' ],
					Tribe__Context::QUERY_VAR   => [ 'hide_subsequent_recurrences' ],
				],
			],
		] );

		return $locations;
	}
}

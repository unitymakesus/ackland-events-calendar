<?php
/**
 * An extension of The Events Calendar rewrite handler to handle PRO rules.
 *
 * @since   4.7.5
 * @package Tribe\Events\Pro\Rewrite
 */

namespace Tribe\Events\Pro\Rewrite;

use Tribe__Events__Main as TEC;

/**
 * Class Rewrite
 *
 * @since   4.7.5
 * @package Tribe\Events\Pro\Rewrite
 */
class Rewrite extends \Tribe__Events__Rewrite {

	/**
	 * An ovveride of the base class method to make sure we're taking Pro rewrite rules into acount when parsing a URL.
	 *
	 * @since 4.7.5
	 *
	 * @param array $query_vars An array of query vars found in the current URL.
	 *
	 * @return array The dynamic matchers, modified if required.
	 */
	protected function get_dynamic_matchers( array $query_vars ) {
		$dynamic_matchers = parent::get_dynamic_matchers( $query_vars );
		$bases            = (array) $this->get_bases();

		if ( isset( $query_vars['tribe_recurrence_list'], $query_vars[ TEC::POSTTYPE ] ) ) {
			$all_regex = $bases['all'];
			preg_match( '/^\(\?:(?<slugs>[^\\)]+)\)/', $all_regex, $matches );
			if ( isset( $matches['slugs'] ) ) {
				$slugs = explode( '|', $matches['slugs'] );
				// The localized version is the last.
				$localized_slug                           = end( $slugs );
				$dynamic_matchers["([^/]+)/{$all_regex}"] = "{$query_vars[TEC::POSTTYPE]}/{$localized_slug}";
			}
		}

		return $dynamic_matchers;
	}
}

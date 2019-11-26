<?php
/**
 * Handles the filters applied by this plugin to the Views.
 *
 * @since   4.7.5
 * @package Tribe\Events\Pro\Views\V2
 */

namespace Tribe\Events\Pro\Views\V2;

use Tribe__Context as Context;

/**
 * Class View_Filters
 * @since   4.7.5
 * @package Tribe\Events\Pro\Views\V2
 */
class View_Filters {

	/**
	 * Filters the View repository args to apply the applicable filters provided by the plugin.
	 *
	 * @since 4.7.5
	 *
	 * @param array        $repository_args         The current repository args.
	 * @param Context|null $context                 An instance of the context the View is using or `null` to use the
	 *                                              global Context.
	 *
	 * @return array The filtered repository args.
	 */
	public function filter_repository_args( array $repository_args, Context $context = null ) {
		$context = null !== $context ? $context : tribe_context();

		$hide_subsequent_recurrences = (bool) $context->get( 'hide_subsequent_recurrences', false );
		if ( $hide_subsequent_recurrences ) {
			$repository_args['hide_subsequent_recurrences'] = true;
		}

		return $repository_args;
	}

	/**
	 * Filters the View template variables before the HTML is generated to add the ones related to this plugin filters.
	 *
	 * @since 4.7.5
	 *
	 * @param array   $template_vars The View template variables.
	 * @param Context $context       The View current context.
	 *
	 * @return array The filtered template variables.
	 */
	public function filter_template_vars( array $template_vars, Context $context = null ) {
		$context = null !== $context ? $context : tribe_context();

		$hide_subsequent_recurrences = (bool) $context->get( 'hide_subsequent_recurrences', false );
		if ( $hide_subsequent_recurrences ) {
			if ( empty( $template_vars['bar'] ) ) {
				$template_vars['bar'] = [];
			}
			$template_vars['bar']['hide_recurring'] = 'true';
		}

		return $template_vars;
	}
}

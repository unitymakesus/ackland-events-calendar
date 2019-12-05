<?php
/**
 * Class Tribe__Tickets__Plugin_Register
 */
class Tribe__Tickets_Plus__Plugin_Register extends Tribe__Abstract_Plugin_Register {

	protected $main_class   = 'Tribe__Tickets_Plus__Main';
	protected $dependencies = array(
		'parent-dependencies' => array(
			'Tribe__Tickets__Main' => '4.10.8-dev',
		),
	);

	public function __construct() {
		$this->base_dir = EVENT_TICKETS_PLUS_FILE;
		$this->version  = Tribe__Tickets_Plus__Main::VERSION;

		$this->register_plugin();
	}
}

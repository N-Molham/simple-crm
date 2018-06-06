<?php namespace Simple_CRM;

/**
 * Base Component
 *
 * @package Simple_CRM
 */
class Component extends Singular {

	/**
	 * Plugin Main Component
	 *
	 * @var Plugin
	 */
	protected $plugin;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function init() {

		// vars
		$this->plugin = Plugin::get_instance();
		
	}
}

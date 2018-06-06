<?php
/**
 * Created by Nabeel
 * Date: 2016-01-22
 * Time: 2:38 AM
 *
 * @package Simple_CRM
 */

use Simple_CRM\Component;
use Simple_CRM\Plugin;

if ( ! function_exists( 'simple_crm' ) ):
	/**
	 * Get plugin instance
	 *
	 * @return Plugin
	 */
	function simple_crm() {

		return Plugin::get_instance();
		
	}
endif;

if ( ! function_exists( 'scrm_component' ) ):
	/**
	 * Get plugin component instance
	 *
	 * @param string $component_name
	 *
	 * @return Component|null
	 */
	function scrm_component( $component_name ) {

		if ( isset( simple_crm()->$component_name ) ) {
			return simple_crm()->$component_name;
		}

		return null;
		
	}
endif;

if ( ! function_exists( 'scrm_view' ) ):
	/**
	 * Load view
	 *
	 * @param string  $view_name
	 * @param array   $args
	 * @param boolean $return
	 *
	 * @return void
	 */
	function scrm_view( $view_name, $args = null, $return = false ) {

		if ( $return ) {
			// start buffer
			ob_start();
		}

		simple_crm()->load_view( $view_name, $args );

		if ( $return ) {
			// get buffer flush
			return ob_get_clean();
		}
		
	}
endif;

if ( ! function_exists( 'scrm_version' ) ):
	/**
	 * Get plugin version
	 *
	 * @return string
	 */
	function scrm_version() {

		return simple_crm()->version;
		
	}
endif;
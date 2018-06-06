<?php namespace Simple_CRM;

/**
 * Frontend logic
 *
 * @package Simple_CRM
 */
class Frontend extends Component {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function init() {

		parent::init();

		add_shortcode( 'simple-crm-form', [ $this, 'render_customer_form' ] );

	}

	/**
	 * @param $attributes
	 *
	 * @return string
	 */
	public function render_customer_form( $attributes ) {

		$customer_fields = $this->plugin->customers->get_fields();

		$default_attributes = [];

		foreach ( $customer_fields as $field_name => $field_args ) {

			$default_attributes[ $field_name . '-label' ]      = $field_args['label'];
			$default_attributes[ $field_name . '-max-length' ] = isset( $field_args['length'] ) ? $field_args['length'] : '';

			if ( 'message' === $field_name ) {

				$default_attributes[ $field_name . '-rows' ]    = 8;
				$default_attributes[ $field_name . '-columns' ] = 24;

			}

		}

		$attributes = shortcode_atts( $default_attributes, $attributes, 'simple-crm-form' );

		return 'form';

	}

}

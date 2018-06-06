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

		$this->load_assets();

		$attributes = shortcode_atts( $default_attributes, $attributes, 'simple-crm-form' );

		$form_hash = md5( json_encode( $attributes ) );

		delete_transient( 'scrm_attrs_' . $form_hash );

		set_transient( 'scrm_attrs_' . $form_hash, $attributes, DAY_IN_SECONDS );

		return scrm_view( 'forms/customer', compact( 'attributes', 'customer_fields', 'form_hash' ), true );

	}

	/**
	 * @return void
	 */
	public function load_assets() {

		wp_enqueue_style( 'scrm-form', untrailingslashit( SCRM_URI ) . '/assets/dist/css/frontend.css', null, Helpers::assets_version() );

		wp_enqueue_script( 'scrm-form', Helpers::enqueue_path() . 'js/form.js', [ 'jquery' ], Helpers::assets_version(), true );

		wp_localize_script( 'scrm-form', 'scrm_form', [
			'ajax_url' => admin_url( 'admin-ajax.php', is_ssl() ? 'https' : 'http' ),
		] );

	}

}

<?php namespace Simple_CRM;

use WP_Post;

/**
 * Backend logic
 *
 * @package Simple_CRM
 */
class Backend extends Component {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function init() {

		parent::init();

		add_action( 'add_meta_boxes_scrm-customer', [ $this, 'add_customer_meta_box' ] );

		add_action( 'admin_enqueue_scripts', [ $this, 'load_assets' ] );

		add_action( 'save_post_scrm-customer', [ $this, 'save_customer_information' ] );

	}

	/**
	 * @param int $post_id
	 *
	 * @return void
	 */
	public function save_customer_information( $post_id ) {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX && 'submit_customer' === filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING ) ) {

			return;

		}

		$customer_fields = array_keys( $this->plugin->customers->get_fields( 'meta' ) );
		$customer_info   = [];

		foreach ( $customer_fields as $field_name ) {

			$customer_info[ $field_name ] = sanitize_text_field( (string) filter_input( INPUT_POST, 'scrm_field_' . $field_name, FILTER_SANITIZE_STRING ) );

		}

		$this->plugin->customers->save_meta_data( $customer_info, $post_id );

	}

	/**
	 * @return void
	 */
	public function load_assets() {

		if ( 'scrm-customer' === get_current_screen()->post_type ) {

			wp_enqueue_style( 'scrm-admin', untrailingslashit( SCRM_URI ) . '/assets/dist/css/admin.css', null, Helpers::assets_version() );

		}

	}

	/**
	 * @return void
	 */
	public function add_customer_meta_box() {

		add_meta_box( 'scrm-customer-information', __( 'Customer Information', SCRM_DOMAIN ),
			[ $this, 'render_customer_meta_box' ], 'scrm-customer', 'advanced', 'high' );

	}

	/**
	 * @param WP_Post $post
	 *
	 * @return void
	 */
	public function render_customer_meta_box( $post ) {

		$customer_fields = $this->plugin->customers->get_fields( 'meta' );

		foreach ( $customer_fields as $field_name => $field_args ) {

			scrm_view( 'admin/field', compact( 'field_name', 'field_args', 'post' ) );

		}

	}

}

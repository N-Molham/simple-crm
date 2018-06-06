<?php namespace Simple_CRM;

/**
 * AJAX handler
 *
 * @package Simple_CRM
 */
class Ajax_Handler extends Component {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function init() {

		parent::init();

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

			$action = filter_var( isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '', FILTER_SANITIZE_STRING );

			if ( method_exists( $this, $action ) ) {

				add_action( 'wp_ajax_' . $action, [ $this, $action ] );
				add_action( 'wp_ajax_nopriv_' . $action, [ $this, $action ] );
			}

		}

	}

	/**
	 * @return void
	 */
	public function submit_customer() {

		// security check
		check_ajax_referer( 'scrm_submit_customer' );

		$form_hash       = sanitize_key( (string) filter_input( INPUT_POST, '_hash', FILTER_SANITIZE_STRING ) );
		$form_attributes = get_transient( 'scrm_attrs_' . $form_hash );
		$customer_info   = [
			'datetime' => (string) filter_input( INPUT_POST, '_datetime', FILTER_SANITIZE_STRING ),
		];

		if ( empty( $form_attributes ) || empty( $customer_info['datetime'] ) || false === strtotime( $customer_info['datetime'] ) ) {

			$this->error( __( 'Invalid form submission', SCRM_DOMAIN ) );

		}

		$customer_fields = $this->plugin->customers->get_fields();

		foreach ( $customer_fields as $field_name => $field_args ) {

			$customer_info[ $field_name ] = filter_input( INPUT_POST, $field_name, FILTER_SANITIZE_STRING );

			if ( isset( $field_args['sanitize'] ) && is_callable( $field_args['sanitize'] ) ) {

				$customer_info[ $field_name ] = call_user_func( $field_args['sanitize'], $customer_info[ $field_name ] );

			}

			$max_length_attr_name = $field_name . '-max-length';
			$label_attr_name      = $field_name . '-label';

			$field_label = isset( $form_attributes[ $label_attr_name ] ) ? $form_attributes[ $label_attr_name ] : $field_args['label'];
			$max_length  = isset( $form_attributes[ $max_length_attr_name ] ) ? (int) $form_attributes[ $max_length_attr_name ] : $field_args['length'];

			if ( $max_length && strlen( $customer_info[ $field_name ] ) > $max_length ) {

				$this->error( sprintf( __( '%s maximum allow length is %d', SCRM_DOMAIN ), $field_label, $max_length ) );

			}

			if ( isset( $field_args['validate'] ) && is_callable( $field_args['validate'] ) ) {

				$field_validation = call_user_func( $field_args['validate'], $customer_info[ $field_name ], $field_name, $field_args );

				if ( is_wp_error( $field_validation ) ) {

					$this->error( sprintf( __( '%s error: %s', SCRM_DOMAIN ), $field_label, $field_validation->get_error_message() ) );

				}

				if ( false === $field_validation || '' === $field_validation || empty( $field_validation ) ) {

					$this->error( sprintf( __( '%s has invalid value!', SCRM_DOMAIN ), $field_label ) );

				}

			}

		}

		$this->debug( $customer_info );

	}

	/**
	 * AJAX Debug response
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $data
	 *
	 * @return void
	 */
	public function debug( $data ) {

		// return dump
		$this->error( $data );

	}

	/**
	 * AJAX Debug response ( dump )
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $args
	 *
	 * @return void
	 */
	public function dump( $args ) {

		// return dump
		$this->error( print_r( func_num_args() === 1 ? $args : func_get_args(), true ) );

	}

	/**
	 * AJAX Error response
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $data
	 *
	 * @return void
	 */
	public function error( $data ) {

		wp_send_json_error( $data );

	}

	/**
	 * AJAX success response
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $data
	 *
	 * @return void
	 */
	public function success( $data ) {

		wp_send_json_success( $data );

	}

	/**
	 * AJAX JSON Response
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $response
	 *
	 * @return void
	 */
	public function response( $response ) {

		// send response
		wp_send_json( $response );

	}
}

<?php namespace Simple_CRM;

/**
 * Customers logic
 *
 * @package Simple_CRM
 */
class Customers extends Component {

	/**
	 * @var array
	 */
	protected $_fields;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function init() {

		parent::init();

		add_action( 'init', [ $this, 'setups' ] );

	}

	/**
	 * Run initial system setups (custom post type, taxonomies, etc)
	 *
	 * @return void
	 */
	public function setups() {

		// Taxonomies
		$this->register_category_taxonomy();
		$this->register_tag_taxonomy();

		// post type
		$this->register_post_type();

	}

	/**
	 * @return void
	 */
	public function register_post_type() {

		$args = [
			'label'               => __( 'Customer', SCRM_DOMAIN ),
			'labels'              => [
				'name'                  => __( 'Customers', 'Post Type General Name', SCRM_DOMAIN ),
				'singular_name'         => __( 'Customer', 'Post Type Singular Name', SCRM_DOMAIN ),
				'menu_name'             => __( 'Customers', SCRM_DOMAIN ),
				'name_admin_bar'        => __( 'Customer', SCRM_DOMAIN ),
				'archives'              => __( 'Customer Archives', SCRM_DOMAIN ),
				'attributes'            => __( 'Customer Attributes', SCRM_DOMAIN ),
				'parent_item_colon'     => __( 'Parent Customer:', SCRM_DOMAIN ),
				'all_items'             => __( 'All Customers', SCRM_DOMAIN ),
				'add_new_item'          => __( 'Add New Customer', SCRM_DOMAIN ),
				'add_new'               => __( 'Add New', SCRM_DOMAIN ),
				'new_item'              => __( 'New Customer', SCRM_DOMAIN ),
				'edit_item'             => __( 'Edit Customer', SCRM_DOMAIN ),
				'update_item'           => __( 'Update Customer', SCRM_DOMAIN ),
				'view_item'             => __( 'View Customer', SCRM_DOMAIN ),
				'view_items'            => __( 'View Customers', SCRM_DOMAIN ),
				'search_items'          => __( 'Search Customer', SCRM_DOMAIN ),
				'not_found'             => __( 'Not found', SCRM_DOMAIN ),
				'not_found_in_trash'    => __( 'Not found in Trash', SCRM_DOMAIN ),
				'featured_image'        => __( 'Featured Image', SCRM_DOMAIN ),
				'set_featured_image'    => __( 'Set featured image', SCRM_DOMAIN ),
				'remove_featured_image' => __( 'Remove featured image', SCRM_DOMAIN ),
				'use_featured_image'    => __( 'Use as featured image', SCRM_DOMAIN ),
				'insert_into_item'      => __( 'Insert into Customer', SCRM_DOMAIN ),
				'uploaded_to_this_item' => __( 'Uploaded to this Customer', SCRM_DOMAIN ),
				'items_list'            => __( 'Customers list', SCRM_DOMAIN ),
				'items_list_navigation' => __( 'Customers list navigation', SCRM_DOMAIN ),
				'filter_items_list'     => __( 'Filter Customers list', SCRM_DOMAIN ),
			],
			'menu_icon'           => 'dashicons-businessman',
			'supports'            => [ 'title', 'editor' ],
			'taxonomies'          => [ 'scrm-category', 'scrm-tag' ],
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 30,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'hierarchical'        => false,
			'exclude_from_search' => true,
			'show_in_rest'        => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
		];

		register_post_type( 'scrm-customer', $args );

	}

	/**
	 * @param bool $meta_field_only
	 *
	 * @return array
	 */
	public function get_fields( $meta_field_only = false ) {

		if ( null === $this->_fields ) {

			$this->_fields = apply_filters( 'scrm_customer_fields', [
				'name'    => [
					'label'    => __( 'Name', SCRM_DOMAIN ),
					'field'    => 'post_title',
					'length'   => 240,
					'sanitize' => 'sanitize_text_field',
					'type'     => 'text',
				],
				'phone'   => [
					'label'    => __( 'Phone Number', SCRM_DOMAIN ),
					'field'    => '_phone',
					'length'   => 30,
					'sanitize' => [ $this, 'sanitize_phone_number' ],
					'type'     => 'text',
				],
				'email'   => [
					'label'    => __( 'Email Address', SCRM_DOMAIN ),
					'field'    => '_email',
					'sanitize' => 'sanitize_email',
					'validate' => 'is_email',
					'length'   => 200,
					'type'     => 'email',
				],
				'budget'  => [
					'label'    => __( 'Desired Budget', SCRM_DOMAIN ),
					'field'    => '_budget',
					'length'   => 10,
					'sanitize' => 'absint',
					'type'     => 'number',
				],
				'message' => [
					'label'    => __( 'Message', SCRM_DOMAIN ),
					'field'    => 'post_content',
					'length'   => 500,
					'sanitize' => '\Simple_CRM\Helpers::sanitize_text_field_with_linebreaks',
					'type'     => 'textarea',
				],
			] );

		}

		if ( $meta_field_only ) {

			return array_filter( $this->_fields, function ( $field_args ) {

				return 0 === strpos( $field_args['field'], '_' );

			} );

		}

		return $this->_fields;

	}

	/**
	 * @param string $phone_number
	 *
	 * @return string
	 */
	public function sanitize_phone_number( $phone_number ) {

		return preg_replace( '/[^0-9_\-\(\)\+]/', '', $phone_number );

	}

	/**
	 * @return void
	 */
	public function register_tag_taxonomy() {

		$args = [
			'labels'             => [
				'name'              => _x( 'Tags', 'taxonomy general name', SCRM_DOMAIN ),
				'singular_name'     => _x( 'Tag', 'taxonomy singular name', SCRM_DOMAIN ),
				'search_items'      => __( 'Search Tags', SCRM_DOMAIN ),
				'all_items'         => __( 'All Tags', SCRM_DOMAIN ),
				'parent_item'       => __( 'Parent Tag', SCRM_DOMAIN ),
				'parent_item_colon' => __( 'Parent Tag:', SCRM_DOMAIN ),
				'edit_item'         => __( 'Edit Tag', SCRM_DOMAIN ),
				'update_item'       => __( 'Update Tag', SCRM_DOMAIN ),
				'add_new_item'      => __( 'Add New Tag', SCRM_DOMAIN ),
				'new_item_name'     => __( 'New Tag Name', SCRM_DOMAIN ),
				'menu_name'         => __( 'Tag', SCRM_DOMAIN ),
			],
			'hierarchical'       => false,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_in_rest'       => true,
			'show_tagcloud'      => false,
			'show_in_quick_edit' => true,
			'show_admin_column'  => false,
		];

		register_taxonomy( 'scrm-tag', [ 'scrm-customer' ], $args );

	}

	/**
	 * @return void
	 */
	public function register_category_taxonomy() {

		$args = [
			'labels'             => [
				'name'              => _x( 'Categories', 'taxonomy general name', SCRM_DOMAIN ),
				'singular_name'     => _x( 'Category', 'taxonomy singular name', SCRM_DOMAIN ),
				'search_items'      => __( 'Search Categories', SCRM_DOMAIN ),
				'all_items'         => __( 'All Categories', SCRM_DOMAIN ),
				'parent_item'       => __( 'Parent Category', SCRM_DOMAIN ),
				'parent_item_colon' => __( 'Parent Category:', SCRM_DOMAIN ),
				'edit_item'         => __( 'Edit Category', SCRM_DOMAIN ),
				'update_item'       => __( 'Update Category', SCRM_DOMAIN ),
				'add_new_item'      => __( 'Add New Category', SCRM_DOMAIN ),
				'new_item_name'     => __( 'New Category Name', SCRM_DOMAIN ),
				'menu_name'         => __( 'Category', SCRM_DOMAIN ),
			],
			'hierarchical'       => true,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_in_rest'       => true,
			'show_tagcloud'      => false,
			'show_in_quick_edit' => true,
			'show_admin_column'  => false,
		];

		register_taxonomy( 'scrm-category', [ 'scrm-customer' ], $args );

	}

	/**
	 * @param array $customer_info
	 *
	 * @return int|\WP_Error Customer Post ID
	 */
	public function save_customer_information( $customer_info ) {

		$post_attributes = [
			'post_type'   => 'scrm-customer',
			'post_date'   => $customer_info['datetime'],
			'post_status' => 'publish',
		];

		$post_fields = array_filter( $this->get_fields(), function ( $field_args ) {

			return 0 !== strpos( $field_args['field'], '_' );

		} );

		foreach ( $post_fields as $field_name => $field_args ) {

			if ( isset( $customer_info[ $field_name ] ) ) {

				$post_attributes[ $field_args['field'] ] = $customer_info[ $field_name ];

			}

		}

		$post_id = wp_insert_post( $post_attributes, true );

		if ( is_wp_error( $post_id ) ) {

			return $post_id;

		}

		$this->save_meta_data( $customer_info, $post_id );

		return $post_id;

	}

	/**
	 * @param array $info
	 * @param int   $customer_id
	 *
	 * @return void
	 */
	public function save_meta_data( $info, $customer_id ) {

		if ( empty( $customer_id ) || empty( $info ) ) {

			return;

		}

		$fields = $this->get_fields( true );

		foreach ( $info as $info_name => $info_value ) {

			if ( ! isset( $fields[ $info_name ] ) ) {

				continue;

			}

			update_post_meta( $customer_id, $fields[ $info_name ]['field'], $info_value );

		}

	}

}

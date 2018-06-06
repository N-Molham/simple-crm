<?php

/**
 * @param array $classes
 *
 * @return array
 */
$form_classes = (array) apply_filters( 'scrm_form_classes', [ 'simple-crm-form', 'simple-crm-customer' ] );

?>
<form action="" method="post" class="<?php echo esc_attr( implode( ' ', $form_classes ) ); ?>">
	<?php
	foreach ( $customer_fields as $field_name => $field_args ) {

		$input_id = 'scrm-field-' . $field_name;

		// attributes
		$input_label           = $attributes[ $field_name . '-label' ];
		$input_max_length      = $attributes[ $field_name . '-max-length' ];
		$additional_attributes = [
			'required' => 'required',
		];

		if ( 'message' === $field_name ) {

			$additional_attributes['rows'] = $attributes['message-rows'];
			$additional_attributes['cols'] = $attributes['message-columns'];

		}

		$attributes_string = \Simple_CRM\Helpers::parse_attributes( $additional_attributes );

		?>
		<div class="field-wrapper">
			<label for="<?php echo esc_attr( $input_id ); ?>"><?php echo $input_label; ?></label>

			<?php
			switch ( $field_args['type'] ) {

				case 'text':
				case 'number':
				case 'email':
					echo '<p><input type="', esc_attr( $field_args['type'] ), '" id="', esc_attr( $input_id ), '" name="', esc_attr( $field_name ), '" maxlength="', $input_max_length, '" ', $attributes_string, '/></p>';
					break;

				case 'textarea':
					echo '<p><textarea id="', esc_attr( $input_id ), '" name="', esc_attr( $field_name ), '" maxlength="', $input_max_length, '" ', $attributes_string, '></textarea></p>';
					break;

			}
			?>
		</div>
		<?php

	}
	?>
	<div class="form-actions">
		<button type="submit" class="button button-submit" data-loading="<?php esc_attr_e( 'Loading...', SCRM_DOMAIN ) ?>"><?php _e( 'Submit', SCRM_DOMAIN ); ?></button>
	</div>

	<input type="hidden" name="_hash" value="<?php echo esc_attr( $form_hash ); ?>" />
	<input type="hidden" name="_datetime" value="<?php echo current_time( 'mysql' ); ?>" />
	<input type="hidden" name="action" value="submit_customer" />
	<?php wp_nonce_field( 'scrm_submit_customer' ); ?>
</form>

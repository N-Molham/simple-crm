<?php

$input_id    = 'scrm-field-' . $field_name;
$input_name  = 'scrm_field_' . $field_name;
$input_value = $post->{$field_args['field']};

?>
<div class="scrm-field-wrapper">
	<label for="<?php echo esc_attr( $input_id ); ?>"><?php echo $field_args['label']; ?></label>

	<p><input type="text" id="<?php echo esc_attr( $input_id ); ?>" name="<?php echo esc_attr( $input_name ); ?>"
	          class="large-text" value="<?php echo esc_attr( $input_value ); ?>" /></p>
</div>

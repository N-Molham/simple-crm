(function ( w, $, undefined ) {
	$( function () {

		$( 'body' ).on( 'submit', 'form.simple-crm-form', function ( e ) {
			e.preventDefault();

			const $form          = $( e.currentTarget ),
			      $submit_button = $form.find( '.form-actions .button-submit' );

			$submit_button.attr( 'data-submit', $submit_button.text() ).text( $submit_button.attr( 'data-loading' ) ).prop( 'disabled', true );

			$.post( scrm_form.ajax_url, $( this ).serialize(), function ( response ) {

				if ( response.success ) {

					$form.get( 0 ).reset();

				}

				alert( response.data );

			} ).always( function () {

				$submit_button.text( $submit_button.attr( 'data-submit' ) ).prop( 'disabled', false );

			} );

		} );

	} );
})( window, jQuery );
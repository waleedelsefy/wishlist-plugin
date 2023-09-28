/* globals ajaxurl */
jQuery( function ( $ ) {

	$( document ).on( 'click', '.aj-plugin-fw-date-format__option', function () {
		var $t       = $( this ),
			$wrapper = $t.closest( '.aj-plugin-fw-date-format' ),
			$example = $wrapper.find( '.example' );

		$example.text( $t.data( 'preview' ) );
		$wrapper.find( '.aj-date-format-custom' ).val( $t.val() );
	} );

	$( document ).on( 'click input', '.aj-date-format-custom', function () {
		var $t       = $( this ),
			$wrapper = $t.closest( '.aj-plugin-fw-radio__row' );

		$wrapper.find( 'input[type=radio]' ).prop( 'checked', true );
	} );

	$( document ).on( 'input aj-date-format-change', '.aj-date-format-custom', function () {
		var $t       = $( this ),
			$wrapper = $t.closest( '.aj-plugin-fw-date-format' ),
			dataType = $wrapper.data( 'format' ),
			js       = $wrapper.data( 'js' ),
			now      = $wrapper.data( 'current' ),
			example  = $wrapper.find( '.example' ),
			spinner  = $wrapper.find( '.spinner' );

		if ( 'yes' === js ) {
			var newDate = new Date( now );
			newDate = $.datepicker.formatDate( $t.val(), newDate );
			example.text( newDate );
		} else {
			clearTimeout( $.data( this, 'timer' ) );
			$t.data( 'timer', setTimeout(
				function () {
					if ( $t.val() ) {
						spinner.addClass( 'is-active' );
						// Call WP ajax action.
						var data = {
							action: dataType + '_format',
							date  : $t.val()
						};

						$.post( ajaxurl, data, function ( response ) {
							spinner.removeClass( 'is-active' );
							example.text( response );
						} );
					}
				},
				500 )
			);
		}
	} );

	$( document ).on( 'aj-plugin-fw-date-format-init', function () {
		$( '.aj-date-format-custom' ).trigger( 'aj-date-format-change' );
	} ).trigger( 'aj-plugin-fw-date-format-init' );

} );

(function($){
    var license_notice  = $( '#aj-license-notice' );

        $('body').on( 'click', '#aj-license-notice button.notice-dismiss', function(){
            $.ajax({
                type: 'POST',
                url: typeof ajaxurl != 'undefined' ? ajaxurl : aj_ajax.url,
                data: {
                    action:     'aj_license_banner_dismiss',
                    _wpnonce:   license_notice.data( 'nonce' )
                }
            });
        });
})(jQuery);
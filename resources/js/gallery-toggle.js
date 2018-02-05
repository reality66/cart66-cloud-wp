jQuery(document).ready(function($) {

    $('.cc-gallery-thumb-link').on( 'click', function( event ) {
        event.preventDefault();
        var ref = $(this).attr('data-ref');
        $('.cc-gallery-full-image').hide();
        $('#' + ref).show();
    });

    $(document).on( 'change', 'select.cart66-switch-image', function( event ) {
    	var index = $(this).find(':selected').data('index');

    	if ( $('.cc-image-index-' + index)[0] ) {
    		$('.cc-gallery-full-image').hide();
    		$('.cc-image-index-' + index).show();
    	}
    	
    });

    $(document).on( 'change', 'input[type=radio].cart66-switch-image', function( event ) {
    	var index = $(this).data('index');

    	if ( $('.cc-image-index-' + index)[0] ) {
    		$('.cc-gallery-full-image').hide();
    		$('.cc-image-index-' + index).show();
    	}
    	
    });

});

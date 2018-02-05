jQuery(document).ready(function($) {

    $('.cc-gallery-thumb-link').on( 'click', function( event ) {
        event.preventDefault();
        var ref = $(this).data('ref');
        var index = $(this).data('index');

        $('.cc-gallery-full-image').hide();
        $('#' + ref).show();

        // Change product form option
        $('select.cart66-switch-image').find('[data-index="' + index + '"]').attr('selected', 'selected');

        $('input[type=radio].cart66-switch-image[data-index="' + index + '"]').attr('checked', 'checked');

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

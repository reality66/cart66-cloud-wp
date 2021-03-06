jQuery(document).ready(function($) {

    var formfield = null, formfielddeux = null, num ='';

    $('.get-image').on( 'click', function() {
        $('html').addClass('image_spe');
        num = $(this).attr('data-num');
        formfield = $('.id_img[data-num="'+num+'"]').attr('name');
        var id=$("#post_ID").val();
        tb_show('', 'media-upload.php?post_id='+id+'&type=image&TB_iframe=true');
        return false;
    });

    $('.del-image').on('click', function(){
        var cible = $(this).attr('data-num');
        $('.img-preview[data-num="'+cible+'"]').empty();
        $('.id_img[data-num="'+cible+'"]').val('');
    });

    $(document).on('click', '.upload_pdf_button',  function() {
        $('html').addClass('pdf');
        num = $(this).attr('data-cible');
        formfielddeux = $('.url_pdf_input[data-input="'+num+'"]').attr('name');
        var id=$("#post_ID").val();
        tb_show('', 'media-upload.php?post_id='+id+'&type=file&TB_iframe=true');
        return false;
    });

    // user inserts file into post. only run custom if user started process using the above process
    // window.send_to_editor(html) is how wp would normally handle the received data

    window.original_send_to_editor = window.send_to_editor;

    window.send_to_editor = function(html) {
        var fileurl;

        if (formfield !== null) {
            var objStr = JSON.stringify(html, null, 4);
            console.log( 'HTML: ' + objStr );

            var matches = html.match(/wp-image-([0-9]*)/);

            $('input[name="' + formfield + '"]').val(matches[1]);
      
            // var imgfull = $(html).first('img').css( { "width":"100px", "height":"100px"} );

            // Find the image
            var imgfull;
            if ( ! $(html).is("img") ) {
                // This one works
                console.log( "Not the image tag itself." );
                imgfull = $(html).find('img:first');
            }
            else {
                console.log( "This is the actual image tag");
                imgfull = $(html);
            }

            // Set the image size to 100x100
            imgfull.css( { "width":"100px", "height":"100px"} );

            $('.img-preview[data-num="'+num+'"]').append( $(imgfull) );

            tb_remove();

            $('html').removeClass('image_spe');

            formfield = null;
            num = null;
        } 
        else {
            if(formfielddeux !== null) {
                fileurl = $(html).filter('a').attr('href');

                $('.url_pdf_input[data-input="'+num+'"]').val(fileurl);

                tb_remove();

                $('html').removeClass('pdf');
                formfielddeux = null;
                num = null;
            } 
            else {
                window.original_send_to_editor(html);
            }
        }
    }

});

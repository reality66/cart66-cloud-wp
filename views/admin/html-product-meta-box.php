<?php 
    wp_nonce_field( 'cc_product_meta_box', 'cc_product_meta_box_nonce' ); 
?>

<script langage="text/javascript">
    jQuery(document).ready(function($) {

        $('#_cc_product_json').select2({
            width: '100%',
            minimumInputLength: 2,
            allowClear: true,
            ajax: {
                url: ajaxurl,
                dataType: 'json',
                data: function (term, page) {
                    return {
                        action: 'cc_ajax_product_search',
                        search: term
                    };
                },
                results: function (data, page) {
                  return { results: data };
                }
            }
        });


    });
</script>

<input type="hidden" name="_cc_product_json" id="_cc_product_json" data-placeholder="<?php echo $value; ?>" />

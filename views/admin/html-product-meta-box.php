<style type="text/css">
.cc_meta_box_option {
  margin-bottom: 15px;
}
</style>

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

<div class="cc_meta_box_option">
    <input type="hidden" name="_cc_product_json" id="_cc_product_json" data-placeholder="<?php echo $value; ?>" />
</div>

<div class="cc_meta_box_option">
    <select name="_cc_product_layout" id="_cc_product_layout">
        <option value="basic" <?php echo ( empty( $layout ) ||  $layout == 'basic' ) ? 'selected="selected"' : ''; ?>>
            Show gallery and product form
        </option>
        <option value="custom" <?php echo ( $layout == 'custom' ) ? 'selected="selected"' : ''; ?>>
            Custom layout
        </option>
    </select>
</div>

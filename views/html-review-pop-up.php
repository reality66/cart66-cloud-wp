<style type="text/css">
    .select2-drop-mask {
        z-index: 2000001;
    }
    .select2-drop {
        z-index: 2000002;
    }
</style>

<script type="text/javascript">
    function cc_insert_review_shortcode(){
        var product_info = JSON.parse(jQuery('#cc_product_id_review').val());
        var display_type = jQuery("#display_type_review").val();

        if(product_info.length == 0 || product_info == "0" || product_info == ""){
            alert("<?php _e("Please select a product", "cart66") ?>");
            return;
        }
        console.log(product_info);

        if ( display_type == 'display' ) {
            window.send_to_editor("[cc_product_reviews sku=\"" + product_info.sku + "\"]");
        }
        else {
            window.send_to_editor("[cc_product_review_form sku=\"" + product_info.sku + "\"]");
        }
    }

    jQuery(document).ready(function($) {
        $('#cc_product_id_review').select2({
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

<div id="cc_review_pop_up" style="display:none;">
    <div id="cart66_revew_pop_up" class="wrap">
        <div>
            <div style="padding:15px 15px 0 15px;">
                <h3 style="color:#5A5A5A!important; font-family:Georgia,Times New Roman,Times,serif!important; font-size:1.8em!important; font-weight:normal!important;"><?php _e("Display and collect product reviews", "cart66"); ?></h3>
                <span><?php _e("Select a product below.", "cart66"); ?></span>
            </div>

            <div style="padding:15px 15px 0 15px;">
                <table class="form-table">
                    <tbody>
                        <tr valign="top">
                            <th scope="row"><label for="cc_product_id_review">Product</label></th>
                            <td>
                                <input type="hidden" name="cc_product_id_review" id="cc_product_id_review" value="" />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><label for="display_type">I want to:</label></th>
                            <td>
                                <select name="display_type_review" id="display_type_review">
                                    <option value="display">Display Reviews</option>
                                    <option value="collect">Collect Reviews</option>
                                </select>
                                <p class="description"><?php _e('Insert a shortcode to either <strong>display reviews</strong> that have already been submitted or show the form to <strong>collect reviews</strong> for a product.', 'cart66'); ?></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style="padding:15px;">
                <input type="button" class="button-primary" value="Insert Shortcode" onclick="cc_insert_review_shortcode();"/>
                &nbsp;&nbsp;&nbsp;
                <a class="button" style="color:#bbb;" href="#" onclick="tb_remove(); return false;"><?php _e("Cancel", "cart66"); ?></a>
            </div>
        </div>
    </div>
</div>


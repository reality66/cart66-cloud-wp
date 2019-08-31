<style type="text/css">
    .choices .choices__inner {
        min-height: auto;
        padding-right: 0;
        width: 90%;
    }

    .choices .choices__list--single {
        width: 90%;
    }

    .choices .choices__item--selectable {
        width: 90%;
    }

    .choices[data-type*=select-one]:after {
        right: 35px;
    }

    .choices .choices__list--dropdown {
        width: 93%;
    }
</style>

<script type="text/javascript">
    function cc_insert_product_shortcode(){
        var product_sku = jQuery('#cc_product_sku').val();
        var display_type = jQuery("#display_type").val();
        var display_quantity = jQuery("#display_quantity").is(":checked") ? 'true' : 'false';
        var display_price = jQuery("#display_price").is(":checked") ? 'true' : 'false';

        if(product_sku.length == 0 || product_sku == "0" || product_sku == ""){
            alert("<?php _e("Please select a product", "cart66") ?>");
            return;
        }
        console.log(product_sku);
        window.send_to_editor("[cc_product sku=\"" + product_sku + "\" display=\"" + display_type + "\" quantity=\"" + display_quantity + "\" price=\"" + display_price + "\"]");
    }

</script>

<?php
    $cloud_product = new CC_Cloud_Product();
    $cc_products = $cloud_product->get_products();
?>

<div id="cc_editor_pop_up" style="display:none;">
    <div id="cart66_pop_up" class="wrap">
        <div>
            <div style="padding:15px 15px 0 15px;">
                <h3 style="color:#5A5A5A!important; font-family:Georgia,Times New Roman,Times,serif!important; font-size:1.8em!important; font-weight:normal!important;"><?php _e("Insert A Product", "cart66"); ?></h3>
                <span><?php _e("Select a product below to add it to your post or page.", "cart66"); ?></span>
            </div>

            <div style="padding:15px 15px 0 15px;">
                <table class="form-table">
                    <tbody>
                        <tr valign="top">
                            <th scope="row"><label for="cc_product_sku">Products</label></th>
                            <td>
                                <select name="cc_product_sku" id="cc_product_sku" class="c66-choices">
                                    <option placeholder value="">Choose Product</option>
                                    <?php foreach ($cc_products as $cc_product): ?>
                                        <option value="<?php echo $cc_product["sku"] ?>">
                                            <?php echo $cc_product["name"] ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><label for="display_type">Display mode</label></th>
                            <td>
                                <select name="display_type" id="display_type">
                                    <option value="inline">inline</option>
                                    <option value="vertical">vertical</option>
                                    <option value="horizontal">horizontal</option>
                                    <option value="naked">naked</option>
                                </select>
                                <p class="description"><?php _e('If the product has no options, we recommend choosing the "inline" display mode.', 'cart66'); ?></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><label for="display_type">Show quantity field</label></th>
                            <td>
                                <input type="checkbox" id="display_quantity" checked='checked' /> <label for="display_quantity"><?php _e("Yes", "cart66"); ?></label>
                                &nbsp;&nbsp;&nbsp;
                                <p class="description"><?php _e('Allow the buyer to set the quanity when adding to cart', 'cart66'); ?></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><label for="display_type">Show product price</label></th>
                            <td>
                                <input type="checkbox" id="display_price" checked='checked' /> <label for="display_price"><?php _e("Yes", "cart66"); ?></label>
                                &nbsp;&nbsp;&nbsp;
                                <p class="description"><?php _e('Do you want to show the price of the product?', 'cart66'); ?></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style="padding:15px;">
                <input type="button" class="button-primary" value="Insert Product" onclick="cc_insert_product_shortcode();"/>
                &nbsp;&nbsp;&nbsp;
                <a class="button" style="color:#bbb;" href="#" onclick="tb_remove(); return false;"><?php _e("Cancel", "cart66"); ?></a>
            </div>
        </div>
    </div>
</div>

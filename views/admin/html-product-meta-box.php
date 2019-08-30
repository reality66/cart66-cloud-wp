<style type="text/css">
.cc_meta_box_option {
  margin-bottom: 15px;
}
</style>

<?php 
    wp_nonce_field( 'cc_product_meta_box', 'cc_product_meta_box_nonce' ); 
?>

<style type="text/css">
    .c66-choices .choices__inner {
        min-height: auto;
        padding-right: 0;
        width: 90%;
    }

    .c66-choices .choices__list--single {
        width: 90%;
    }

    .c66-choices .choices__item--selectable {
        width: 90%;
    }

    .c66-choices[data-type*=select-one]:after {
        right: 35px;
    }

    .c66-choices .choices__list--dropdown {
        width: 93%;
    }
</style>

<div class="cc_meta_box_option">
    <select name="_cc_product_sku" id="_cc_product_sku" class="c66-choices">
        <option placeholder value="">Choose Product</option>
        <?php foreach ($cc_products as $cc_product): ?>
            <?php if ($cc_product["sku"] == $selected_product_sku): ?>
                <option value="<?php echo $cc_product["sku"] ?>" selected>
                    <?php echo $cc_product["name"] ?>
                </option>
            <?php else: ?>
                <option value="<?php echo $cc_product["sku"] ?>">
                    <?php echo $cc_product["name"] ?>
                </option>
            <?php endif ?>
        <?php endforeach ?>
    </select>
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

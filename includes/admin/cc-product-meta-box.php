<?php

add_action( 'load-post.php',     'cc_product_meta_box_setup' );
add_action( 'load-post-new.php', 'cc_product_meta_box_setup' );

function cc_product_meta_box_setup() {
    add_action( 'add_meta_boxes', 'cc_add_product_meta_box' );
    add_action( 'save_post', 'cc_save_product_meta_box', 10, 2 );
}

function cc_add_product_meta_box() {
    add_meta_box(
        'cart66-product-box',             // unique id assigned to the meta box
        __( 'Cart66 Product', 'cart66' ), // title for metabox
        'cc_product_meta_box_render',     // callback to display the output for the meta box
        'cc_product',                        // the name of the post type on which to display the meta box
        'side',                           // where on the page to display the meta box (normal, side, advanced)
        'default'                         // priority (default, core, high, low)
    );
}

/**
 * Render the output for the cart66 product meta box on the product post type
 *
 * This function should echo the content
 */
function cc_product_meta_box_render( $post, $box ) {

    $selected_product_sku = get_post_meta( $post->ID, '_cc_product_sku', true );
    $layout = get_post_meta( $post->ID, '_cc_product_layout', true );
    $cloud_product = new CC_Cloud_Product();
    $cc_products = $cloud_product->get_products();

    $data = array( 
        'post' => $post, 
        'box' => $box,
        'selected_product_sku' => $selected_product_sku,
        'layout' => $layout,
        'cc_products' => $cc_products
    );

    $template = CC_PATH . 'views/admin/html-product-meta-box.php';
    $view = CC_View::get( $template, $data );
    echo $view;
}

/**
 * Save the product id associated with this product post
 */
function cc_save_product_meta_box( $post_id, $post ) {

    /* Verify the nonce before proceeding. */
    if ( !isset( $_POST['cc_product_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['cc_product_meta_box_nonce'], 'cc_product_meta_box' ) ) {
        return $post_id;
    }

    /* Get the post type object. */
    $post_type = get_post_type_object( $post->post_type );

    /* Check if the current user has permission to edit the post. */
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
        return $post_id;

    /* Store the meta key value in the wp_postmeta table */
    cc_store_meta_box_values( $post_id );
}

/**
 * Function to add, update, or delete post meta
 *
 * Note that the given $meta_key is both the HTML form field name and the 
 * meta_key in the wp_postmeta table
 * 
 * If a new product is selected, the submitted value will be in the format:
 * cloud_id~~product_name
 *
 * If the stored value is being displayed, the submitted value is empty
 *
 * @param int $post_id
 * @param string $meta_key
 */
function cc_store_meta_box_values( $post_id ) {
    $json_key = '_cc_product_json';
    $prefix   = '_cc_product_';

    if ( isset( $_POST[ '_cc_product_sku' ] ) && strlen( $_POST[ '_cc_product_sku' ] ) > 0 ) {
        $cc_product = CC_Cloud_Product::find_by_sku($_POST[ '_cc_product_sku' ]);
        CC_Log::write( print_r($cc_product, true) );
    }

    if ( is_array( $cc_product ) ) {

        // Get the meta value of the custom field key.
        $old_value = get_post_meta( $post_id, $json_key, true );

        if ( '' == $old_value ) {
            // If a new meta value was added and there was no previous value, add it.
            add_post_meta( $post_id, $json_key, $cc_product, true );
            foreach( $cc_product as $key => $value ) {
                add_post_meta( $post_id, $prefix . $key, $value, true );
            }
        } elseif ( $cc_product != $old_value ) {
            // If the new meta value does not match the old value, update it.
            update_post_meta( $post_id, $json_key, $cc_product );
            foreach( $cc_product as $key => $value ) {
                update_post_meta( $post_id, $prefix . $key, $value );
            }
        } elseif ( '' == $cc_product && $old_value ) {
            // TODO: $product_info will never be empty here because in order to get here it has to be an array
            // If there is no new meta value but an old value exists, delete it.
            delete_post_meta( $post_id, $json_key );
        }
        else {
            CC_Log::write( "Totally skipping saving meta data for a reason currently unknown to me." );
        }

    }

    // Save the product layout value
    if ( ! empty( $_POST['_cc_product_layout'] ) ) {
        $layout = sanitize_text_field( $_POST['_cc_product_layout'] );
        update_post_meta( $post_id, '_cc_product_layout', $layout );
    }

}

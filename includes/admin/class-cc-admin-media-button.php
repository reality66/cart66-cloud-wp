<?php

class CC_Admin_Media_Button {

    public static function add_media_button( $context ) {

        $style = <<<EOL
<style type="text/css">
    #cart66-review-button-icon,
    #cart66-menu-button-icon {
        padding: 4px 0px;
        font-size: 1.3em;
        color: #888;
    }
</style>

EOL;

        // Add button for inserting product shortcodes
        $product_title = __( 'Shortcode to show product add to cart form', 'cart66' );
        $button = '<a id="cc_product_shortcodes" href="#TB_inline?width=480&height=600&inlineId=cc_editor_pop_up" class="button thickbox" title="' . $product_title . '">';
        $button .= '<span id="cart66-menu-button-icon" class="dashicons dashicons-cart">  </span>';
        $button .= 'Cart66 Product';
        $button .= '</a>';

        // Add button for inserting review shortcodes
        $review_title = __( 'Shortcodes to display and collect product reviews ', 'cart66' );
        $button .= '<a id="cc_product_review_shortcodes" href="#TB_inline?width=480&height=600&inlineId=cc_review_pop_up" class="button thickbox" title="' . $review_title . '">';
        $button .= '<span id="cart66-review-button-icon" class="dashicons dashicons-admin-comments">  </span>';
        $button .= 'Cart66 Review';
        $button .= '</a>';

        $out = $style . $button;
        echo $out;
    }

    public static function add_media_button_popup() {
        // Get view for managing products
        $view = CC_View::get(CC_PATH . 'views/html-editor-pop-up.php');
        
        // Get view for managing reviews
        $view .= CC_View::get(CC_PATH . 'views/html-review-pop-up.php');

        echo $view;
    }

}

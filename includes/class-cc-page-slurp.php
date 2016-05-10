<?php

class CC_Page_Slurp {

    public static function check_slurp() {
        global $post, $wp, $wp_query;

        $is_slurp = false;

        if ( is_object( $post ) && self::slurp_page_id() == $post->ID ) {
            CC_Log::write( 'Slurp with permalinks ON: Setting up filters to load content into slurped page' );
            $is_slurp = true;
        } elseif ( isset( $wp->query_vars['page_id'] ) &&  $wp->query_vars['page_id'] == 'page-slurp-template' ) {
            CC_Log::write( 'Slurp with permalinks OFF: Setting up filters to load content into slurped page' );
            unset( $wp->query_vars['page_id'] );

            $args = array(
                'page_id' => self::slurp_page_id()
            );
            $wp_query = new WP_Query( $args );
            CC_Log::write( 'WP Query: ' . print_r( $wp_query, true ) );

            $is_slurp = true;
        }

        if ( $is_slurp ) {
            CC_Log::write( 'This is a page slurp! Setting filters: the_title' );
            add_filter( 'the_title', 'CC_Page_Slurp::set_page_heading' );
            add_filter( 'document_title_parts', 'CC_Page_Slurp::set_page_title', 30, 2 );
            add_filter( 'wp_title', 'CC_Page_Slurp::set_page_title', 30, 2 );
            self::check_receipt();
        }
    }

    /**
     * Return the id of the page slurp template.
     *
     * If the page slurp template cannot be found, return false.
     *
     * @return mixed int or false
     */
    public static function slurp_page_id() {
        $page_id = false;
        $page = get_page_by_path('page-slurp-template');

        if( is_null( $page ) ) {
            $page = get_page_by_path('cart66_title');
        }

        if ( is_object( $page ) && $page->ID > 0 ) {
            $page_id = $page->ID;
        }

        return $page_id;
    }


    public static function set_page_title( $title ) {
        CC_Log::write( 'Called set_page_title with input: '  . print_r( $title, true ) );

        if ( is_array( $title ) ) {
            $original_title = $title['title'];
        }
        else {
            $original_title = $title;
        }

        if( false !== strpos( $original_title, '{{cart66_title}}' ) ) {
            $title_value = cc_get( 'cc_page_title', 'text_field' );
            $new_title = ucwords( str_replace('{{cart66_title}}', $original_title , $title_value) );
            CC_Log::write( 'set_page_title: Slurp title changed: ' . $new_title );
        }
        else {
            CC_Log::write( 'set_page_title: Not setting slurp page title because the token is not in the title: ' . print_r( $title, true ) );
        }

        if ( is_array( $title ) ) {
            $title['title'] = $new_title;
        }
        else {
            $title = $new_title;
        }

        CC_Log::write( 'set_page_title result: ' . print_r( $title, true ) );

        return $title;
    }

    public static function set_page_heading( $content ) {

        if( false !== strpos( $content, '{{cart66_title}}' ) ) {
            if ( isset( $_GET['cc_page_name'] ) ) {
                CC_Log::write( 'CC_Page_Slurp: Set page heading with original content: ' . $content );
                $content = ucwords( str_replace('{{cart66_title}}', $_GET['cc_page_name'], $content) );
            }
        }

        return $content;
    }

    public static function check_receipt() {
        CC_Log::write( 'Checking if this is the receipt page' );

        // Drop the cart key cookie if the receipt page is requested
        if( isset( $_GET['cc_order_id'] ) && isset( $_GET['cc_page_name'] ) && strtolower( $_GET['cc_page_name'] ) == 'receipt' ) {
            CC_Log::write("Receipt page requested - preparing to drop the cart");
            CC_Cart::drop_cart();

            CC_Log::write( 'Add filter: the_content' );
            add_filter( 'the_content', array( 'CC_Page_Slurp', 'load_receipt' ) );
        }
        else {
            CC_Log::write( 'This is not the receipt page: ' . print_r( $_GET, true ) );
        }
    }

    public static function load_receipt( $content ) {
        $order_id = '';
        $receipt = '';

        CC_Log::write( 'Trying to load receipt from the cloud' );

        if ( isset( $_GET['cc_order_id'] ) ) {
            $order_id = $_GET['cc_order_id'];
            try {
                $receipt = CC_Cloud_Receipt::get_receipt_content( $order_id );
                do_action('cc_load_receipt', $order_id);
                $content = apply_filters( 'cc_receipt_content', $content );
            }
            catch(CC_Exception_Store_ReceiptNotFound $e) {
                CC_Log::write( 'Unable to load receipt because the receipt could not be found in the cloud' );
                $receipt = '<p>Unable to find receipt for the given order number.</p>';
            }
        }
        else {
            CC_Log::write( 'Unable to load receipt because cc_order_id is not set' );
            $receipt = '<p>Unable to find receipt because the order number was not provided.</p>';
        }

        $content = str_replace('{{cart66_content}}', $receipt, $content);

        return $content;
    }

    public static function hide_page_slurp( $pages ) {
        $page_slurp_id = self::slurp_page_id();

        CC_Log::write ( "Hiding page slurp page from navigation with page id: $page_slurp_id" );

        if( $page_slurp_id ) {
            foreach ( $pages as $index => $page ) {
                if( $page->ID == $page_slurp_id ) {
                    unset( $pages[$index] );
                }
            }
        }

        return $pages;
    }

    /**
     * Creates physical page slurp template page and returns the page id.
     *
     * If the page could not be created, return 0.
     *
     * @return int
     */
    public static function create_slurp_page() {
        $page_slurp_id = self::slurp_page_id();

        if ( ! $page_slurp_id ) {
            $page = array(
                'post_title' => '{{cart66_title}}',
                'post_content' => '{{cart66_content}}',
                'post_name' => 'page-slurp-template',
                'post_parent' => 0,
                'post_status' => 'publish',
                'post_type' => 'page',
                'comment_status' => 'closed',
                'ping_status' => 'closed'
            );
            $page_slurp_id = wp_insert_post( $page );
            CC_Log::write("Created page slurp template page with ID: $page_slurp_id");
        }
        else {
            $page = array(
                'ID' => $page_slurp_id,
                'post_title' => '{{cart66_title}}',
                'post_name' => 'page-slurp-template',
                'post_status' => 'publish',
                'post_type' => 'page',
                'comment_status' => 'closed',
                'ping_status' => 'closed'
            );
            wp_update_post($page);
            CC_Log::write("Updating an existing post for the page slurp template page: $page_slurp_id");
        }

        return $page_slurp_id;
    }

    public static function set_query_to_slurp( $wp_query ) {
        if ( $wp_query->is_main_query() ) {
            $slurp_id = self::slurp_page_id();
            CC_Log::write( "Setting query to use slurp ID: $slurp_id" );
            $wp_query->set( 'post_type', 'page' );
            $wp_query->set( 'page_id', $slurp_id );
        }
    }

    /**
     * Render receipt page when called from API endpoint
     */
    public function show_receipt( $order_id ) {
        $receipt = CC_Cloud_Receipt::get_receipt_content( $order_id );
        do_action('cc_load_receipt', $order_id);
        $content = str_replace('{{cart66_content}}', $receipt, $content);
    }

}

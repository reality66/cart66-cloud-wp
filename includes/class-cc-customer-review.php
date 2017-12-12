<?php

/**
 * Regsiter post type: cc_customer_review
 */
class CC_Customer_Review {

    protected static $instance;
    protected static $slugs;

    /**
     * Kick things off by calling this function.
     */
    public static function init() {
        $instance = self::get_instance();
        
        return $instance;
    }

    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
            self::$instance->create_post_type();
            self::$instance->manage_custom_columns();
            self::$instance->custom_bulk_actions();
        }

        return self::$instance;
    }

    public function custom_bulk_actions() {
        add_filter( 'current_screen', function( $screen ) {
            CC_Log::write( 'Current screen: ' . print_r( $screen, true ) );
        } );

        add_filter('bulk_actions-edit-cc_customer_review', function ( $actions ) {
            $actions['approve_reviews'] = 'Approve';
            $actions['deny_reviews'] = 'Deny';
            $actions['pend_reviews'] = 'Pending';

            return $actions;
        } );

        add_filter('handle_bulk_actions-edit-cc_customer_review', 
            function ( $redirect_to, $do_action, $post_ids ) {
                CC_Log::write( "Redirect to: $redirect_to :: Do Action: $do_action :: Post IDs: " . print_r( $post_ids, true ) );

                foreach ( $post_ids as $post_id ) {
                    $status = false;

                    switch ( $do_action ) {
                        case 'approve_reviews':
                            $status = 'approved';
                            break;
                        case 'deny_reviews':
                            $status = 'denied';
                            break;
                        case 'pend_reviews':
                            $status = 'pending';
                            break;
                    }

                    if ( $status ) {
                        update_post_meta( $post_id, 'review_details_status', $status );
                    }
                }
                
                return $redirect_to;
            },
            10,
            3
        );
    }

    public function manage_custom_columns() {
        add_filter( 'manage_cc_customer_review_posts_columns', function( $columns ) {
            unset( $columns['date'] );

            $columns['name'] = __('Name', 'cart66');
            $columns['sku'] = __('SKU', 'cart66');
            $columns['email'] = __('Email', 'cart66');
            $columns['rating'] = __('Rating', 'cart66');
            $columns['status'] = __('Status', 'cart66');

            return $columns;
        });

        add_action( 
            'manage_cc_customer_review_posts_custom_column',
            function( $column, $post_id ) {
                $value = get_post_meta( $post_id, 'review_details_' . $column, true );
                echo $value;
            },
            10,
            2
        );
    }

    public function create_post_type() {

        $labels = array(
            'name'               => __( 'Reviews', 'cart66' ),
            'singular_name'      => __( 'Review', 'cart66' ),
            'menu_name'          => __( 'Reviews', 'cart66' ),
            'all_items'          => __( 'All Reviews', 'cart66' ),
            'view_item'          => __( 'View Review', 'cart66' ),
            'add_new_item'       => __( 'Add New Review', 'cart66' ),
            'add_new'            => __( 'Add New', 'cart66' ),
            'edit_item'          => __( 'Edit Review', 'cart66' ),
            'update_item'        => __( 'Update Review', 'cart66' ),
            'search_items'       => __( 'Search Reviews', 'cart66' ),
            'not_found'          => __( 'Not Found', 'cart66' ),
            'not_found_in_trash' => __( 'Not found in Trash', 'cart66' ),
        );

        $options = array (
            'description' => 'Cart66 Customer Reviews',
            'labels' => $labels,
            'supports' => array('title', 'editor'),
            'hierarchcical' => false,
            'capability_type' => 'post',
            'public' => true,
            'publicly_queryable' => false,
            'has_archive' => false,
            'rewrite' => array( 'slug' => 'reviews' ),
            'menu_position' => 30,
            'menu_icon' => 'dashicons-megaphone',
            'show_in_menu' => true
        );

        register_post_type( 'cc_customer_review', $options );

        // Register customer review meta box
        new CC_Customer_Review_Meta_Box();
    }

}

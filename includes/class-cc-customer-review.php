<?php

/**
 * Regsiter post type: cc_customer_reviews
 */
class CC_Customer_Review {

    protected static $instance;
    protected static $slugs;

    /**
     * Kick things off by calling this function.
     */
    public static function init() {
        $instance = self::get_instance();
        $instance->create_post_type();

        return $instance;
    }

    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
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

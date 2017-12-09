<?php

class CC_Product_Review extends CC_Model {

    public function __construct() {
        $data = [
            'status' => '',
            'rating' => '',
            'name' => '',
            'email' => '',
            'sku' => '',
            'title' => '',
            'content' => '',
            'date' => ''
        ];

        parent::__construct( $data );
    }

    public function load( $post_id ) {
        $post = get_post( $post_id );

        $this->status = get_post_meta( $post_id, 'review_details_status', true );
        $this->rating = get_post_meta( $post_id, 'review_details_rating', true );
        $this->name = get_post_meta( $post_id, 'review_details_name', true );
        $this->email = get_post_meta( $post_id, 'review_details_email', true );
        $this->sku = get_post_meta( $post_id, 'review_details_sku', true );

        $this->title = $post->post_title;
        $this->content = $post->post_content;
        $this->date = $post->post_date;
    }

    public function ajax_save_review() {
        $review = $_REQUEST['review'];

        $post_data = [
            'post_type' => 'cc_customer_review',
            'post_content' => $review['content'],
            'post_title' => $review['title'],
            'post_status' => 'publish',
            'meta_input' => [
                'review_details_status' => 'pending',
                'review_details_rating' => $review['rating'],
                'review_details_name' => $review['name'],
                'review_details_email' => $review['email'],
                'review_details_sku' => $review['sku'],
            ]
        ];

        $post_id = wp_insert_post( $post_data );

        CC_Log::write( "Created new customer review post: $post_id" );
        echo $post_id;

        die();
    }

}

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

}

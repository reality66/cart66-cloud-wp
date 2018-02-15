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

    public static function ajax_save_review() {

        // Validate the reCAPTCHA if it is enabeled before saving the reivew
        $site_key = CC_Admin_Setting::get_option( 'cart66_recaptcha_settings', 'site_key', false );

        if ( $site_key ) {
            $response = $_REQUEST['g-recaptcha-response'];
            $valid = cc_validate_recaptcha_response( $response );

            if ( ! $valid ) {
                // Unable to process the request because the reCAPTCHA validation failed
                status_header('422');
                exit();
            }
        }

        // Save the review if the reCAPTHCA validation passes
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

        if ( $post_id ) {
            self::send_email_notification( $review );
        }

        CC_Log::write( "Created new customer review post: $post_id" );
        echo $post_id;

        die();
    }

    public static function send_email_notification( $review ) {

        $recipients = CC_Admin_Setting::get_option('cart66_review_settings', 'notify_emails', false);

        $from = get_bloginfo('admin_email');
        $subject = 'New Product Review';
        $body = 'Rating: ' . $review['rating'] . "\nName: " . $review['name'];
        $body .= "\nEmail: " . $review['email'] . "\nSKU: " . $review['sku'];
        $body .= "\n\n" . $review['title'] ."\n\n" . $review['content'];

        $headers = ['From: ' . $review['name'] . "<$from>"];

        $recipients = explode( ',', $recipients );

        foreach ( $recipients as $to ) {
            $to = trim( $to );
            wp_mail( $to, $subject, $body, $headers );
        }
    }

}

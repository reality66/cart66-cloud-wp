<?php

class CC_Admin_Review_Settings extends CC_Admin_Setting {

    public static function init() {
        $page = 'cart66_review';
        $option_group = 'cart66_review_settings';
        $setting = new CC_Admin_Review_Settings( $page, $option_group );

        return $setting;
    }

    /**
     * Allow other add-ons to add settings sections to the cart66 main settings page
     */
    public function add_settings_sections() {
        $this->sections = apply_filters( 'cart66_review_settings_sections', $this->sections );
        parent::add_settings_sections();
    }

    /**
     * Register cart66_review_settings
     */
    public function register_settings() {
        
        /*****************************************************
         * Customer Review Options
         *****************************************************/
        
        // Create section for theme content wrappers
        $option_values = CC_Admin_Setting::get_options( 
            'cart66_review_settings', 
            [
                'show_reviews' => 'approved',
                'review_thank_you' => 'Thank you for submitting your review.',
                'notify_emails' => ''
            ] 
        );

        CC_Log::write( 'Reivew option values array: ' . print_r( $option_values, true ) );

        // Create a section for customer review options
        $reviews_section = new CC_Admin_Settings_Section( __( 'Customer Review Settings', 'cart66' ), 'cart66_review_settings' );

        // Add option for choosing review status to display by default
        $show_reviews = new CC_Admin_Settings_Radio_Buttons( __( 'Show Reviews', 'cart66' ), 'show_reviews' );
        $show_reviews->new_option( __( 'Only Approved Reviews', 'cart66' ), 'approved', true );
        $show_reviews->new_option( __( 'Both Pending and Approved', 'cart66' ), 'pending_approved', false );
        $show_reviews->description .= __( 'If you want to have all reviews shown as soon as they are submitted, select <em>Both Pending and Approved</em>. ', 'cart66');
        $show_reviews->description .= __( 'Denied reviews will never be shown.', 'cart66');
        $show_reviews->set_selected( $option_values['show_reviews'] );
        $reviews_section->add_field( $show_reviews );

        // Add review thank you message textarea
        $review_thank_you_msg = $option_values[ 'review_thank_you' ];
        $review_thanks = new CC_Admin_Settings_Text_Area( __('Thank you message', 'cart66'), 'review_thank_you', $review_thank_you_msg );
        $review_thanks->description = __( 'The message shown to the customer after new a review is submitted.', 'cart66' );
        $reviews_section->add_field( $review_thanks );

        // Add email notification box
        $email = $option_values[ 'notify_emails' ];
        $emails = new CC_Admin_Settings_Text_Field( __('Email Notifications', 'cart66'), 'notify_emails', $email );
        $emails->description = __( 'Get an email when a new review is submitted. <br>Leave blank if you do not want to receive an email notification.<br>You can list multiple email address separated by commas like this:<br>one@email.com, two@email.com', 'cart66' );
        $reviews_section->add_field( $emails );

        // Add Post Type section to the main settings
        $this->add_section( $reviews_section );

        /*****************************************************
         * reCAPTCHA Options
         *****************************************************/

        // Create section for reCaptcha settings
        $option_values = CC_Admin_Setting::get_options(
            'cart66_recaptcha_settings',
            [
               'site_key' => '',
               'secret_key' => '' 
            ]
        );

        $recaptcha_section = new CC_Admin_Settings_Section( __( 'reCAPTCHA Settings', 'cart66' ), 'cart66_recaptcha_settings' );
        $recaptcha_section->description = __('Cart66 integrates with <a href="https://www.google.com/recaptcha/intro/">reCAPTCHA</a>, a service powered by Google to block spam.', 'cart66');
        $recaptcha_section->description .= __(' These settings are only required if you want to use this feature.', 'cart66' ); 
        $recaptcha_section->description .= __('<br>We highly recommend enabling this feature to keep spam out of your reviews.', 'cart66' ); 
        $recaptcha_section->description .= __('<br>You can <a target="_blank" href="https://www.google.com/recaptcha/">get your <strong>Site Key</strong> and <strong>Secret Key</strong> here</a>. reCAPTCHA is a free service. ', 'cart66' ); 
        $recaptcha_section->description .= __('<br>Choose <strong>reCAPTCHA V2</strong> as the type of reCAPTCHA for your site.', 'cart66' ); 

        $site_key = new CC_Admin_Settings_Text_Field( __( 'Site Key', 'cart66' ), 'site_key', $option_values['site_key'] );
        $recaptcha_section->add_field( $site_key );

        $site_key = new CC_Admin_Settings_Text_Field( __( 'Secret Key', 'cart66' ), 'secret_key', $option_values['secret_key'] );
        $recaptcha_section->add_field( $site_key );
       
        $this->add_section( $recaptcha_section );

        // Register all of the settings
        $this->register();
    }

    public function render_section() {
        _e( 'Connect your WordPress site to your secure Cart66 Cloud account', 'cart66' );
    }

    public function sanitize( $options ) {
        /*
        $clean = true;
        CC_Log::write( '########## SANITZE OPTIONS FOR MAIN SETTINGS :: ' . get_class() . ' ########## ' . print_r( $options, true ) );

        // Attempt to sanitize, validate, and save the options
        if( is_array( $options )) {
            foreach( $options as $key => $value ) {
                if( 'secret_key' == $key ) {
                    if( cc_starts_with($value, 's_' ) ) {
                        // Attempt to get the subdomain from the cloud and save it locally
                        $subdomain = CC_Cloud_Subdomain::load_from_cloud( $value );
                        if( isset($subdomain) ) {
                            $options[ 'subdomain' ] = $subdomain;
                        }
                    }
                    else {
                        $clean = false;
                        $error_message = __( 'The secret key is invalid', 'cart66' );
                        add_settings_error(
                            'cart66_main_settings_group',
                            'invalid-secret-key',
                            $error_message,
                            'error'
                        );
                        CC_Log::write( "Cart66 settings validation error added: $error_message" );
                    }
                }
            }

        }
        else {
            $message = __( 'Cart66 settings were not saved', 'cart66' );
            add_settings_error(
                'cart66_main_settings_group',
                'settings-valid',
                $message,
                'error'
            );
        }

        // Sanitize options registered by add-on plugins
        $options = apply_filters( 'cart66_main_settings_sanitize', $options);
         */

        return $options;
    }

}


<?php

class CC_Scripts {
    public static function enqueue_choices() {
        $url = cc_url();
        wp_enqueue_style( 'choices', $url .'resources/css/choices.min.css' );
        wp_enqueue_script( 'choices', $url . 'resources/js/choices.min.js' );
        wp_enqueue_script( 'c66-choices', $url . 'resources/js/c66-choices.js', ['choices'] );
    }
}

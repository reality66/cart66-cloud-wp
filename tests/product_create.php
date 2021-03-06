<?php
$url = 'http://cart66.leeh.blue:8080/cc-api/v1/products';
$data = array('sku' => 'auto-01');

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode( $data ),
    )
);

$context  = stream_context_create( $options );
$result = file_get_contents( $url, false, $context );

var_dump( $result );

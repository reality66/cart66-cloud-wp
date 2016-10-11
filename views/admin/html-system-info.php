<?php
$ch = curl_init('https://www.howsmyssl.com/a/check');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($ch);
curl_close($ch);

$tls_version = "Not set";
$json = json_decode($data);
if ( isset( $json->tls_version ) ) {
  $tls_version = $json->tls_version;
}
?>
<div class="card">
    <table>
        <tr>
            <th>TLS Version</th>
            <td><?php echo $tls_version ?></td>
        </tr>
    </table>
</div>


<?php

ob_start();
phpinfo();
$content = ob_get_clean();

$doc = new DOMDocument;
$mock = new DOMDocument;

$doc->loadHTML( $content );
$body = $doc->getElementsByTagName('body')->item(0);

foreach ($body->childNodes as $child){
    $mock->appendChild($mock->importNode($child, true));
}

$html = $mock->saveHTML();

$styles = "<style type='text/css'>
pre {margin: 0; font-family: monospace;}
.center {text-align: center;}
.center table {margin: 1em auto; text-align: left;}
.center th {text-align: center !important;}
h1 {font-size: 150%;}
h2 {font-size: 125%;}
.p {text-align: left;}
.e {background-color: #ccf; width: 300px; font-weight: bold; padding: 5px 15px; }
.h {background-color: transparent; font-weight: bold;}
.v {background-color: #ddd; max-width: 300px; overflow-x: auto; padding: 5px; }
.v i {color: #999;}
.v td p { padding: 0px 20px; }
a img { display: none; }
</style>";
?>

<div class="card" style="width: 100%; max-width: 1000px;">
    <?php echo $styles . $html; ?>
</div>

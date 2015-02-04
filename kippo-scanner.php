<?php
# Author: ikoniaris

require_once('config.php');
require_once(DIR_ROOT . '/include/misc/xss_clean.php');

function scanFileUrl($file_url)
{
    $scanner_url = 'http://www.garyshood.com/virus/scan.php';

    $fields = array(
        //this limit comes from garyshood's website itself and must be included even if we submit URLs
        'MAX_FILE_SIZE' => urldecode('100000000'),
        'fileurl' => urlencode($file_url),
        'method' => urldecode('url'),
    );

    $fields_string = "";
    foreach ($fields as $key => $value) {
        $fields_string .= $key . '=' . $value . '&';
    }
    rtrim($fields_string, '&');

    $ch = curl_init($scanner_url);

    curl_setopt($ch, CURLOPT_URL, $scanner_url);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

    $result = curl_exec($ch);

    curl_close($ch);
}

if (isset($_GET['file_url'])) {
    $file_url = xss_clean($_GET['file_url']);
    scanFileUrl($file_url);
} else {
    echo "Kippo-Graph virus scanning submission error. You need to supply a valid file URL.";
}
?>
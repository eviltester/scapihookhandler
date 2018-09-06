<?php

// import your own hooklogger here since the emptyhooklogger doesn't do anything
require_once ("emptyhooklogger.php");

//require_once ("hooklogger.php");


$KEY = 'unknown';
$CUSTOMKEY_ID = 'unknown';
$CUSTOMKEY_URL = 'unknown';
$LIVE_TEST_SECRETCODE = '';

require_once ("hookconfig.php"); // set the $key and custom field values

$body = @file_get_contents('php://input');

$testmode=getTestModeFromConfig();

$payload = json_decode($body, true);

// dummy to allow a static web site to operate independently


if (!testmode && !validateCallback($payload, $KEY)) {
    http_response_code(400);
    //$this->returnErrorJson('Callback failed validation');
    exit();
}else{
    http_response_code(200);
    header('Content-Type: application/json');

    $customfields = $payload['data']['custom_fields'];

    $id="unknown";
    $url="unknown";

    foreach ($customfields as $key) {
        if(strcmp($key['key'], $CUSTOMKEY_ID)==0){
            $id = $key['value'];
        }
        if(strcmp($key['key'], $CUSTOMKEY_URL)==0){
            $url = $key['value'];
        }

    }

    $data = array('id' => $id, 'permalink' => $url);

    echo json_encode($data);

    $logger = new HookLogger();
    $logger->logData($data, $payload);


}


function validateCallback($payload, $KEY) {
    // copy the mac from array
    $givenMac = $payload['meta']['mac'];

    // removes the mac from array
    unset($payload['meta']['mac']);

    // recalculate the mac with you key
    $calcMac = hash_hmac('sha256', json_encode($payload), $KEY);

    // validate that calculated and given mac are the same
    return hash_equals($givenMac, $calcMac);
}

?>
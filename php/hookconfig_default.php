<?php

// Change these details to be your details

$KEY = '';
$CUSTOMKEY_ID = '';
$CUSTOMKEY_URL = '';

// you don't need to set this if you aren't going to do live testing
$LIVE_TEST_SECRETCODE='';


function getTestModeFromConfig(){

    global $body, $CUSTOMKEY_ID, $CUSTOMKEY_URL, $LIVE_TEST_SECRETCODE;
    $testmode = false;

    return $testmode;
}
?>
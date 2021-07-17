<?php

if(empty($_ENV['DISCORD_URL'])) {
    logMsg('Missing Discord URL in ENV');
    exit(1);
}

if(empty($_ENV['TOKEN'])) {
    logMsg("Missing Token in ENV");
    exit(1);
}

if(!checkToken()) {
    logMsg('Message rejected! Token invalid');
    exit(1);
}

logMsg('>> Msg :: '.$_SERVER['HTTP_MSG']);
sendMsg();

function checkToken() :bool {
    return ($_SERVER['HTTP_TOKEN']===$_ENV['TOKEN']);
}

function sendMsg() {

    if(!isset($_ENV['DISCORD_URL'])) {
        logMsg("Missing Discord URL. please see README.md");
        print "Missing Discord URL";
        return;
    }

    if(!isset($_SERVER['HTTP_MSG'])) {
        logMsg("Warning! Missing message!");
        return;
    }
    
    $requestData = json_encode([
        'content' => $_SERVER['HTTP_MSG']
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

    $ch = curl_init();
    curl_setopt_array($ch,[
        CURLOPT_URL => $_ENV['DISCORD_URL'],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $requestData,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Content-Length: ".strlen($requestData)
        ]
    ]);

    // we COULD validate SSL, but who really cares?
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);

    $response = curl_exec( $ch );
    
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);

    $body = substr($response, $header_size);
    logMsg('==> Body :: '.$body);
    logMsg('==> Req :: '.$requestData);

    curl_close($ch);
}

function logMsg($msg) {

    $ptr = fopen("php://stderr","w");

    if(is_array($msg)) {
        $msg = print_r($msg,true);
    }

    fwrite($ptr,$msg);
    fwrite($ptr,PHP_EOL);
    fclose($ptr);
}
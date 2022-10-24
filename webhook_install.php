<?php
require "config/Config.php";

function installWebHook():string {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => "https://api.telegram.org/bot".Config::API_KEY."/setWebhook?url=".Config::WEBHOOK_URL,
        CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"))
    ]);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}

function parseResult(string $data) {
    $result = json_decode($data,true);
    if ($result["result"]) {
        echo $result["description"];
    } else {
        var_dump($result);
    }
}

parseResult(installWebHook());
//{"ok":true,"result":true,"description":"Webhook was set"}
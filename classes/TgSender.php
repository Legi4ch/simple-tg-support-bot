<?php

class TgSender
{

    public static function sendTextMessage(string $userId,string $text) {
        $data = array(
            "chat_id" => $userId,
            "text" => $text
        );
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.telegram.org/bot' .Config::API_KEY. '/sendMessage',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"))
        ]);
        curl_exec($curl);
        curl_close($curl);
    }

    public static function sendPhotoAsFile(string $userId, string $photoFile, string $photoCaption = "") {
        $data = array(
            "chat_id" => $userId,
            "photo" => curl_file_create(Config::IMAGES_DOWNLOAD_PATH.$photoFile)
        );
        if ($photoCaption) {
            $data["caption"] = $photoCaption;
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.telegram.org/bot' .Config::API_KEY. '/sendPhoto',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array_merge(array("Content-Type: multipart/form-data"))
        ]);
        curl_exec($curl);
        curl_close($curl);
   }

    public static function sendPhotoAsUrl(string $userId, string $photoFile, string $photoCaption = "") {
        $data = array(
            "chat_id" => $userId,
            "photo" => Config::IMAGES_WEB_PATH.$photoFile
        );
        if ($photoCaption) {
            $data["caption"] = $photoCaption;
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.telegram.org/bot' .Config::API_KEY. '/sendPhoto',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array_merge(array("Content-Type: multipart/form-data"))
        ]);
        $result = curl_exec($curl);
        curl_close($curl);
    }

}
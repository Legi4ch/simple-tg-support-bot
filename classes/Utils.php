<?php

class Utils
{
    public static function trimStr($string, $chars, $more="..."): string {
        if (strlen($string) <= $chars) {
            return strip_tags($string);
        } else {
            $string = strip_tags($string);
            $string = substr($string, 0, $chars);
            $string = rtrim($string, "!,.-");
            return substr($string, 0, strrpos($string, ' ')).$more;
        }
    }


    public static function uploadPhoto($source, $destination) {
        if (move_uploaded_file($source, $destination)) {
            return basename($destination);
        } else return "";
    }

    public static function getFileExt(string $fileName):string {
        return substr(strrchr($fileName,'.'), 1);
    }

    public static function getImgTag($imageFileName):string {
        return "<img src = ".Config::IMAGES_WEB_PATH.$imageFileName.' class="chat-image">';
    }

}
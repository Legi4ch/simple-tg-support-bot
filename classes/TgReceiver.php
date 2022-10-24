<?php

class TgReceiver {

    private const DATE_FORMAT = "d.m.Y H:i:s";

    private array $jsonObject;
    private string $id = "";
    private string $name = "";
    private string $firstName = "";
    private string $timestamp = "";
    private string $message = "";
    private string $command = "";
    private string $photoId = "";
    private string $caption = "";


    public function __construct(string $rawInput) {
        $this->init($rawInput);
    }


    private function init (string $rawInput) {
        $this->jsonObject = json_decode($rawInput, true);
        $this->setValues();
    }

    private function setValues() {
        $this->id = $this->jsonObject["message"]["from"]["id"];
        $this->timestamp = $this->jsonObject["message"]["date"];

        if (array_key_exists("text",$this->jsonObject["message"])) {
            $this->message = $this->jsonObject["message"]["text"];
        }

        if (array_key_exists("username",$this->jsonObject["message"]["from"])) {
            $this->name = $this->jsonObject["message"]["from"]["username"];
        }

        if (array_key_exists("first_name",$this->jsonObject["message"]["from"])) {
            $this->firstName = $this->jsonObject["message"]["from"]["first_name"];
        }

        //проверка на команду
        if (substr($this->message,0,1) == '/') {
            $this->command = substr($this->message,1);
        }

        //проверка на отправленное фото
        if  (array_key_exists("photo",$this->jsonObject["message"])) {
            //берем самое большое фото, оно последнее в массиве
            $this->photoId = $this->jsonObject["message"]["photo"][count($this->jsonObject["message"]["photo"])-1]["file_id"];
        }

        //проверка на подпись к фото
        if (array_key_exists("caption",$this->jsonObject["message"])) {
            $this->caption = $this->jsonObject["message"]["caption"];
        }

    }


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getFormattedTimestamp(): string
    {
        return date(self::DATE_FORMAT,$this->timestamp);
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }


    public function getCommand() {
        return $this->command;
    }

    /**
     * @return string
     */
    public function getPhotoId(): string
    {
        return $this->photoId;
    }


    /**
     * @return string
     */
    public function getCaption(): string
    {
        return $this->caption;
    }

    public function toString(){
        return $this->getId()."-".$this->getName()."-".$this->getFirstName()."-".$this->getFormattedTimestamp();
    }



}


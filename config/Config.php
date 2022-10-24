<?php

class Config {


    #Настройки сервера
    const SITE_URL = "https://your_domain"; //адрес размещения
    // Базу данных лучше размещать в папке не доступной из веб, например /var/database/...
    // Папка и файл базы должны быть доступны для записи пользователем под которым работает web сервер
    const DB_PATH = "/your-path/db/database.db"; //Unix путь до файла базы
    const IMAGES_DOWNLOAD_PATH = "/your-path/"; //Unix путь для сохранения фото из чата и отправленных фото
    const IMAGES_WEB_PATH = self::SITE_URL."/your-path/"; //путь по которому веб сервер отдает фото (папка web сервера)


    const DT_FORMAT = "d.m.Y H:i:s";
    const AUTH_FAIL_MSG = "Ошибка авторизации";

    #настройки Telegram
    const API_KEY =""; //Токен бота полученный от BotFather
    const WEBHOOK_URL = self::SITE_URL."receiver/index.php";
    const TELEGRAM_FILES_PATH = "https://api.telegram.org/file/bot".self::API_KEY."/";
    const TELEGRAM_FILES_API = "https://api.telegram.org/bot".self::API_KEY. "/getFile?file_id=";

    const MSG_HELLO_MSG = "Приветствуем Вас!"; //это сообщение получит пользователь подключившийся к боту

    #настройки чата
    const CHAT_BLOCK_TIME_SEC = 300; //время блокировк чата пока в нем работает оператор. Продлевается, пока чат открыт.
    const TRIM_MSG_TO = 200; //кол-во символов до которого будет обрезано сообщение, показываемое в списке чатов
    const CHAT_OWNER_IS_CURRENT_USER = "Вы ответили:";
    const CHAT_OWNER_IS_ANOTHER_USER = "%s ответил:";
    const CHAT_IS_BLOCKED_BY_ANOTHER_USER = "Сейчас этот чат заблокирован другим пользователем!";
    const EMPTY_CHAT_LIST_MSG = "Еще никто не писал(";

}
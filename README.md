# simple-tg-support-bot

## Простой чат бот Telegram для переписки с пользователями через web-интерфейс

### Не требует сложных настроек. Написан на PHP и JS.



__Что может?__
+ Пользователь пишет боту, оператор отвечает через веб-интерфейс. Пользователь получает ответ в бот.
+ Поддерживает несколько операторов в команде. Рекомендую 3, максимум 5. Выдержит и больше, но для большого количества обращений в бот и большего числа операторов, лучше использовать другие решения.
+ Бан пользователей. Для пользователя он прозрачен, то есть писать он сможет, но его сообщения не будут получены операторами.
+ Удаление чатов.
+ Пауза для оператора. Пока оператор на паузе, обновления в его интерфейс приходить не будут.
+ Пока оператор общается с одним из пользователей, другие операторы не видят этот чат.
+ В текущей версии поддерживает только получение фото. Отправка пока не поддерживается.


__Как работает?__
+ Скачиваете и размещаете проект на сайте
+ Регистрируете вашего бота через BotFather в телегам
+ настраиваете файл ```/config/Config.php``` 
+ обязательно укажите токен вашего бота, полученного от BotFather
+ выполняете на вашем сайте скрипт ```webhook_install.php```. Если все правильно, то ответом должно быть __*Webhook was set*__.
+ заводите операторов напрямую в файле базы данных в таблицу ```system_users```
+ *__можете общаться с пользователями через веб интерфейс__*


__Важно__

Рекомендую размещать файл базы данных в директории, куда веб-сервер не _смотрит_.
Или защищать его от скачивания напрямую, с помощью средств веб-сервера.
Директория, где размещается файл базы данных, а также сам файл базы, должны быть доступными для записи
пользователю, от имени которого запущен веб-сервер.
Также должны быть доступны для записи директории, в которые будут сохраняться фотографии и файлы, полученные 
от пользователй через бот.

__Особенности__

Из коробки используется база SQLite для хранения. Может быть изменена на MysSql при необходимости.
Автоматической миграции не предусмотрено. Для смены базы необходимо будет создать таблицы вручную и произвести изменения
в файлах ```/config/Config.php```, ```/classes/Db.php```

В текущей версии нет возможности редактировать базу из веб интерфейса. Для добавления операторов 
необходимо вручную отредактировать файл базы с помощью программ для редактирования SQLite файлов.
Например, DB Browser for SQLite https://sqlitebrowser.org/.

Данные в интерфейсе обновляются с помощью XMLHttpRequest запросов. 
Поэтому общение происходит не совсем в реальном времени. 
Обновления запрашиваются каждые 5 секунд. Эти значения могут быть изменены в настройках JS в файле ```/js/setting.js```

let selectedChat = 0;
let lastId = 0;
let contentTimer = false;
let pause = false;
const UPDATE_CHAT_LIST_SEC = 5*1000;
const UPDATE_CHAT_CONTENT_SEC = 5*1000;

const MESSAGE_EMPTY = "Нельзя отправить пустое сообщение!";
const MESSAGE_OK = "Сообщение отправлено";
const MESSAGE_ERROR = "Ошибка при отправке сообщения!";
const MESSAGE_BAN_OK = "Пользователь заблокирован. Он больше не сможет оправлять сообщения!";
const MESSAGE_BAN_ERROR = "Ошибка при попытке блокировки пользователя!";
const MESSAGE_DELETE_ERROR = "Ошибка при удалении чата!";
const MESSAGE_PAUSE_ON = "Вы на паузе! Отмените ее если хотите снова общаться!";


const BAN_DIALOG_HEADER = "Заблокировать пользователя?";
const DELETE_DIALOG_HEADER = "Удалить всю переписку в этом чате?";
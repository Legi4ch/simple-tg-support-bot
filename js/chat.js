function selectChat(chatId) {
    try {
        if (selectedChat > 0 && selectedChat != chatId) { //сбрасываем покрашенный элемент
            document.getElementById(selectedChat).className = 'chat';
            lastId = 0; //стираем последний айдишник предыдущего чата
        }
        if (chatId > 0) { //красим текущий элемент
            let chat = document.getElementById(chatId);
            chat.className = 'selected-chat';
            selectedChat = chatId;
        }
    } catch (e) {
        console.log(e);
    }
}

function loadChatContent(chatId) {
    if (pause) {
        M.toast({html: MESSAGE_PAUSE_ON});
        return false;
    }
    if (selectedChat == chatId) {
        requestChatContent(chatId);
    } else {
        lastId = 0;
        requestChatContent(chatId);
    }
}

function drawChatsList(htmlStr) {
    let chatDiv = document.getElementById("chatsList");
    chatDiv.innerHTML = htmlStr;
}

function  setChatClicks(){
    let chatsArray = document.querySelectorAll(".chat");
    chatsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            selectChat(this.id);
            loadChatContent(this.id);
        });
    });
}

function requestChatsLists() {
    let xhr = new XMLHttpRequest();
    xhr.open('GET', './backend/get-chats-list.php','true');
    xhr.send();

    xhr.onload = function() {
        if (xhr.status == 200) {
            drawChatsList(xhr.response); //загружаем блоки
            setChatClicks(); //устанавливаем события
            selectChat(selectedChat); //подсвечиваем чат который был выбран (если был)
        }
    };
    xhr.onerror = function() {
        console.log("Chat list request error")
    };
}

function requestChatLastId(chatId) {
    let xhr = new XMLHttpRequest();
    xhr.open('GET', './backend/get-chat-content.php?id='+chatId+'&get_last=1' ,'false');
    xhr.send();
    xhr.onload = function() {
        if (xhr.status == 200) {
            lastId = parseInt(xhr.response);
        }
    };
    xhr.onerror = function() {
        console.log("Error while request last id from chat " + chatId);
    };
}

function scrollContent() {
    let block = document.getElementById("chat-content");
    block.scrollTop = block.scrollHeight;
}


function requestChatContent(chatId) {
    let xhr = new XMLHttpRequest();
    xhr.open('GET', './backend/get-chat-content.php?id='+chatId+'&last='+lastId ,'true');
    xhr.send();
    let chatBody = document.getElementById("chat-content");
    xhr.onload = function() {
        if (xhr.status == 200) {
            if (lastId == 0) {
                chatBody.innerHTML = xhr.response;
            } else {
                let insert = document.createElement("div");
                insert.innerHTML = xhr.response;
                chatBody.append(insert);
            }
            requestChatLastId(chatId);
            let messageDiv = document.getElementById("message-div").style.display = "block";
            if (xhr.response.length > 50) {
                scrollContent();
            }
            if (contentTimer == false) {
                startAutoUpdateContent();
            }
        }
        if (xhr.status == 404) {
            chatBody.innerHTML = xhr.responseText;
            M.toast({html: xhr.responseText});
            let messageDiv = document.getElementById("message-div").style.display = "none";
            contentTimer = false;
            window.clearInterval(contentUpdateTimer);
        }

    };
    xhr.onerror = function() {
        console.log(xhr.response);
        contentTimer = false;
        window.clearInterval(contentUpdateTimer);
    };
}

function startAutoUpdateContent() {
    if (selectedChat > 0) {
        contentTimer = true;
        contentUpdateTimer = window.setInterval(
            () => requestChatContent(selectedChat),
            UPDATE_CHAT_CONTENT_SEC
        );
    }
}


function sendMessage() {
    let xhr = new XMLHttpRequest();
    let messageText = document.getElementById("msg").value;
    if (messageText.length < 1) {
        M.toast({html: MESSAGE_EMPTY});
        return false;
    }
    let requestBody = 'id=' + encodeURIComponent(selectedChat) + '&message=' + encodeURIComponent(messageText);
    xhr.open('POST', './backend/send-message.php','false');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(requestBody);

    xhr.onload = function() {
        if (xhr.status == 200) {
            if (xhr.response == "Ok") {
                M.toast({html: MESSAGE_OK});
            } else {
                M.toast({html: MESSAGE_ERROR});
            }
        }
    };
    xhr.onerror = function() {
        console.log(xhr.response);
    };
    document.getElementById("msg").value = "";
}

function banUser() {
    if (confirm(BAN_DIALOG_HEADER)) {
        let xhr = new XMLHttpRequest();
        var btn = document.getElementById("ban-btn");
        xhr.open('GET', './backend/ban-user.php?id=' + selectedChat, 'false');
        xhr.send();
        xhr.onload = function () {
            if (xhr.status == 200) {
                if (xhr.response == "Ok") {
                    M.toast({html: MESSAGE_BAN_OK});
                    contentTimer = false;
                    window.clearInterval(contentUpdateTimer);
                    btn.style.display = "none";
                } else {
                    M.toast({html: MESSAGE_BAN_ERROR});
                }
            } else {
                M.toast({html: MESSAGE_BAN_ERROR});
            }
        };
        xhr.onerror = function () {
            console.log(xhr.response);
        };
    }
}

function delChat() {
    if (confirm(DELETE_DIALOG_HEADER)) {
        let xhr = new XMLHttpRequest();
        xhr.open('GET', './backend/delete-chat.php?id=' + selectedChat,'false');
        xhr.send();
        xhr.onload = function() {
            if (xhr.status == 200) {
                if (xhr.response == "Ok") {
                    location.reload();
                } else {
                    M.toast({html: MESSAGE_DELETE_ERROR});
                }
            } else {
                M.toast({html: MESSAGE_DELETE_ERROR});
            }
        };
        xhr.onerror = function() {
            console.log(xhr.response);
        };
    }
}

function stopTimers() {
    if (pause) {
        location.reload();
    } else {
        contentTimer = false;
        pause = true;
        try {
            window.clearInterval(contentUpdateTimer);
            document.getElementById("chat-content").innerText="";
            document.getElementById("message-div").style.display = "none";
            document.getElementById("msg").value = "";
        } catch (e) {
            console.log(e);
        } finally {
            window.clearInterval(chatListUpdateTimer);
            document.getElementById("pause-btn").innerText="СНЯТЬ ПАУЗУ";
        }
    }
}

function selectFile() {
    let input = document.createElement("input");
    input.type = "file";
    input.accept ="image/*";
    input.onchange =  e  => {
        let file = e.target.files[0];
        let fd = new FormData();
        fd.append("photo", file);
        fd.append("id", selectedChat);

        sendPhoto(fd);

    }
    input.click();
}


function sendPhoto(fd) {

    let xhr = new XMLHttpRequest();
    xhr.open('POST', './backend/send-photo.php','true');
    xhr.send(fd);

    xhr.onload = function() {
        if (xhr.status == 200) {
            console.log(xhr.response);
            if (xhr.response == "Ok") {
                M.toast({html: MESSAGE_OK});
            } else {
                M.toast({html: MESSAGE_ERROR});
            }
        } else {
            M.toast({html: MESSAGE_ERROR});
        }
    };
    xhr.onerror = function() {
        console.log("error");
    };
}

document.addEventListener('DOMContentLoaded', function() {
    requestChatsLists();

    let messageDiv = document.getElementById("message-div").style.display = "none";

    chatListUpdateTimer = window.setInterval(
        () => requestChatsLists(),
        UPDATE_CHAT_LIST_SEC
    );

});
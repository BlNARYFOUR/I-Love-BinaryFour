"use strict";

document.addEventListener("DOMContentLoaded", onLoad);

let firstTimeChatGetsLoaded = true;
let selectedChat = null;
let prevSelectedChat = selectedChat;
let beginIndex = 0;
let messageBackup = null;

function onLoad(e) {
    updateChatStuff();
    let timer = setInterval(updateChatStuff, 500);
}

function startMusic() {
    document.querySelector("audio").play();
}

function updateChatStuff() {
    updateChatList();
    updateChat(beginIndex);
}

function updateChat(beginIndex) {
    fetch(`assets/php/request.php?request=chat&id=${selectedChat}&beginIndex=${beginIndex}&amount=50`, {
        method: 'GET',
        credentials: 'include'
    })
    .then(function(res) {
        if(res.ok === true)
            return res.json();
    })
    .then(function(text) {
        if(selectedChat !== prevSelectedChat) {
            showDialog(text);
            firstTimeChatGetsLoaded = false;
            prevSelectedChat = selectedChat;
        }
        else if(messageBackup !== text[0]["sendTime"]) {
            showDialog(text);
            if(text[0]["isSender"] === "0") {
                startMusic();
            }
        }

        messageBackup = text[0]["sendTime"];
    })
    .catch(function(err) {
        console.log("Error 404: Could not connect to the server - Find", err.message);
    });
}

function showDialog(messages) {
    document.querySelector("#message-container").innerHTML = "";

    let buffer;
    for(let i=0; i<messages.length; i++) {
        buffer = '<li class="';

        if(messages[i]["isSender"] === "1") {
            buffer += "send";
        }
        else {
            buffer += "received";
        }

        buffer += '"><span> Sent ';
        buffer += messages[i]["sendTime"];
        buffer += '</span><h3>';
        buffer += messages[i]["usr"];
        buffer += '</h3><p>';
        buffer += messages[i]["content"];
        buffer += '</p></li>';

        document.querySelector("#message-container").innerHTML += buffer;
    }
}

function updateChatList() {
    fetch(`assets/php/request.php?request=chatlist`, {
        method: 'GET',
        credentials: 'include'
    })
    .then(function(res) {
        if(res.ok === true)
            return res.json();
    })
    .then(function(text) {
        showAvailableChats(text);
    })
    .catch(function(err) {
        console.log("Error 404: Could not connect to the server - Find", err.message);
    });
}

function showAvailableChats(chats) {
    document.querySelector("#chatList").innerHTML = "";

    for(let i=0; i<chats.length; i++) {
        let classAddOn = selectedChat === chats[i]["id"] ? "active" : "";

        document.querySelector("#chatList").innerHTML += '<li class="' + classAddOn + '" id="chat-' + chats[i]["id"] + '">' + chats[i]["chatName"] + '</li>';
    }

    let chatLI = document.querySelectorAll("#chatList li");

    for(let i=0; i< chatLI.length; i++) {
        chatLI[i].addEventListener("click", openChat);
    }
}

function openChat(e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    this.classList.add("active");
    if(selectedChat !== null) {
        document.querySelector(`#chat-${selectedChat}`).classList.remove("active");
    }

    selectedChat = this.id.split("-")[1];
    document.querySelector("#chatId").value = selectedChat;
    document.querySelector("#chatId2").value = selectedChat;

    updateMembers();
}

function updateMembers() {
    if(selectedChat !== null) {
        fetch(`assets/php/request.php?request=members&chat=${selectedChat}`, {
            method: 'GET',
            credentials: 'include'
        })
        .then(function(res) {
            if(res.ok === true)
                return res.json();
        })
        .then(function(text) {
            showMembers(text);
        })
        .catch(function(err) {
            console.log("Error 404: Could not connect to the server - Find", err.message);
        });
    }
    else {
        document.querySelector("#memberList").innerHTML = "<li>No chat selected</li>";
    }
}

function showMembers(members) {
    document.querySelector("#memberList").innerHTML = "";

    for(let i=0; i<members.length; i++) {
        document.querySelector("#memberList").innerHTML += '<li id="member-' + members[i]["id"] + '">' + members[i]["usr"] + '</li>';
    }
}
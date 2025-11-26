let CHAT_CONFIG = {};
let lastMessageTime = new Date(0).toISOString();
const DOM = {
    chatInput: 'chat-input',
    sendButton: 'send-button',
    chatBox: 'chat-box',
    chatData: 'chat-data' //id script tag dari php
};

document.addEventListener('DOMContentLoaded', ()=>{
    const chatDataElement = document.getElementById(DOM.chatData);
    if (chatDataElement) {
        try {
            CHAT_CONFIG = JSON.parse(chatDataElement.textContent.trim());
            if(!CHAT_CONFIG.chatId || !CHAT_CONFIG.userId || !CHAT_CONFIG.apiBaseUrl){
                console.error('Konfigurasi chat tidak lengkap.');
                return;
            }
            console.log(`Chat konfigurasi dimuat:, ${CHAT_CONFIG.chatId}`);

            const sendButton = document.getElementById(DOM.sendButton);
            const chatInput = document.getElementById(DOM.chatInput);

            if(sendButton){
                sendButton.addEventListener('click', handleSend);
            }
            if(chatInput){
                chatInput.addEventListener('keypress', (e)=>{
                    if(e.key === 'Enter')handleSend();
                });
            }
            startPolling(CHAT_CONFIG.chatId);
        } catch (err) {
            console.error('Gagal mengurai konfigurasi chat:', err);
        }
    }
});

function handleSend(){
    const inputField = document.getElementById(DOM.chatInput);
    const message = inputField.value.trim();
    if(message){
        sendMessage(CHAT_CONFIG.chatId, CHAT_CONFIG.userId, CHAT_CONFIG.userRole, message);
        inputField.value = '';
    }
}

async function sendMessage(chatId, senderId, senderRole, content){
    const url = `${CHAT_CONFIG.apiBaseUrl}/api/chats/${chatId}/send`;
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ senderId, senderRole, content })
        });
        const data = await response.json();
        if(response.ok){
            displayMessage(data.sentMessage, 'outgoing');
            console.log('Pesan terkirim:', data);
            if(data.sentMessage && data.sentMessage.timestamp){
                lastMessageTime = data.sentMessage.timestamp;
            }
        }else{
            console.error('Gagal mengirim pesan:', data.message);
            alert('Gagal mengirim pesan: ' + data.message);
        }
    } catch (err) {
        console.error('Kesalahan jaringan/server:', err);
        alert('gagal terhubung ke server.');
    }
}

function startPolling(chatId){
    const pollingInterval = 3500;
    checkNewMessages(chatId);

    setInterval(()=>{
        checkNewMessages(chatId);
    }, pollingInterval);
}

async function checkNewMessages(chatId){
    const url = `${CHAT_CONFIG.apiBaseUrl}/api/chats/${chatId}/messages?since=${lastMessageTime}`;

    try{
        const response = await fetch(url);
        if(!response.ok){
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const messages = await response.json();
        if(messages.length > 0){
            messages.forEach(msg => {
                if(msg.senderId !== CHAT_CONFIG.userId){
                    displayMessage(msg, 'incoming');
                }
        });
            lastMessageTime = messages[messages.length - 1].timestamp;
        }
    }catch(err){
        console.error('Gagal memeriksa pesan baru:', err);
    }
}

function displayMessage(message, type){
    const chatBox = document.getElementById(DOM.chatBox);
    if(!chatBox) return;
    const isOutgoing = (type === 'outgoing');
    const myMsg = message.senderId === CHAT_CONFIG.userId;

    const sender = myMsg ? 'Anda' : message.senderRole;
    const alignClass = myMsg ? 'self-end bg-purple-600 text-white' : 'self-start bg-gray-200 text-gray-900';
    const messageElement = document.createElement('div');
    messageElement.className = `max-w-xs md:max-w-md lg:max-w-lg p-3 my-1 rounded-lg ${alignClass} shadow-md`;

    let headerHtml = '';
    if(!isOutgoing){
        headerHtml = `<span class="font-semibold text-xs mb-1 block">${sender}</span>`;
    }

    messageElement.innerHTML = `
        ${headerHtml}
        <p class="text-sm">${message.content}</p>
        <span class="text-[0.6rem] opacity-70 mt-1 block text-right">${formatTime(message.timestamp)}</span>
        `;
    chatBox.appendChild(messageElement);
    chatBox.scrollTop = chatBox.scrollHeight;
}

function formatTime(timestamp){
    try{
        const date = new Date(timestamp);
        return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }catch(err){
        return 'Waktu tidak valid';
    }
}

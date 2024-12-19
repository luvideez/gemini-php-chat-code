<!DOCTYPE html>
<html>
<head>
    <title>Chat</title>
    <style>
body {
    font-family: sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f8f8;
}

h1 {
    text-align: center;
    background-color: #4285f4;
    color: #fff;
    padding: 20px;
    margin: 0;
}

#chatbox {
    width: 80%;
    height: 350px;
    border: 1px solid #ccc;
    margin: 20px auto;
    overflow-y: auto;
    padding: 10px;
    background-color: #fff;
    border-radius: 5px;
}

#chatbox p {
    margin: 15px 0;
    padding: 8px 12px;
    border-radius: 10px;
    font-size: 15px;
    line-height: 1.4;
    word-wrap: break-word;
}

#chatbox p.user {
    background-color: #DCF8C6;
    text-align: right;
    margin-left: 20%;
    border-bottom-right-radius: 2px;
}

#chatbox p.gemini {
    background-color: #E8EAED;
    text-align: left;
    margin-right: 20%;
    border-bottom-left-radius: 2px;
}

#chatbox p.gemini::before {
    content: "Đi tù có gì vui?";
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    color: #70757A;
    font-size: 12px;
}

#chatbox p.user::before {
    content: "Bạn";
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    color: #70757A;
    font-size: 12px;
}

#chatbox p:hover {
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

form {
    width: 80%; /* Giữ nguyên độ rộng form */
    height: auto; /* Thay đổi height form */
    margin: 0 auto;
    display: flex;
}

input[type="text"] {
    width: 80%;
    padding: 40px 20px; /* Tăng padding trên và dưới */
    border: 1px solid #ccc;
    border-radius: 5px 0 0 5px;
    font-size: 16px;
}

button[type="submit"] {
    width: 20%;
    padding: 40px 10px; /* Tăng padding trên và dưới */
    background-color: #4285f4;
    color: #fff;
    border: none;
    border-radius: 0 5px 5px 0;
    cursor: pointer;
    font-size: 16px;
}
    </style>
</head>
<body>
    <h1>AI CHAT</h1>

    <div id="chatbox">
    </div>

    <form id="messageForm">
        <input type="text" name="message" id="message" placeholder="Nhập tin nhắn của bạn...">
        <button type="submit">Gửi</button>
    </form>

    <script>
        const form = document.getElementById('messageForm');
        const chatbox = document.getElementById('chatbox');
        const messageInput = document.getElementById('message');

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const message = messageInput.value;
            messageInput.value = '';

            appendMessage('user', 'Bạn: ' + message);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'chat.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    appendMessage('gemini', xhr.responseText);
                } else {
                    appendMessage('gemini', 'Lỗi: Không thể kết nối đến server.');
                }
            };
            xhr.send('message=' + encodeURIComponent(message));
        });

        function appendMessage(type, message) {
          const messageElement = document.createElement('p');
          messageElement.classList.add(type);
          chatbox.appendChild(messageElement);
          if(type == 'user'){
            messageElement.innerHTML = message;
          } else {
            const lines = message.split('\n');
            let lineIndex = 0;
            let charIndex = 0;
            let currentLine = '';

            const typingInterval = setInterval(() => {
              if (lineIndex < lines.length) {
                const line = lines[lineIndex];
                if (charIndex < line.length) {
                  currentLine += line[charIndex];
                  messageElement.innerHTML = currentLine + '|'; 
                  charIndex++;
                } else {
                  currentLine += '<br>';
                  lineIndex++;
                  charIndex = 0;
                }
                chatbox.scrollTop = chatbox.scrollHeight;
              } else {
                messageElement.innerHTML = currentLine;
                clearInterval(typingInterval);
              }
            }, 25);
          }
        }
    </script>
</body>
</html>

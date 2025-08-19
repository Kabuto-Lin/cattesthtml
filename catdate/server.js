const express = require('express');
const bodyParser = require('body-parser');
const path = require('path');

const app = express();
const port = 3000; // 您可以選擇其他埠號

// 使用 body-parser 中介軟體來解析 JSON 請求體
app.use(bodyParser.json());

// 設置靜態檔案目錄，讓瀏覽器可以存取 login.html 和 login.js
app.use(express.static(__dirname));

// 簡單的使用者驗證 (僅為範例，實際應用應使用資料庫和安全措施)
const validUser = { username: 'testuser', password: 'password123' };

// 處理 POST 到 /login 的請求
app.post('/login', (req, res) => {
    const { username, password } = req.body;

    // 檢查使用者名稱和密碼
    if (username === validUser.username && password === validUser.password) {
        // 驗證成功
        res.status(200).json({ success: true, message: '登錄成功！' });
    } else {
        // 驗證失敗
        res.status(401).json({ success: false, message: '使用者名稱或密碼錯誤。' });
    }
});

// 啟動伺服器
app.listen(port, () => {
    console.log(`伺服器運行在 http://localhost:${port}`);
    console.log(`請訪問 http://localhost:${port}/login.html 進行測試`);
});

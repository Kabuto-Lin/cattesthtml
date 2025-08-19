<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

try {
    $pdo = new PDO("mysql:host=localhost;dbname=catdate;charset=utf8", "abuser", "1234", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // 後端驗證
    if (empty($username) || empty($password)) {
        echo json_encode(['status' => 0, 'error' => '帳號和密碼不得為空']);
        exit;
    }

    // 查詢用戶（支援帳號或電子信箱登入）
    $stmt = $pdo->prepare("SELECT uid, password, email FROM UserInfo WHERE uid = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['password'])) {
        // 登入成功，設置 session
        $_SESSION['uid'] = $row['uid'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['login_time'] = time();
        
        echo json_encode([
            'status' => 1, 
            'message' => '登入成功',
            'uid' => $row['uid']
        ]);
    } else {
        echo json_encode(['status' => 0, 'error' => '帳號或密碼錯誤']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => -1, 'error' => '伺服器錯誤：' . $e->getMessage()]);
}
?>

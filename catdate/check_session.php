<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

try {
    if (!isset($_SESSION['uid'])) {
        echo json_encode(['status' => 0, 'error' => '未登入']);
        exit;
    }

    $pdo = new PDO("mysql:host=localhost;dbname=catdate;charset=utf8", "abuser", "1234", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // 取得用戶詳細資訊
    $stmt = $pdo->prepare("SELECT uid, email, created_at FROM UserInfo WHERE uid = ?");
    $stmt->execute([$_SESSION['uid']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode([
            'status' => 1,
            'uid' => $user['uid'],
            'email' => $user['email'],
            'created_at' => $user['created_at'],
            'login_time' => $_SESSION['login_time'] ?? time()
        ]);
    } else {
        // 用戶不存在，清除 session
        session_destroy();
        echo json_encode(['status' => 0, 'error' => '用戶不存在']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => -1, 'error' => '伺服器錯誤：' . $e->getMessage()]);
}
?>

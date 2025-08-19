<?php
header("Content-Type: application/json; charset=UTF-8");

try {
    $pdo = new PDO("mysql:host=localhost;dbname=catdate;charset=utf8", "abuser", "1234", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $uid = trim($_POST['uid'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // 後端驗證
    if (empty($uid) || empty($password) || empty($confirm_password) || empty($email)) {
        echo json_encode(['status' => 0, 'error' => '所有欄位都必須填寫']);
        exit;
    }

    // 驗證帳號格式（3-20個英文或數字）
    if (!preg_match('/^[A-Za-z0-9]{3,20}$/', $uid)) {
        echo json_encode(['status' => 0, 'error' => '帳號格式錯誤（3-20個英文或數字）']);
        exit;
    }

    // 驗證密碼長度
    if (strlen($password) < 6) {
        echo json_encode(['status' => 0, 'error' => '密碼至少需要6個字元']);
        exit;
    }

    // 驗證密碼確認
    if ($password !== $confirm_password) {
        echo json_encode(['status' => 0, 'error' => '密碼與確認密碼不一致']);
        exit;
    }

    // 驗證電子信箱格式
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 0, 'error' => '電子信箱格式錯誤']);
        exit;
    }

    // 檢查帳號是否已存在
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM UserInfo WHERE uid = ?");
    $stmt->execute([$uid]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['status' => 0, 'error' => '帳號已存在，請選擇其他帳號']);
        exit;
    }

    // 檢查電子信箱是否已存在
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM UserInfo WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['status' => 0, 'error' => '電子信箱已被使用']);
        exit;
    }

    // 密碼加密
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 新增用戶
    $stmt = $pdo->prepare("INSERT INTO UserInfo (uid, password, email, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$uid, $hashedPassword, $email]);

    echo json_encode(['status' => 1, 'message' => '註冊成功']);

} catch (Exception $e) {
    echo json_encode(['status' => -1, 'error' => '伺服器錯誤：' . $e->getMessage()]);
}
?>

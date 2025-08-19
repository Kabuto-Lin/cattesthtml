<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

try {
    // 清除所有 session 資料
    $_SESSION = array();
    
    // 銷毀 session
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-42000, '/');
    }
    
    session_destroy();
    
    echo json_encode(['status' => 1, 'message' => '登出成功']);
} catch (Exception $e) {
    echo json_encode(['status' => 0, 'error' => '登出失敗：' . $e->getMessage()]);
}
?>

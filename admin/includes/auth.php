<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/db.php';

function is_logged_in() {
    return isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
}

function admin_login($email, $password) {
    global $pdo;
    $sql = "SELECT * FROM m_pengguna WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['pass'])) {
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_id'] = $user['id_pengguna'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        return true;
    }
    return false;
}

function admin_logout() {
    session_destroy();
    header('Location: login.php');
    exit;
}

if (isset($_POST['admin-logout'])) {
    admin_logout();
}
?>

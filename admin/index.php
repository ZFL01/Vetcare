<?php
require_once '../includes/DAO_user.php';
require_once '../includes/DAO_dokter.php';
require_once '../src/config/config.php';
require_once '../includes/userService.php';

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$route = isset($_GET['access']) ? $_GET['access'] : '';

if(isset($_SESSION['user']) && $_SESSION['user']->getRole() === 'Admin'){
    $route = 'dashboard';
}else{
    $route = 'auth';
}

$isValid = true;
$notFound = '<div class="pt-32 pb-20 text-center"><h1 class="text-4xl font-bold text-gray-800">Page not found</h1></div>';

switch($route){
    case 'auth':
        $content = 'login.php';
        break;
    case 'register':
        $content = 'register.php';
        break;
    case 'dashboard':
        $content = 'admin_direct.php';
        break;
    case 'logout':
        session_destroy();
        header('Location: ?access=auth');
        exit;
    default:
        $isValid = false;
        break;
}

if($isValid){
    include_once $content;
}else{?>
    <div class="min-h-screen bg-gray-50" style="align-content: center;"><?php
    echo $notFound;
}
?>
    </div>
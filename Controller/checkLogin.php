<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../Model/repositories/UserRepository.php';

session_start();

$conn = Database::connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $userRepo = new UserRepository($conn);
    $user = $userRepo->getUserByUsername($username);

    if ($user && $password === $user->password) {
        $_SESSION['user'] = $user;
        header("Location: ../View/appointment.php");  // đường dẫn tới trang lịch
        exit;
    } else {
        $_SESSION['error'] = "Tên đăng nhập hoặc mật khẩu sai!";
        header("Location: ../View/login.php");
        exit;
    }
} else {
    header("Location: ../View/login.php");
    exit;
}

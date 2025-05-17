<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Calendar App</title>
    <link rel="stylesheet" href="./css/style2.css">
</head>
<header>
    <h1>📅 Calendar App</h1>
    <nav>
        <a href="/index.php?page=calendar">Lịch</a> |
        <a href="/index.php?page=add">Thêm cuộc hẹn</a> |
        <a href="/index.php?page=logout">Đăng xuất</a>
    </nav>
</header>

<?php
session_start();
if (isset($_SESSION['error'])) {
    echo "<p style='color:red; text-align:center'>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']);
}
?>

<body>
    <div class="container-login">
        <div class="main-content-login">
            <div class="login-header">
                <h2>Đăng nhập</h2>
            </div>
            <form method="POST" action="../Controller/checkLogin.php">
                <label>Tên đăng nhập:</label><br>
                <input type="text" name="username" required><br><br>

                <label>Mật khẩu:</label><br>
                <input type="password" name="password" required><br><br>

                <input type="submit" value="Đăng nhập">
            </form>
        </div>
    </div>

</body>
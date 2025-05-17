<?php
require_once __DIR__ . '/../Controller/AppointmentController.php';
require_once __DIR__ . '/../config/db.php';

session_start(); 

$error = '';
$success = '';

?>

<?php include 'Components/header.php'; ?>

<body>
    <div class="container">
        <div class="main-content">
            <div class="calendar">
                <div class="login-header">
                    <h2>Danh sách cuộc hẹn</h2>
                </div>
                <table border="1" cellpadding="10">
                    <tr>
                        <th>Tên</th>
                        <th>Địa điểm</th>
                        <th>Thời gian bắt đầu</th>
                        <th>Thời gian kết thúc</th>
                        <th>Chủ trì</th>
                    </tr>
                    <?php foreach ($appointments as $appt): ?>
                        <tr>
                            <td><?= htmlspecialchars($appt['name']) ?></td>
                            <td><?= htmlspecialchars($appt['location']) ?></td>
                            <td><?= $appt['start'] ?></td>
                            <td><?= $appt['end'] ?></td>
                            <td><?= htmlspecialchars($appt['host_name']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div class="add-appointment">
                <div class="login-header">
                    <h2>Thêm cuộc hẹn mới</h2>
                </div>

                <?php if (!empty($error)): ?>
                    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
                <?php elseif (!empty($success)): ?>
                    <p style="color:green;"><?= htmlspecialchars($success) ?></p>
                <?php endif; ?>

                <form method="POST" action="/Controller/AppointmentController.php">
                    <label>Tên cuộc hẹn:</label>
                    <input type="text" name="name" id="name" required>

                    <label>Địa điểm:</label>
                    <input type="text" name="location" required>

                    <label>Thời gian bắt đầu:</label>
                    <input type="datetime-local" name="start" id="start" required>

                    <label>Thời gian kết thúc:</label>
                    <input type="datetime-local" name="end" id="end" required>

                    <input type="submit" value="Tạo cuộc hẹn">
                </form>
            </div>
        </div>
    </div>
</body>
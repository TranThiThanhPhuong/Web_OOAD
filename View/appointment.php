<?php
require_once __DIR__ . '/../Model/repositories/AppointmentRepository.php';
session_start();

$repo = new AppointmentRepository();
$appointments = $repo->getAllAppointments();

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
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

                <?php if ($error): ?>
                    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
                <?php elseif ($success): ?>
                    <p style="color:green;"><?= htmlspecialchars($success) ?></p>
                <?php endif; ?>

                <form method="POST" action="../Controller/AppointmentController.php?action=add" onsubmit="return validateForm()">
                    <label>Tên cuộc hẹn:</label>
                    <input type="text" name="name" id="name">

                    <label>Địa điểm:</label>
                    <input type="text" name="location">

                    <label>Thời gian bắt đầu:</label>
                    <input type="datetime-local" name="start" id="start">

                    <label>Thời gian kết thúc:</label>
                    <input type="datetime-local" name="end" id="end">

                    <input type="submit" value="Tạo cuộc hẹn">
                </form>
            </div>
        </div>
    </div>
</body>
<script>
    function validateForm() {
        const name = document.getElementById('name').value.trim();
        const location = document.querySelector('input[name="location"]').value.trim();
        const startInput = document.getElementById('start').value;
        const endInput = document.getElementById('end').value;

        if (!name) {
            alert("Vui lòng nhập tên cuộc hẹn.");
            return false;
        }

        if (!location) {
            alert("Vui lòng nhập địa điểm.");
            return false;
        }

        if (!startInput || !endInput) {
            alert("Vui lòng nhập đầy đủ thời gian bắt đầu và kết thúc.");
            return false;
        }

        const currentStart = new Date(startInput);
        const currentEnd = new Date(endInput);

        if (isNaN(currentStart.getTime()) || isNaN(currentEnd.getTime())) {
            alert("Thời gian không hợp lệ.");
            return false;
        }

        if (currentStart >= currentEnd) {
            alert("Thời gian kết thúc phải lớn hơn thời gian bắt đầu.");
            return false;
        }
for (let appt of existingAppointments) {
            const apptStart = new Date(appt.start);
            const apptEnd = new Date(appt.end);

            // Trường hợp 11: Trùng tên và thời lượng
            const durationCurrent = (currentEnd - currentStart) / (1000 * 60); // phút
            const durationAppt = (apptEnd - apptStart) / (1000 * 60);

            if (appt.name === name && durationCurrent === durationAppt) {
                if (confirm("Đã tồn tại một cuộc họp nhóm giống như vậy. Bạn có muốn tham gia không?")) {
                    const form = document.querySelector('form');
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'join_group';
                    hiddenInput.value = 'true';
                    form.appendChild(hiddenInput);
                    return true;
                } else {
                    return false;
                }
            }

            // Trường hợp 22: Trùng thời gian
            if ((currentStart < apptEnd) && (currentEnd > apptStart)) {
                const userChoice = confirm("Bạn đã có cuộc hẹn vào thời gian này. Bạn muốn thay thế cuộc hẹn trước không?\n\nChọn OK để thay thế.\nChọn Hủy để nhập thời gian khác.");

                if (userChoice) {
                    const form = document.querySelector('form');
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'replace_conflict';
                    hiddenInput.value = appt.id; // Gửi ID của cuộc hẹn bị trùng để xóa
                    form.appendChild(hiddenInput);
                    return true;
                } else {
                    return false; // cho người dùng nhập thời gian khác
                }
            }

        }

        return true; // Không có xung đột nào
        
    }

    const existingAppointments = <?= json_encode($appointments) ?>;
</script>
<?php
require_once __DIR__ . '/../Model/repositories/AppointmentRepository.php';

class AppointmentController
{
    private $appointmentRepo;

    public function __construct()
    {
        $this->appointmentRepo = new AppointmentRepository();
    }

    public function addAppointment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $name = trim($_POST['name']);
            $location = trim($_POST['location']);
            $start = $_POST['start'];
            $end = $_POST['end'];
            $hostId = $_SESSION['user_id'] ?? 1;

            $startTimestamp = strtotime($start);
            $endTimestamp = strtotime($end);

            if ($name === '' || $startTimestamp === false || $endTimestamp === false || $startTimestamp >= $endTimestamp) {
                die('Dữ liệu không hợp lệ');
            }

            $repo = new AppointmentRepository();

            // 1. Kiểm tra trùng thời gian
            $conflicts = $repo->findConflictsByTime($hostId, $start, $end);
            if (!empty($conflicts)) {
                // Nếu trùng, hiển thị thông báo - bạn có thể redirect lại với session message hoặc truyền biến error
                die("Bạn đã có một cuộc hẹn khác trong khoảng thời gian này. Vui lòng chọn thời gian khác hoặc thay thế.");
            }

            // 2. Kiểm tra có cuộc họp nhóm trùng tên và thời lượng
            $groupMeeting = $repo->findGroupMeetingByNameAndDuration($name, $start, $end);
            if ($groupMeeting) {
                // Hiển thị form hỏi người dùng có muốn tham gia cuộc họp nhóm không
                // Đây là ví dụ cơ bản, bạn có thể làm AJAX hoặc redirect tới trang xác nhận
                echo '<form method="POST" action="">
                <p>Cuộc họp nhóm cùng tên và thời lượng đã tồn tại. Bạn có muốn tham gia không?</p>
                <input type="hidden" name="join_group_meeting" value="1">
                <input type="hidden" name="appointment_id" value="' . $groupMeeting['id'] . '">
                <button type="submit">Đồng ý</button>
                <button type="button" onclick="window.location.href=\'/View/appointment.php\'">Hủy</button>
            </form>';
                exit();
            }

            // 3. Nếu là yêu cầu tham gia cuộc họp nhóm
            if (isset($_POST['join_group_meeting']) && $_POST['join_group_meeting'] == '1') {
                $appointmentId = $_POST['appointment_id'];
                $repo->addParticipant($appointmentId, $hostId);
                header('Location: /View/appointment.php?joined=1');
                exit();
            }

            // 4. Nếu không trùng và không tham gia nhóm thì thêm cuộc hẹn mới
            $repo->addAppointment($name, $location, $start, $end, $hostId);
            header('Location: /View/appointment.php?success=1');
            exit();
        }

        include __DIR__ . '/../View/appointment.php';
    }
}

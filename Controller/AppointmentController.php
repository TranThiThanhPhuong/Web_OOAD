<?php
require_once __DIR__ . '/../Model/repositories/AppointmentRepository.php';
session_start();

$action = $_GET['action'] ?? '';
$repo = new AppointmentRepository();

function redirectWithMessage($type, $message) {
    header("Location: ../View/appointment.php?{$type}=" . urlencode($message));
    exit();
}

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $start = $_POST['start'] ?? '';
    $end = $_POST['end'] ?? '';
    $hostId = $_SESSION['user_id'] ?? 1;
    $joinGroup = ($_POST['join_group'] ?? '') === 'true';

    // Validate input
    $startTime = strtotime($start);
    $endTime = strtotime($end);
    if ($name === '' || !$startTime || !$endTime || $startTime >= $endTime) {
        redirectWithMessage('error', 'Thời gian không hợp lệ.');
    }

    $duration = $endTime - $startTime;

    // Check time conflict
    if (!empty($repo->findByTimeRange($hostId, $start, $end))) {
        redirectWithMessage('error', 'Bạn đã có một cuộc hẹn khác trong khoảng thời gian này.');
    }

    // Check for group meeting
    $groupMeeting = $repo->findGroupMeetingByNameAndDuration($name, $duration);
    if ($groupMeeting) {
        if ($joinGroup) {
            $repo->addUserToGroupMeeting($hostId, $groupMeeting['id']);
            redirectWithMessage('success', 'Đã tham gia cuộc họp nhóm.');
        } else {
            redirectWithMessage('error', 'Cuộc họp nhóm đã tồn tại. Bạn có muốn tham gia?');
        }
    }

    // Create new appointment
    $repo->addAppointment($name, $location, $start, $end, $hostId);
    redirectWithMessage('success', 'Thêm cuộc hẹn thành công.');
}

redirectWithMessage('error', 'Hành động không hợp lệ.');

<?php
require_once __DIR__ . '/../Appointment.php';
require_once __DIR__ . '/../../config/db.php';

class AppointmentRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    public function create(Appointment $a)
    {
        $stmt = $this->conn->prepare("INSERT INTO appointments (id, name, location, start, end, host_id) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$a->id, $a->name, $a->location, $a->start, $a->end, $a->host_id]);
    }

    public function findByTimeRange($userId, $start, $end)
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM appointments 
            JOIN participants ON appointments.id = participants.appointmentId 
            WHERE participants.userId = ? AND ((start < ? AND end > ?) OR (start >= ? AND start < ?))
        ");
        $stmt->execute([$userId, $end, $start, $start, $end]);
        return $stmt->fetchAll();
    }

    public function getAllAppointments()
    {
        $stmt = $this->conn->query("SELECT a.*, u.name AS host_name 
                                    FROM appointments a 
                                    JOIN users u ON a.host_id = u.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addAppointment($name, $location, $start, $end, $hostId)
    {
        $stmt = $this->conn->prepare("INSERT INTO appointments (name, location, start, end, host_id) 
                                      VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $location, $start, $end, $hostId]);
    }

    // Kiểm tra cuộc hẹn trùng thời gian của user
    public function findConflictsByTime($userId, $start, $end)
    {
        $stmt = $this->conn->prepare("
        SELECT * FROM appointments 
        JOIN participants ON appointments.id = participants.appointmentId 
        WHERE participants.userId = ? 
        AND ((start < ? AND end > ?) OR (start >= ? AND start < ?))
    ");
        $stmt->execute([$userId, $end, $start, $start, $end]);
        return $stmt->fetchAll();
    }

    // Tìm cuộc hẹn nhóm có cùng tên và cùng thời lượng
    public function findGroupMeetingByNameAndDuration($name, $start, $end)
    {
        $stmt = $this->conn->prepare("
        SELECT * FROM appointments 
        WHERE name = ? AND start = ? AND end = ?
    ");
        $stmt->execute([$name, $start, $end]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm người dùng vào cuộc họp nhóm (bảng participants)
    public function addParticipant($appointmentId, $userId)
    {
        $stmt = $this->conn->prepare("INSERT INTO participants (appointmentId, userId) VALUES (?, ?)");
        $stmt->execute([$appointmentId, $userId]);
    }
}

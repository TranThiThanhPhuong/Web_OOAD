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

    public function findGroupMeetingByNameAndDuration($name, $durationInSeconds)
    {
        $stmt = $this->conn->prepare("
        SELECT *, (UNIX_TIMESTAMP(end) - UNIX_TIMESTAMP(start)) as duration 
        FROM appointments 
        WHERE name = ?
        HAVING ABS(duration - ?) <= 60
    ");
        $stmt->execute([$name, $durationInSeconds]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về 1 cuộc họp nhóm đầu tiên tìm được
    }

    public function addUserToGroupMeeting($userId, $appointmentId)
    {
        // Kiểm tra xem người dùng đã có mặt trong cuộc họp chưa
        $checkStmt = $this->conn->prepare("
        SELECT * FROM participants WHERE userId = ? AND appointmentId = ?
    ");
        $checkStmt->execute([$userId, $appointmentId]);
        if ($checkStmt->fetch()) {
            return; // đã tham gia rồi
        }

        $stmt = $this->conn->prepare("
        INSERT INTO participants (userId, appointmentId) VALUES (?, ?)
    ");
        $stmt->execute([$userId, $appointmentId]);
    }

    public function deleteAppointment($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM appointments WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

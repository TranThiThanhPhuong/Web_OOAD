<?php
require_once __DIR__ . '/../Appointment.php';
require_once __DIR__ . '/../../config/db.php';

class AppointmentRepository {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect();
    }

    public function create(Appointment $a) {
        $stmt = $this->conn->prepare("INSERT INTO appointments (id, name, location, start, end, host_id) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$a->id, $a->name, $a->location, $a->start, $a->end, $a->host_id]);
    }

    public function findByTimeRange($userId, $start, $end) {
        $stmt = $this->conn->prepare("
            SELECT * FROM appointments 
            JOIN participants ON appointments.id = participants.appointmentId 
            WHERE participants.userId = ? AND ((start < ? AND end > ?) OR (start >= ? AND start < ?))
        ");
        $stmt->execute([$userId, $end, $start, $start, $end]);
        return $stmt->fetchAll();
    }
}
?>

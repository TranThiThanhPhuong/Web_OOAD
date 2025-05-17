<?php
class Participant {
    public $userId;
    public $appointmentId;

    public function __construct($userId, $appointmentId) {
        $this->userId = $userId;
        $this->appointmentId = $appointmentId;
    }
}
?>

<?php
class Reminder {
    public $userId;
    public $appointmentId;
    public $remindTime;

    public function __construct($userId, $appointmentId, $remindTime) {
        $this->userId = $userId;
        $this->appointmentId = $appointmentId;
        $this->remindTime = $remindTime;
    }
}
?>

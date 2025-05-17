<?php
class Appointment {
    public $id;
    public $name;
    public $location;
    public $start;
    public $end;
    public $host_id;

    public function __construct($id, $name, $location, $start, $end, $host_id) {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
        $this->start = $start;
        $this->end = $end;
        $this->host_id = $host_id;
    }
}
?>

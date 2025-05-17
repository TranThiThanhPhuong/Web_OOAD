<?php
class User {
    public $id;
    public $username;
    public $password;
    public $name;

    public function __construct($id, $username, $password, $name) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->name = $name;
    }
}
?>

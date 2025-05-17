<?php
require_once __DIR__ . '/../User.php';
require_once __DIR__ . '/../../config/db.php';

class UserRepository {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect();
    }

    public function findByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $row = $stmt->fetch();
        if ($row) {
            return new User($row['id'], $row['username'], $row['password'], $row['name']);
        }
        return null;
    }

    public function createUser(User $user) {
        $stmt = $this->conn->prepare("INSERT INTO users (id, username, password, name) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user->id, $user->username, $user->password, $user->name]);
    }
}
?>

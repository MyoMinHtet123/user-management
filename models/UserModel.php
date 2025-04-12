<?php
require_once __DIR__ . '/../config/db_connection.php';

class UserModel {
    private $db;

    public function __construct() {
        $this->db = Db_connection::getInstance()->getConnection();
    }

    /**
     * Get all users
     * @return array
     */
    public function getAllUsers(): array  {
        $stmt = $this->db->prepare("SELECT * FROM users");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all();
    }

    /**
     * Get user by Id
     * @param mixed $id
     * @return array|bool|null
     */
    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Get User by username
     * @return mixed
     */
    public function getUserByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Get recent users based on creation date
     * @return array
     */
    public function getRecentUsers($limit = 5): array {
        $sql = "SELECT * FROM users ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limit);

        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        $stmt->close();
        return $users;
    }

    /**
     * Create a new user
     * @param mixed $name
     * @param mixed $email
     * @param mixed $username
     * @param mixed $password
     * @param mixed $roleId
     * @return bool
     */
    public function createUser($name,$email, $username, $password, $roleId): bool {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, username, password, role_id) VALUES (?, ?, ?, ?, ?)");
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("ssssi", $name, $email, $username, $hashedPassword, $roleId);
        return $stmt->execute();
    }
}

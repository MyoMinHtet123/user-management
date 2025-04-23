<?php
require_once __DIR__ . '/../config/db_connection.php';

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = Db_connection::getInstance()->getConnection();
    }

    /**
     * Get all users
     * @return array
     */
    public function getAllUsers(): array
    {
        $stmt = $this->db->prepare("
        SELECT u.*, r.name as role_name 
        FROM users u
        LEFT JOIN roles r ON u.role_id = r.id
    ");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get user by Id
     * @param mixed $id
     * @return array|bool|null
     */
    public function getUserById($id)
    {
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
    public function getUserByUsername($username)
    {
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
    public function getRecentUsers($limit = 5): array
    {
        $sql = "SELECT 
                u.id,
                u.name,
                u.username,
                u.email,
                u.role_id,
                u.created_at,
                r.name AS role_name
            FROM 
                users u
            LEFT JOIN 
                roles r ON u.role_id = r.id
            ORDER BY 
                u.created_at DESC
            LIMIT ?";

        try {
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }

            $limit = (int)$limit; // Ensure integer type
            $stmt->bind_param("i", $limit);

            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            $result = $stmt->get_result();
            $users = [];

            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }

            return $users;
        } catch (Exception $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
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
    public function createUser($name, $email, $username, $password, $roleId): bool
    {
        $stmt           = $this->db->prepare("INSERT INTO users (name, email, username, password, role_id) VALUES (?, ?, ?, ?, ?)");
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("ssssi", $name, $email, $username, $hashedPassword, $roleId);
        return $stmt->execute();
    }

    /**
     * Update a User
     * @param int $id
     * @param string $name
     * @param string $email
     * @param string $username
     * @param int $roleId
     * @return bool
     */
    public function updateUser(int $id, string $name, string $email, string $username, int $roleId): bool
    {
        try {
            // Update without changing password
            $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ?, username = ?, role_id = ? WHERE id = ?");
            $stmt->bind_param("sssii", $name, $email, $username, $roleId, $id);

            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a user
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }
}

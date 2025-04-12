<?php
require_once __DIR__ . '/../config/db_connection.php';
class RoleModel
{
    private $db;

    public function __construct()
    {
        $this->db = Db_connection::getInstance()->getConnection();
    }

    // Get all roles
    public function getAllRoles(): array
    {
        $query = "SELECT * FROM roles";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all();
    }

    /**
     * Create a new role
     * @param mixed $roleName
     * @return bool|int|string
     */
    public function createRole($roleName)
    {
        // Check if the role already exists (optional)
        $checkQuery = "SELECT id FROM roles WHERE name = ?";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->bind_param("s", $roleName);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            return false; // Role already exists
        }

        // Insert new role
        $insertQuery = "INSERT INTO roles (name) VALUES (?)";
        $stmt = $this->db->prepare($insertQuery);

        if (!$stmt) {
            return false; // Prepare failed
        }

        $stmt->bind_param("s", $roleName);

        if ($stmt->execute()) {
            return $this->db->insert_id; // Return the new role ID
        }

        return false; // Insert failed
    }
}

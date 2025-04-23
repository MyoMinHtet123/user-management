<?php
require_once __DIR__ . '/../config/db_connection.php';
class RoleModel
{
    private $db;

    public function __construct()
    {
        $this->db = Db_connection::getInstance()->getConnection();
    }

    // Get role by Id
    public function getRoleById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Get role by name
    public function getRoleByName($roleName)
    {
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE name = ?");
        $stmt->bind_param("s", $roleName);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
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

    // Update role name
    public function updateRole($roleId, $roleName)
    {
        $updateStmt = $this->db->prepare("UPDATE roles SET name = ? WHERE id = ?");
        $updateStmt->bind_param("si", $roleName, $roleId);
        if ($updateStmt->execute()) {
            return $this->getRoleById($roleId);
        }
    }

    // Delete role 
    public function deleteRole($roleId)
    {
        $deleteStmt = $this->db->prepare("DELETE FROM roles WHERE id = ?");
        $deleteStmt->bind_param('i', $roleId);
        return $deleteStmt->execute();
    }
}

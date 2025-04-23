<?php
require_once __DIR__ . '/../config/db_connection.php';

class RolePermModel
{
    private $db;

    public function __construct()
    {
        $this->db = Db_connection::getInstance()->getConnection();
    }

    public function getPerByRoleId($roleId)
    {
        $query = "
    SELECT 
        p.id AS permission_id,
        p.name AS permission_name,
        f.id AS feature_id,
        f.name AS feature_name
    FROM role_permissions rp
    JOIN permissions p ON rp.permissions_id = p.id
    JOIN features f ON p.feature_id = f.id
    WHERE rp.role_id = ?
    ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $roleId);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $permissions = [];

            while ($row = $result->fetch_assoc()) {
                $permissions[] = $row;
            }

            return $permissions;
        }
    }

    // Assign Permissions to role
    public function assignPermissionsToRole($roleId, $permissionId)
    {
        $query = "INSERT INTO role_permissions  (role_id, permissions_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $roleId, $permissionId);
        return $stmt->execute();
    }

    // Delete Role permissions
    public function deleteRolePerms($roleId)
    {
        $query = "DELETE FROM role_permissions WHERE role_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $roleId);
        return $stmt->execute();
    }
}

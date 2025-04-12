<?php
require_once __DIR__ . '/../config/db_connection.php';

class RolePermModel {
    private $db;

    public function __construct() {
        $this->db = Db_connection::getInstance()->getConnection();
    }

    public function assignPermissionsToRole($roleId, $permissionId){
        $query = "INSERT INTO role_permissions  (role_id, permissions_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $roleId, $permissionId);
        if($stmt->execute()) {
            return $this->db->insert_id;
        }
    }
}

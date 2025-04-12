<?php
require_once __DIR__ . '/../config/db_connection.php';

class PermissionsModel {
    private $db;

    public function __construct() {
        $this->db = Db_connection::getInstance()->getConnection();
    }

    /**
     * Get Permissions
     * @return array
     */
    public function getAllPermissions() {
        $query = "SELECT * FROM permissions";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Add permissions
     *
     */
    public function addPermissions($permission, $featureId) {
        $query = "INSERT INTO permissions  (name, feature_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $permission, $featureId);
        if($stmt->execute()) {
            return $this->db->insert_id;
        }
    }
}

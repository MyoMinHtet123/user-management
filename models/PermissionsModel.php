<?php
require_once __DIR__ . '/../config/db_connection.php';

class PermissionsModel
{
    private $db;

    public function __construct()
    {
        $this->db = Db_connection::getInstance()->getConnection();
    }

    /**
     * Get Permissions
     * @return array
     */
    public function getAllPermissions()
    {
        $query = "SELECT * FROM permissions";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get permission By Name and Feature Id
    public function getPermByNameAndId($permName, $featureId)
    {
        $query = "SELECT id FROM permissions WHERE name = ? AND feature_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $permName, $featureId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}

<?php
require_once __DIR__ . '/../config/db_connection.php';
class FeaturesModel
{
    private $db;

    public function __construct()
    {
        $this->db = Db_connection::getInstance()->getConnection();
    }

    /**
     * Get all features
     * @return array
     */
    public function getAllFeatures()
    {
        $query = "SELECT * FROM features";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get feature by name
     * @param mixed $name
     * @return array|bool|null
     */
    public function getFeatureByName($name)
    {
        $query = "SELECT * FROM features WHERE name = ?";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("s", $name);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Get Feature by id
    public function getFeatureById($featureId)
    {
        $query = "SELECT * FROM features WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $featureId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}

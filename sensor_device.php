<?php
class SensorDevice {
    private $conn;
    private $table_name = "sensor_device";

    public $id;
    public $device_id;
    public $admin_id;
    public $station_code;

    public function __construct($db) {
        $this->conn = $db;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET device_id=:device_id, admin_id=:admin_id, station_code=:station_code";

        $stmt = $this->conn->prepare($query);

        $this->device_id = htmlspecialchars(strip_tags($this->device_id));
        $this->admin_id = htmlspecialchars(strip_tags($this->admin_id));
        $this->station_code = htmlspecialchars(strip_tags($this->station_code));

        $stmt->bindParam(":device_id", $this->device_id);
        $stmt->bindParam(":admin_id", $this->admin_id);
        $stmt->bindParam(":station_code", $this->station_code);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>

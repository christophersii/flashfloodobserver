<?php
class Station {
    private $conn;
    private $table_name = "station";

    public $station_code;
    public $station_name;
    public $latitude;
    public $longitude;
    public $threshold_alert;
    public $threshold_warning;
    public $threshold_danger;
    public $drainage_depth;
    public $admin_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET station_name=:station_name, latitude=:latitude, longitude=:longitude, threshold_alert=:threshold_alert, threshold_warning=:threshold_warning, threshold_danger=:threshold_danger, drainage_depth=:drainage_depth, admin_id=:admin_id";

        $stmt = $this->conn->prepare($query);

        $this->station_name = htmlspecialchars(strip_tags($this->station_name));
        $this->latitude = htmlspecialchars(strip_tags($this->latitude));
        $this->longitude = htmlspecialchars(strip_tags($this->longitude));
        $this->threshold_alert = htmlspecialchars(strip_tags($this->threshold_alert));
        $this->threshold_warning = htmlspecialchars(strip_tags($this->threshold_warning));
        $this->threshold_danger = htmlspecialchars(strip_tags($this->threshold_danger));
        $this->drainage_depth = htmlspecialchars(strip_tags($this->drainage_depth));
        $this->admin_id = htmlspecialchars(strip_tags($this->admin_id));

        $stmt->bindParam(":station_name", $this->station_name);
        $stmt->bindParam(":latitude", $this->latitude);
        $stmt->bindParam(":longitude", $this->longitude);
        $stmt->bindParam(":threshold_alert", $this->threshold_alert);
        $stmt->bindParam(":threshold_warning", $this->threshold_warning);
        $stmt->bindParam(":threshold_danger", $this->threshold_danger);
        $stmt->bindParam(":drainage_depth", $this->drainage_depth);
        $stmt->bindParam(":admin_id", $this->admin_id);

        if ($stmt->execute()) {
            $this->station_code = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }
}
?>

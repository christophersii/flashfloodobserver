<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require 'db.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$station_code = $request->station_code;

$statement = $pdo->prepare("
  SELECT *
  FROM (
    SELECT *
    FROM sensor_device
    WHERE station_code = :station_code
  ) AS d
  JOIN (
    SELECT device_id, water_level, reading_time
    FROM sensor_reading
    WHERE device_id IN (
      SELECT device_id
      FROM sensor_device
      WHERE station_code = :station_code
    )
    ORDER BY reading_time DESC
  ) AS r ON d.device_id = r.device_id
  GROUP BY d.device_id
");

$statement->execute([
  ':station_code' => $station_code,
]);

$result = $statement->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);

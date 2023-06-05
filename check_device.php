1   <?php
2   header("Access-Control-Allow-Origin: *");
3   header("Content-Type: application/json; charset=UTF-8");
4   header("Access-Control-Allow-Methods: POST");
5   header("Access-Control-Max-Age: 3600");
6   header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
7
8   include_once 'config.php';
9
10  // get posted data
11  $device_id = $_POST['device_id'];
12
13  // make sure data is not empty
14  if(!empty($device_id)){
15      try {
16          // get database connection
17          $database = new Database();
18          $db = $database->getConnection();
19
20          // query to check if device_id exists
21          $query = "SELECT COUNT(*) FROM sensor_device WHERE device_id = :device_id";
22          $stmt = $db->prepare($query);
23
24          // bind id of product to be updated
25          $stmt->bindParam(":device_id", $device_id);
26
27          // execute query
28          $stmt->execute();
29
30          // get retrieved row
31          $num = $stmt->fetchColumn();
32
33          if($num>0){
34              // device exists
35              http_response_code(200);
36              echo json_encode(array("exists" => true));
37          }
38          else{
39              // device does not exist
40              http_response_code(200);
41              echo json_encode(array("exists" => false));
42          }
43      }
44      catch(PDOException $exception){
45          // on error
46          http_response_code(500);
47          echo json_encode(array("message" => "Error processing your request.", "error" => $exception->getMessage()));
48      }
49  }
50  else{
51      // data is incomplete
52      http_response_code(400);
53      echo json_encode(array("message" => "Device id not provided."));
54  }
55  ?>

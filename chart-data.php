<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("config.php");

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT id, water_level, rainfall, temperature, humidity, reading_time FROM sensor_reading order by reading_time desc limit 40";

$result = $conn->query($sql);

while ($data = $result->fetch_assoc()){
    $sensor_data[] = $data;
}

$readings_time = array_column($sensor_data, 'reading_time');

// ******* Uncomment to convert readings time array to your timezone ********
$i = 0;
foreach ($readings_time as $reading){
    // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
    //$readings_time[$i] = date("Y-m-d H:i:s", strtotime("$reading - 1 hours"));
    // Uncomment to set timezone to + 4 hours (you can change 4 to any number)
    $readings_time[$i] = date("Y-m-d H:i:s", strtotime("$reading + 8 hours"));
    $i += 1;
}

$water_level = json_encode(array_reverse(array_column($sensor_data, 'water_level')), JSON_NUMERIC_CHECK);
$rainfall = json_encode(array_reverse(array_column($sensor_data, 'rainfall')), JSON_NUMERIC_CHECK);
$temperature = json_encode(array_reverse(array_column($sensor_data, 'temperature')), JSON_NUMERIC_CHECK);
$humidity = json_encode(array_reverse(array_column($sensor_data, 'humidity')), JSON_NUMERIC_CHECK);
$reading_time = json_encode(array_reverse($readings_time), JSON_NUMERIC_CHECK);

/*echo $value1;
echo $value2;
echo $value3;
echo $reading_time;*/

$result->free();
$conn->close();
?>

<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <style>
    body {
      min-width: 310px;
    	max-width: 1280px;
    	height: 500px;
      margin: 0 auto;
    }
    h2 {
      font-family: Arial;
      font-size: 2.5rem;
      text-align: center;
    }
  </style>
  <body>
    <h2>Flash Flood Observer</h2>
    <div id="chart-water_level" class="container"></div>
    <div id="chart-rainfall" class="container"></div>
    <div id="chart-temperature" class="container"></div>
    <div id="chart-humidity" class="container"></div>
<script>

var water_level = <?php echo $water_level; ?>;
var rainfall = <?php echo $rainfall; ?>;
var temperature = <?php echo $temperature; ?>;
var humidity = <?php echo $humidity; ?>;
var reading_time = <?php echo $reading_time; ?>;

var chartW = new Highcharts.Chart({
  chart:{ renderTo : 'chart-water_level' },
  title: { text: 'Water Level' },
  series: [{
    showInLegend: false,
    data: water_level
  }],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    },
    series: { color: '#059e8a' }
  },
  xAxis: { 
    type: 'datetime',
    categories: reading_time
  },
  yAxis: {
    title: { text: 'Water Level (millimeter)' }
    //title: { text: 'Temperature (Fahrenheit)' }
  },
  credits: { enabled: false }
});

var chartR = new Highcharts.Chart({
  chart:{ renderTo:'chart-rainfall' },
  title: { text: 'Rain' },
  series: [{
    showInLegend: false,
    data: rainfall
  }],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    }
  },
  xAxis: {
    type: 'datetime',
    //dateTimeLabelFormats: { second: '%H:%M:%S' },
    categories: reading_time
  },
  yAxis: {
    title: { text: 'Rain Presence (0= Rain/ 1= Not Rain)' }
  },
  credits: { enabled: false }
});


var chartT = new Highcharts.Chart({
  chart:{ renderTo:'chart-temperature' },
  title: { text: 'Temperature' },
  series: [{
    showInLegend: false,
    data: temperature
  }],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    },
    series: { color: '#18009c' }
  },
  xAxis: {
    type: 'datetime',
    categories: reading_time
  },
  yAxis: {
    title: { text: 'Temperature (°C)' }
  },
  credits: { enabled: false }
});

var chartH = new Highcharts.Chart({
  chart:{ renderTo:'chart-humidity' },
  title: { text: 'Humidity' },
  series: [{
    showInLegend: false,
    data: humidity
  }],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    },
    series: { color: '#18009c' }
  },
  xAxis: {
    type: 'datetime',
    categories: reading_time
  },
  yAxis: {
    title: { text: 'Humidity (%)' }
  },
  credits: { enabled: false }
});
</script>
</body>
</html>

<!--
 
Rui Santos
 
Complete project details at https://RandomNerdTutorials.com
 
Permission is hereby granted, free of charge, to any person obtaining a copy
 
of this software and associated documentation files.
 
The above copyright notice and this permission notice shall be included in all
 
copies or substantial portions of the Software.
 
-->
 
<?php
 
$servername = "localhost";
 
// Здесь указываем название БД
 
$dbname = "f0688902_Database";
 
// Указываем имя пользователя
 
$username = "f0688902_Victor_Samoilov";
 
// Указываем пароль
 
$password = "etegzaifef";
 
 
 
// Создаем соединение
 
$conn = new mysqli($servername, $username, $password, $dbname);
 
// Проверяем соединение
 
if ($conn->connect_error) {
 
die("Connection failed: " . $conn->connect_error);
 
}
 
 
 
$sql = "SELECT id, value1, reading_time FROM Sensor order by reading_time desc limit 40";
 
 
 
$result = $conn->query($sql);
 
 
 
while ($data = $result->fetch_assoc()){
 
$sensor_data[] = $data;
 
}
 
 
 
$readings_time = array_column($sensor_data, 'reading_time');
 
 
 
// ******* Раскомментируйте одну из строк для конвертации время для вашей зоны Московское – UTC-2, оставляем 0********
 
$i = 0;
 
foreach ($readings_time as $reading){
 
// Для зоны UTC 1
 
$readings_time[$i] = date("Y-m-d H:i:s", strtotime("$reading + 6 hours"));
 
$i += 1;
 
}
 
 
 
$value1 = json_encode(array_reverse(array_column($sensor_data, 'value1')), JSON_NUMERIC_CHECK);
 
$reading_time = json_encode(array_reverse($readings_time), JSON_NUMERIC_CHECK);
 
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
 
<h2>ESP Chart</h2>
 
<div id="chart" class="container"></div>
 
 
<script>
 
 
 
var value1 = <?php echo $value1; ?>;
 
 
var reading_time = <?php echo $reading_time; ?>;
 
 
 
var chartT = new Highcharts.Chart({
 
chart:{ renderTo : 'chart' },
 
title: { text: 'ESP Rand' },
 
series: [{
 
showInLegend: false,
 
data: value1
 
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
 
title: { text: 'Rand' }
 
//title: { text: 'Temperature (Fahrenheit)' }
 
},
 
credits: { enabled: false }
 
});
 
 
 
</script>
 
</body>
 
</html>
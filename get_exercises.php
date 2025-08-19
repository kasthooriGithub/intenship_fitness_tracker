<?php
header('Content-Type: application/json');
include 'config.php';

$result = $conn->query("
  SELECT exercise_date, SUM(duration) as duration 
  FROM exercises 
  GROUP BY exercise_date
  ORDER BY exercise_date
");

$data = [];
while($row = $result->fetch_assoc()) {
  $data[] = [
    'exercise_date' => date('M j', strtotime($row['exercise_date'])),
    'duration' => (int)$row['duration']
  ];
}

echo json_encode($data);
$conn->close();
?>
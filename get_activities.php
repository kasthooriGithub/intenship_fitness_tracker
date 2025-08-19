<?php
require 'config.php';
$query = "(SELECT 'Exercise' as type, name, exercise_date as date, 
          CONCAT(duration, ' mins, ', calories, ' cal') as details
          FROM exercises ORDER BY exercise_date DESC LIMIT 5)
          UNION
          (SELECT 'Event' as type, event_name as name, 
          event_date as date, description as details
          FROM events ORDER BY event_date DESC LIMIT 5)
          ORDER BY date DESC LIMIT 10";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['type']}</td>
                <td>{$row['name']}</td>
                <td>{$row['date']}</td>
                <td>{$row['details']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No activities found</td></tr>";
}
?>
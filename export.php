<?php
require_once 'config.php';

// Get search and filter parameters
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$filter_date = isset($_GET['filter_date']) ? $conn->real_escape_string($_GET['filter_date']) : '';

// Build WHERE clause
$where = '';
if (!empty($search)) {
    $where .= "WHERE exercise_name LIKE '%$search%'";
}
if (!empty($filter_date)) {
    $where .= empty($where) ? "WHERE" : " AND";
    $where .= " exercise_date = '$filter_date'";
}

// Fetch data for export
$sql = "SELECT * FROM exercises $where ORDER BY exercise_date DESC";
$result = $conn->query($sql);

// Set headers for download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=fitness_export_' . date('Y-m-d') . '.csv');

// Create output stream
$output = fopen('php://output', 'w');

// Add CSV headers
fputcsv($output, ['Date', 'Exercise Name', 'Duration (min)', 'Calories Burned']);

// Add data rows
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['exercise_date'],
            $row['exercise_name'],
            $row['duration'],
            $row['calories']
        ]);
    }
}

fclose($output);
exit();
?>
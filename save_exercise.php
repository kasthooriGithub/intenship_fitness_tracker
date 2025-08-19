<?php
// Ensure no output before headers
ob_start();
ini_set('display_errors', 0); // Disable error display for production
header('Content-Type: application/json');

require 'config.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate required fields
    $required = ['exercise_name', 'duration', 'calories', 'exercise_date'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    // Process data
    $exercise_name = $_POST['exercise_name'] === 'Other' 
        ? trim($_POST['custom_exercise'] ?? '')
        : trim($_POST['exercise_name']);
    
    $duration = (int)$_POST['duration'];
    $calories = (int)$_POST['calories'];
    $exercise_date = $_POST['exercise_date'];

    // Validate values
    if ($duration <= 0 || $calories <= 0) {
        throw new Exception('Duration and calories must be positive numbers');
    }

    // Database insertion
    $stmt = $conn->prepare("INSERT INTO exercises (exercise_name, duration, calories, exercise_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siis", $exercise_name, $duration, $calories, $exercise_date);
    
    if (!$stmt->execute()) {
        throw new Exception('Database error: ' . $stmt->error);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Exercise saved successfully!',
        'data' => [
            'exercise_name' => $exercise_name,
            'duration' => $duration,
            'calories' => $calories,
            'date' => $exercise_date
        ]
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    $stmt->close();
    $conn->close();
    ob_end_flush();
}
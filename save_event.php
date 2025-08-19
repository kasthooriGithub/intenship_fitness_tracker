<?php
$conn = new mysqli("localhost", "root", "", "fitness_tracker");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $_POST['exercise_name'];
    $duration = $_POST['duration'];
    $calories = $_POST['calories'];
    $date     = $_POST['exercise_date'];

    $sql = "INSERT INTO exercises (name, duration, calories, exercise_date) 
            VALUES ('$name', '$duration', '$calories', '$date')";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to form.php with success message
        header("Location: form.php?success=1");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

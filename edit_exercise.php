<?php
require_once 'config.php';

// Get exercise data if ID is provided
$exercise = null;
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "SELECT * FROM exercises WHERE id = $id";
    $result = $conn->query($sql);
    $exercise = $result->fetch_assoc();
}

// Handle form submission for editing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int)$_POST['id'];
    $name = $conn->real_escape_string($_POST['exercise_name']);
    $duration = (int)$_POST['duration'];
    $calories = (int)$_POST['calories'];
    $exercise_date = $conn->real_escape_string($_POST['exercise_date']);

    $stmt = $conn->prepare("UPDATE exercises SET exercise_name=?, duration=?, calories=?, exercise_date=? WHERE id=?");
    $stmt->bind_param("siisi", $name, $duration, $calories, $exercise_date, $id);
    
    if ($stmt->execute()) {
        header("Location: view.php?success=1");
        exit();
    } else {
        $error = "Error updating exercise: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Exercise - Fitness Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Edit Exercise</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="edit_exercise.php">
            <input type="hidden" name="id" value="<?php echo $exercise['id']; ?>">
            
            <div class="mb-3">
                <label class="form-label">Exercise Name</label>
                <select class="form-select" name="exercise_name" required>
                    <option value="">Select an exercise</option>
                    <?php
                    $options = ['Running', 'Cycling', 'Swimming', 'Weight Training', 'Yoga', 'Pilates', 'HIIT', 'Walking', 'Other'];
                    foreach ($options as $option) {
                        $selected = ($exercise['exercise_name'] == $option) ? 'selected' : '';
                        echo "<option value='$option' $selected>$option</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Duration (minutes)</label>
                <input type="number" class="form-control" name="duration" value="<?php echo $exercise['duration']; ?>" min="1" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Calories Burned</label>
                <input type="number" class="form-control" name="calories" value="<?php echo $exercise['calories']; ?>" min="1" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Date</label>
                <input type="date" class="form-control" name="exercise_date" value="<?php echo $exercise['exercise_date']; ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Exercise</button>
            <a href="view.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
<?php $conn->close(); ?>
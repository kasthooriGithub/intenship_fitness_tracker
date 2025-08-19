<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Tracker - Progress</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">Fitness Tracker</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="bi bi-house-door"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="form.php"><i class="bi bi-plus-circle"></i> Add Exercise</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="track.php"><i class="bi bi-graph-up"></i> Progress</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php"><i class="bi bi-info-circle"></i> About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php"><i class="bi bi-envelope"></i> Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1">
        <div class="container my-5">
            <h1 class="text-center mb-5">Your Fitness Progress</h1>
            
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Weekly Summary</h5>
                            <?php
                            include 'config.php';
                            $weekly_sql = "SELECT SUM(duration) as total_duration, 
                                           SUM(calories) as total_calories,
                                           COUNT(*) as total_exercises
                                           FROM exercises 
                                           WHERE exercise_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
                            $weekly_result = $conn->query($weekly_sql);
                            $weekly_data = $weekly_result->fetch_assoc();
                            ?>
                            <div class="d-flex justify-content-between">
                                <div class="text-center">
                                    <h6>Duration</h6>
                                    <h3 class="text-primary"><?= $weekly_data['total_duration'] ?? 0 ?> min</h3>
                                </div>
                                <div class="text-center">
                                    <h6>Calories</h6>
                                    <h3 class="text-success"><?= $weekly_data['total_calories'] ?? 0 ?> cal</h3>
                                </div>
                                <div class="text-center">
                                    <h6>Sessions</h6>
                                    <h3 class="text-warning"><?= $weekly_data['total_exercises'] ?? 0 ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Monthly Summary</h5>
                            <?php
                            $monthly_sql = "SELECT SUM(duration) as total_duration, 
                                             SUM(calories) as total_calories,
                                             COUNT(*) as total_exercises
                                             FROM exercises 
                                             WHERE exercise_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
                            $monthly_result = $conn->query($monthly_sql);
                            $monthly_data = $monthly_result->fetch_assoc();
                            ?>
                            <div class="d-flex justify-content-between">
                                <div class="text-center">
                                    <h6>Duration</h6>
                                    <h3 class="text-primary"><?= $monthly_data['total_duration'] ?? 0 ?> min</h3>
                                </div>
                                <div class="text-center">
                                    <h6>Calories</h6>
                                    <h3 class="text-success"><?= $monthly_data['total_calories'] ?? 0 ?> cal</h3>
                                </div>
                                <div class="text-center">
                                    <h6>Sessions</h6>
                                    <h3 class="text-warning"><?= $monthly_data['total_exercises'] ?? 0 ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Workout Duration Trend</h5>
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="durationChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Calories Burned Trend</h5>
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="caloriesChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Recent Exercises</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Exercise</th>
                                    <th>Duration</th>
                                    <th>Calories</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $recent_sql = "SELECT * FROM exercises ORDER BY exercise_date DESC LIMIT 5";
                                $recent_result = $conn->query($recent_sql);
                                
                                if ($recent_result->num_rows > 0) {
                                    while($row = $recent_result->fetch_assoc()) {
                                        echo "<tr>
                                            <td>".htmlspecialchars($row['exercise_date'])."</td>
                                            <td>".htmlspecialchars($row['exercise_name'])."</td>
                                            <td>".$row['duration']." min</td>
                                            <td>".$row['calories']." cal</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center'>No exercises recorded yet</td></tr>";
                                }
                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-3">
        <div class="container">
            <p>&copy; 2023 Fitness Tracker. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Duration Chart
        const durationCtx = document.getElementById('durationChart').getContext('2d');
        const durationChart = new Chart(durationCtx, {
            type: 'line',
            data: {
                labels: <?php
                    include 'config.php';
                    $date_sql = "SELECT DISTINCT exercise_date FROM exercises 
                                ORDER BY exercise_date DESC LIMIT 7";
                    $date_result = $conn->query($date_sql);
                    $dates = [];
                    while($row = $date_result->fetch_assoc()) {
                        $dates[] = date('M j', strtotime($row['exercise_date']));
                    }
                    echo json_encode(array_reverse($dates));
                    $conn->close();
                ?>,
                datasets: [{
                    label: 'Duration (minutes)',
                    data: <?php
                        include 'config.php';
                        $duration_sql = "SELECT exercise_date, SUM(duration) as total 
                                        FROM exercises 
                                        GROUP BY exercise_date 
                                        ORDER BY exercise_date DESC LIMIT 7";
                        $duration_result = $conn->query($duration_sql);
                        $durations = [];
                        while($row = $duration_result->fetch_assoc()) {
                            $durations[] = $row['total'];
                        }
                        echo json_encode(array_reverse($durations));
                        $conn->close();
                    ?>,
                    borderColor: 'rgb(220, 53, 69)',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        // Calories Chart
        const caloriesCtx = document.getElementById('caloriesChart').getContext('2d');
        const caloriesChart = new Chart(caloriesCtx, {
            type: 'bar',
            data: {
                labels: <?php
                    include 'config.php';
                    $exercise_sql = "SELECT name FROM exercises 
                                   GROUP BY name 
                                   ORDER BY COUNT(*) DESC LIMIT 5";
                    $exercise_result = $conn->query($exercise_sql);
                    $exercises = [];
                    while($row = $exercise_result->fetch_assoc()) {
                        $exercises[] = $row['name'];
                    }
                    echo json_encode($exercises);
                    $conn->close();
                ?>,
                datasets: [{
                    label: 'Total Calories',
                    data: <?php
                        include 'config.php';
                        $calories_sql = "SELECT name, SUM(calories) as total 
                                       FROM exercises 
                                       GROUP BY name 
                                       ORDER BY SUM(calories) DESC LIMIT 5";
                        $calories_result = $conn->query($calories_sql);
                        $calories = [];
                        while($row = $calories_result->fetch_assoc()) {
                            $calories[] = $row['total'];
                        }
                        echo json_encode($calories);
                        $conn->close();
                    ?>,
                    backgroundColor: 'rgba(208, 53, 69, 0.7)',
                    borderColor: 'rgb(208, 53, 69)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>
</html>
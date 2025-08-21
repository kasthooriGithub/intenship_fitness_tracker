<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Tracker - About</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
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
                        <a class="nav-link" href="track.php"><i class="bi bi-graph-up"></i> Progress</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="about.php"><i class="bi bi-info-circle"></i> About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view.php"><i class="bi bi-list"></i> View Exercises</a>
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
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h1 class="text-center mb-4">About Fitness Tracker</h1>
                            <p class="lead text-center">Your personal fitness companion to track workouts and monitor progress.</p>
                            
                            <div class="row mt-5">
                                <div class="col-md-6">
                                    <h3><i class="bi bi-lightbulb"></i> Our Mission</h3>
                                    <p>We aim to help individuals stay motivated and accountable in their fitness journey by providing simple yet powerful tools to track their exercises and visualize their progress.</p>
                                    
                                    <h3 class="mt-4"><i class="bi bi-star"></i> Features</h3>
                                    <ul class="list-group list-group-flush mb-4">
                                        <li class="list-group-item">Track all types of exercises</li>
                                        <li class="list-group-item">Monitor duration and calories</li>
                                        <li class="list-group-item">View progress with interactive charts</li>
                                        <li class="list-group-item">Set and achieve fitness goals</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h3><i class="bi bi-people"></i> The Team</h3>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h5 class="mb-0">John Doe</h5>
                                                    <p class="mb-0 text-muted">Founder & Developer</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h5 class="mb-0">Jane Smith</h5>
                                                    <p class="mb-0 text-muted">Fitness Expert</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
</body>
</html>
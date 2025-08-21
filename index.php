<?php
session_start(); // Must be at the TOP
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Tracker - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #dc3545;
            --secondary: #6c757d;
            --dark: #212529;
            --light: #f8f9fa;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                       url('https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
            padding: 120px 0;
            color: white;
            text-align: center;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .stats-container {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .stat-item {
            text-align: center;
            padding: 15px;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary);
        }
        
        .stat-label {
            color: var(--secondary);
            font-size: 1rem;
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            color: var(--primary);
        }
        
        .recent-exercise {
            border-left: 4px solid var(--primary);
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            background-color: white;
        }
        
        .btn-primary-custom {
            background-color: var(--primary);
            border-color: var(--primary);
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: bold;
        }
        
        .btn-primary-custom:hover {
            background-color: #bb2d3b;
            border-color: #bb2d3b;
        }
        
        .motivation-section {
            background-color: #ff7b7b;
            color: white;
            padding: 60px 0;
            text-align: center;
        }
        
        .testimonial-card {
            background-color: white;
            border-radius: 15px;
            padding: 25px;
            margin: 15px 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .testimonial-text {
            font-style: italic;
            margin-bottom: 15px;
        }
        
        .testimonial-author {
            font-weight: bold;
            color: var(--primary);
        }
        
        .cta-section {
            background: linear-gradient(to right, #dc3545, #ff7b7b);
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        
        footer {
            background-color: var(--dark);
            color: white;
            padding: 30px 0;
        }
        
        /* Animation for stats */
        @keyframes countUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-stat {
            animation: countUp 1s ease-out forwards;
        }
    </style>
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
                        <a class="nav-link active" href="index.php"><i class="bi bi-house-door"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="form.php"><i class="bi bi-plus-circle"></i> Add Exercise</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="track.php"><i class="bi bi-graph-up"></i> Progress</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php"><i class="bi bi-info-circle"></i> About</a>
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
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="container">
                <h1 class="display-4 fw-bold mb-3">Transform Your Fitness Journey</h1>
                <p class="lead mb-4">Track workouts, monitor progress, and achieve your fitness goals with our comprehensive tracker</p>
                <a href="form.php" class="btn btn-light btn-lg me-2">Get Started</a>
                <a href="view.php" class="btn btn-outline-light btn-lg">View Exercises</a>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="container my-5">
            <div class="stats-container">
                <div class="row">
                    <div class="col-md-3 col-6 stat-item">
                        <div class="stat-number" id="total-workouts">0</div>
                        <div class="stat-label">Workouts</div>
                    </div>
                    <div class="col-md-3 col-6 stat-item">
                        <div class="stat-number" id="total-minutes">0</div>
                        <div class="stat-label">Minutes</div>
                    </div>
                    <div class="col-md-3 col-6 stat-item">
                        <div class="stat-number" id="total-calories">0</div>
                        <div class="stat-label">Calories</div>
                    </div>
                    <div class="col-md-3 col-6 stat-item">
                        <div class="stat-number" id="current-streak">0</div>
                        <div class="stat-label">Day Streak</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="container my-5">
            <h2 class="text-center mb-5">Why Choose Fitness Tracker?</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-plus-circle feature-icon"></i>
                            <h3>Easy Tracking</h3>
                            <p>Quickly add exercises with details like duration, calories burned, and type of workout.</p>
                            <a href="form.php" class="btn btn-outline-danger mt-3">Start Tracking</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-graph-up feature-icon"></i>
                            <h3>Progress Insights</h3>
                            <p>Visualize your improvement over time with charts and detailed statistics.</p>
                            <a href="track.php" class="btn btn-outline-danger mt-3">View Progress</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-trophy feature-icon"></i>
                            <h3>Achieve Goals</h3>
                            <p>Set personal fitness targets and track your journey to success.</p>
                            <a href="form.php" class="btn btn-outline-danger mt-3">Set Goals</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities Section -->
        <div class="container my-5">
            <h2 class="text-center mb-4">Recent Activities</h2>
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="recent-exercise">
                        <div class="d-flex justify-content-between">
                            <h5>Running</h5>
                            <span class="text-muted">Today</span>
                        </div>
                        <p class="mb-1">45 minutes • 450 calories</p>
                    </div>
                    <div class="recent-exercise">
                        <div class="d-flex justify-content-between">
                            <h5>Weight Training</h5>
                            <span class="text-muted">Yesterday</span>
                        </div>
                        <p class="mb-1">60 minutes • 350 calories</p>
                    </div>
                    <div class="recent-exercise">
                        <div class="d-flex justify-content-between">
                            <h5>Swimming</h5>
                            <span class="text-muted">2 days ago</span>
                        </div>
                        <p class="mb-1">30 minutes • 300 calories</p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="view.php" class="btn btn-danger">View All Activities</a>
            </div>
        </div>

        <!-- Motivation Section -->
        <div class="motivation-section my-5">
            <div class="container">
                <h2 class="mb-4">Fitness Motivation</h2>
                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <blockquote class="blockquote">
                            <p class="mb-0">"The only bad workout is the one that didn't happen."</p>
                            <footer class="blockquote-footer mt-2">Unknown</footer>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonials Section -->
        <div class="container my-5">
            <h2 class="text-center mb-5">What Our Users Say</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"This fitness tracker helped me lose 15 pounds in 3 months by keeping me accountable for my workouts!"</p>
                        <div class="testimonial-author">- Sarah M.</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"I love how easy it is to track my progress. The visual charts keep me motivated to push harder."</p>
                        <div class="testimonial-author">- James K.</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"As a fitness coach, I recommend this tracker to all my clients. It's simple yet powerful."</p>
                        <div class="testimonial-author">- Coach Rodriguez</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action Section -->
        <div class="cta-section">
            <div class="container">
                <h2 class="mb-3">Ready to Transform Your Fitness Journey?</h2>
                <p class="lead mb-4">Join thousands of users who are achieving their fitness goals with our tracker</p>
                <a href="form.php" class="btn btn-light btn-lg">Start Tracking Now</a>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-4 mt-auto">
        <div class="container">
            <p>&copy; 2023 Fitness Tracker. All rights reserved.</p>
            <div class="mt-2">
                <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                <a href="#" class="text-white me-3"><i class="bi bi-twitter"></i></a>
                <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
                <a href="#" class="text-white"><i class="bi bi-linkedin"></i></a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animated counter for stats
        function animateValue(id, start, end, duration) {
            const element = document.getElementById(id);
            const range = end - start;
            const increment = end > start ? 1 : -1;
            const stepTime = Math.abs(Math.floor(duration / range));
            let current = start;
            
            const timer = setInterval(function() {
                current += increment;
                element.textContent = current.toLocaleString();
                
                if (current === end) {
                    clearInterval(timer);
                }
            }, stepTime);
            
            element.classList.add('animate-stat');
        }
        
        // Initialize counters when page loads
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                animateValue('total-workouts', 0, 84, 2000);
                animateValue('total-minutes', 0, 1230, 2000);
                animateValue('total-calories', 0, 4280, 2000);
                animateValue('current-streak', 0, 7, 2000);
            }, 500);
        });
    </script>
</body>
</html>
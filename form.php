<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Tracker - Add Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        /* Enhanced exercise dropdown styling */
        .form-select {
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            border: 2px solid #dee2e6;
            transition: all 0.2s;
        }
        .form-select:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .form-select {
                font-size: 1rem;
                padding: 0.85rem 1.25rem;
            }
            #custom-exercise-container {
                margin-top: -0.5rem;
            }
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
                        <a class="nav-link" href="index.php"><i class="bi bi-house-door"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="form.php"><i class="bi bi-plus-circle"></i> Add Exercise</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="track.php"><i class="bi bi-graph-up"></i> Progress</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1 py-4">
        <div class="container">
            <!-- Alert Area -->
            <div id="ajax-alerts"></div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <div class="form-container">
                <h2 class="text-center mb-4"><i class="bi bi-plus-circle"></i> Add New Exercise</h2>
                
                <form id="exercise-form">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="exercise_name" class="form-label">Exercise Type*</label>
                            <select class="form-select" id="exercise_name" name="exercise_name" required>
                                <option value="" disabled selected>Select exercise...</option>
                                <optgroup label="Cardio">
                                    <option value="Running" data-duration="30" data-calories="300">Running</option>
                                    <option value="Cycling" data-duration="45" data-calories="400">Cycling</option>
                                    <option value="Swimming" data-duration="60" data-calories="500">Swimming</option>
                                </optgroup>
                                <optgroup label="Strength">
                                    <option value="Weight Training" data-duration="60" data-calories="200">Weight Training</option>
                                    <option value="Bodyweight" data-duration="30" data-calories="250">Bodyweight</option>
                                </optgroup>
                                <optgroup label="Flexibility">
                                    <option value="Yoga" data-duration="60" data-calories="180">Yoga</option>
                                    <option value="Pilates" data-duration="50" data-calories="175">Pilates</option>
                                </optgroup>
                                <option value="Other">Other (Specify Below)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3" id="custom-exercise-container" style="display:none;">
                            <label for="custom_exercise" class="form-label">Custom Exercise*</label>
                            <input type="text" class="form-control" id="custom_exercise" name="custom_exercise">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="duration" class="form-label">Duration (minutes)*</label>
                            <input type="number" class="form-control" id="duration" name="duration" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="calories" class="form-label">Calories Burned*</label>
                            <input type="number" class="form-control" id="calories" name="calories" min="1" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="exercise_date" class="form-label">Date*</label>
                        <input type="date" class="form-control" id="exercise_date" name="exercise_date" required>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger btn-lg">
                            <i class="bi bi-save"></i> Save Exercise
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-3">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Fitness Tracker. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set today's date as default
        document.getElementById('exercise_date').value = new Date().toISOString().split('T')[0];
        
        // Exercise selection handler
        const exerciseSelect = document.getElementById('exercise_name');
        const customContainer = document.getElementById('custom-exercise-container');
        
        exerciseSelect.addEventListener('change', function() {
            // Show/hide custom exercise field
            customContainer.style.display = this.value === 'Other' ? 'block' : 'none';
            document.getElementById('custom_exercise').required = this.value === 'Other';
            
            // Auto-fill duration and calories from data attributes
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.dataset.duration) {
                document.getElementById('duration').value = selectedOption.dataset.duration;
                document.getElementById('calories').value = selectedOption.dataset.calories;
            }
        });
        
        // Form submission
        document.getElementById('exercise-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
            
            fetch('save_exercise.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    this.reset();
                    document.getElementById('exercise_date').value = new Date().toISOString().split('T')[0];
                } else {
                    showAlert('danger', data.message || 'Error saving exercise');
                }
            })
            .catch(error => {
                showAlert('danger', 'Network error: ' + error.message);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
        
        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            document.getElementById('ajax-alerts').prepend(alertDiv);
            
            setTimeout(() => {
                bootstrap.Alert.getInstance(alertDiv)?.close();
            }, 5000);
        }
    });
    </script>
</body>
</html>
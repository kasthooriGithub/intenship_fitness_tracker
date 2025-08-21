<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Tracker - Contact</title>
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
                        <a class="nav-link" href="about.php"><i class="bi bi-info-circle"></i> About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view.php"><i class="bi bi-list"></i> View Exercises</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="contact.php"><i class="bi bi-envelope"></i> Contact</a>
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
                            <h1 class="text-center mb-4">Contact Us</h1>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <form id="contactForm" class="needs-validation" novalidate>
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Your Name</label>
                                            <input type="text" class="form-control" id="name" required>
                                            <div class="invalid-feedback">
                                                Please provide your name.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="email" required>
                                            <div class="invalid-feedback">
                                                Please provide a valid email.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="message" class="form-label">Message</label>
                                            <textarea class="form-control" id="message" rows="5" required></textarea>
                                            <div class="invalid-feedback">
                                                Please enter your message.
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-danger">Send Message</button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="mb-4">Contact Information</h3>
                                    <div class="mb-4">
                                        <h5><i class="bi bi-envelope text-danger"></i> Email</h5>
                                        <p>support@fitnesstracker.com</p>
                                    </div>
                                    <div class="mb-4">
                                        <h5><i class="bi bi-telephone text-danger"></i> Phone</h5>
                                        <p>(123) 456-7890</p>
                                    </div>
                                    <div>
                                        <h5><i class="bi bi-geo-alt text-danger"></i> Address</h5>
                                        <p>123 Fitness Street<br>Health City, HC 12345</p>
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
    <script>
        // Form validation
        (function() {
            'use strict';
            
            var form = document.getElementById('contactForm');
            
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    alert('Thank you for your message! We will get back to you soon.');
                    form.reset();
                }
                
                form.classList.add('was-validated');
            }, false);
        })();
    </script>
</body>
</html>
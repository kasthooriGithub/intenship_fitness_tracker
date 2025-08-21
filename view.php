<?php
require_once 'config.php';

// Initialize variables with default values
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_date = isset($_GET['filter_date']) ? trim($_GET['filter_date']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';
$where = '';
$params = [];
$param_types = '';

// Build WHERE clause and parameters
if (!empty($search)) {
    $where .= "WHERE exercise_name LIKE ?";
    $params[] = "%$search%";
    $param_types .= 's';
}

if (!empty($filter_date)) {
    $where .= empty($where) ? "WHERE" : " AND";
    $where .= " exercise_date = ?";
    $params[] = $filter_date;
    $param_types .= 's';
}

// Build ORDER BY clause
switch ($sort) {
    case 'date_asc':
        $order_by = "ORDER BY exercise_date ASC";
        break;
    case 'name_asc':
        $order_by = "ORDER BY exercise_name ASC";
        break;
    case 'name_desc':
        $order_by = "ORDER BY exercise_name DESC";
        break;
    case 'duration_asc':
        $order_by = "ORDER BY duration ASC";
        break;
    case 'duration_desc':
        $order_by = "ORDER BY duration DESC";
        break;
    case 'calories_asc':
        $order_by = "ORDER BY calories ASC";
        break;
    case 'calories_desc':
        $order_by = "ORDER BY calories DESC";
        break;
    default:
        $order_by = "ORDER BY exercise_date DESC";
}

// Pagination setup
$limit = 8; // Records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Get total records
$count_sql = "SELECT COUNT(*) as total FROM exercises";
if (!empty($where)) {
    $count_sql .= " $where";
}

$count_stmt = $conn->prepare($count_sql);
if (!empty($params)) {
    $count_stmt->bind_param($param_types, ...$params);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);
$count_stmt->close();

// Adjust page if out of bounds
if ($page > $total_pages && $total_pages > 0) {
    $page = $total_pages;
    $offset = ($page - 1) * $limit;
}

// Fetch data with prepared statement
$sql = "SELECT * FROM exercises";
if (!empty($where)) {
    $sql .= " $where";
}
$sql .= " $order_by LIMIT ? OFFSET ?";

// Prepare the statement
$stmt = $conn->prepare($sql);

// If we have search/filter parameters, add them along with limit/offset
if (!empty($params)) {
    // Add limit and offset to parameters
    $params[] = $limit;
    $params[] = $offset;
    $param_types .= 'ii';
    $stmt->bind_param($param_types, ...$params);
} else {
    // No search/filter parameters, just bind limit and offset
    $stmt->bind_param('ii', $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

// Calculate statistics - need to handle parameters correctly
$duration_sql = "SELECT SUM(duration) as total FROM exercises";
$calories_sql = "SELECT SUM(calories) as total FROM exercises";

if (!empty($where)) {
    $duration_sql .= " $where";
    $calories_sql .= " $where";
}

// Duration calculation
$duration_stmt = $conn->prepare($duration_sql);
if (!empty($params) && !empty($where)) {
    // Use only the original params (without limit/offset)
    $original_params = array_slice($params, 0, count($params) - 2);
    $original_param_types = substr($param_types, 0, -2);
    $duration_stmt->bind_param($original_param_types, ...$original_params);
}
$duration_stmt->execute();
$duration_result = $duration_stmt->get_result();
$total_duration = $duration_result->fetch_assoc()['total'] ?? 0;
$duration_stmt->close();

// Calories calculation
$calories_stmt = $conn->prepare($calories_sql);
if (!empty($params) && !empty($where)) {
    $calories_stmt->bind_param($original_param_types, ...$original_params);
}
$calories_stmt->execute();
$calories_result = $calories_stmt->get_result();
$total_calories = $calories_result->fetch_assoc()['total'] ?? 0;
$calories_stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Tracker - View Exercises</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .card-stat {
            transition: transform 0.2s;
            border-radius: 10px;
            overflow: hidden;
        }
        .card-stat:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .table th {
            background-color: #dc3545;
            color: white;
        }
        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .exercise-card {
            border-left: 4px solid #dc3545;
            margin-bottom: 1rem;
            border-radius: 8px;
        }
        .stats-header {
            background: linear-gradient(135deg, #ff7b7b 0%, #dc3545 100%);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            color: white;
        }
        .content-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .mobile-card {
            display: none;
        }
        @media (max-width: 576px) {
            .desktop-table {
                display: none;
            }
            .mobile-card {
                display: block;
            }
        }
        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
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
                        <a class="nav-link active" href="view.php"><i class="bi bi-list"></i> View Exercises</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="track.php"><i class="bi bi-graph-up"></i> Progress</a>
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

    <!-- Main Content -->
    <div class="container my-4">
        <!-- Header -->
        <div class="stats-header text-center mb-4">
            <h1 class="display-5 fw-bold"><i class="bi bi-list-check"></i> Your Exercise History</h1>
            <p class="lead">Track and manage all your workouts in one place</p>
        </div>

        <!-- Search and Filter Card -->
        <div class="content-card">
            <div class="card-body p-4">
                <h2 class="card-title mb-4"><i class="bi bi-search"></i> Search & Filter</h2>
                <form method="GET" action="view.php">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">Search Exercises</label>
                            <input type="text" name="search" class="form-control" placeholder="Type exercise name..." value="<?= htmlspecialchars($search) ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Filter by Date</label>
                            <input type="date" name="filter_date" class="form-control" value="<?= htmlspecialchars($filter_date) ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sort By</label>
                            <select name="sort" class="form-select">
                                <option value="date_desc" <?= $sort == 'date_desc' ? 'selected' : '' ?>>Date (Newest)</option>
                                <option value="date_asc" <?= $sort == 'date_asc' ? 'selected' : '' ?>>Date (Oldest)</option>
                                <option value="name_asc" <?= $sort == 'name_asc' ? 'selected' : '' ?>>Name (A-Z)</option>
                                <option value='name_desc' <?= $sort == 'name_desc' ? 'selected' : '' ?>>Name (Z-A)</option>
                                <option value="duration_desc" <?= $sort == 'duration_desc' ? 'selected' : '' ?>>Duration (High-Low)</option>
                                <option value="duration_asc" <?= $sort == 'duration_asc' ? 'selected' : '' ?>>Duration (Low-High)</option>
                                <option value="calories_desc" <?= $sort == 'calories_desc' ? 'selected' : '' ?>>Calories (High-Low)</option>
                                <option value="calories_asc" <?= $sort == 'calories_asc' ? 'selected' : '' ?>>Calories (Low-High)</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-danger w-100">Apply Filters</button>
                        </div>
                    </div>
                </form>
                <?php if (!empty($search) || !empty($filter_date)): ?>
                <div class="mt-3">
                    <a href="view.php" class="btn btn-outline-secondary btn-sm">Clear All Filters</a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Exercise updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Exercise deleted successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card card-stat text-center border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-list-check text-primary"></i> Total Exercises</h5>
                        <h3 class="text-primary"><?= number_format($total_records) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-stat text-center border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-clock text-success"></i> Total Duration</h5>
                        <h3 class="text-success"><?= number_format($total_duration) ?> min</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-stat text-center border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-fire text-danger"></i> Total Calories</h5>
                        <h3 class="text-danger"><?= number_format($total_calories) ?> cal</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exercises Table -->
        <div class="content-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="card-title mb-0"><i class="bi bi-table"></i> Exercise History</h2>
                    <a href="export.php?search=<?= urlencode($search) ?>&filter_date=<?= urlencode($filter_date) ?>" class="btn btn-outline-success">
                        <i class="bi bi-download"></i> Export to CSV
                    </a>
                </div>

                <!-- Desktop Table -->
                <div class="desktop-table">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Exercise</th>
                                    <th>Duration</th>
                                    <th>Calories</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['exercise_date']) ?></td>
                                        <td><?= htmlspecialchars($row['exercise_name']) ?></td>
                                        <td><?= $row['duration'] ?> min</td>
                                        <td><?= $row['calories'] ?> cal</td>
                                        <td>
                                            <a href="edit_exercise.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary btn-action">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <a href="delete_exercise.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger btn-action" 
                                               onclick="return confirm('Are you sure you want to delete this exercise?')">
                                                <i class="bi bi-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="bi bi-inbox display-4 text-muted d-block mb-2"></i>
                                            No exercises found. <a href="form.php">Add your first exercise!</a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Cards -->
                <div class="mobile-card mt-4">
                    <?php if ($result->num_rows > 0): ?>
                        <?php 
                        // Reset pointer and loop again for mobile view
                        $result->data_seek(0);
                        while($row = $result->fetch_assoc()): 
                        ?>
                        <div class="card exercise-card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title text-danger mb-0"><?= htmlspecialchars($row['exercise_name']) ?></h5>
                                    <span class="badge bg-secondary"><?= $row['exercise_date'] ?></span>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Duration</small>
                                        <p class="mb-1 fw-bold"><?= $row['duration'] ?> min</p>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Calories</small>
                                        <p class="mb-1 fw-bold"><?= $row['calories'] ?> cal</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 mt-3">
                                    <a href="edit_exercise.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary flex-fill">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="delete_exercise.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger flex-fill"
                                       onclick="return confirm('Delete this exercise?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-4 text-muted d-block mb-2"></i>
                            <p>No exercises found.</p>
                            <a href="form.php" class="btn btn-danger">Add Your First Exercise</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>&filter_date=<?= urlencode($filter_date) ?>&sort=<?= $sort ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        
                        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&filter_date=<?= urlencode($filter_date) ?>&sort=<?= $sort ?>">
                                <?= $i ?>
                            </a>
                        </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>&filter_date=<?= urlencode($filter_date) ?>&sort=<?= $sort ?>">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <p>&copy; 2023 Fitness Tracker. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php 
$stmt->close();
$conn->close();
?>
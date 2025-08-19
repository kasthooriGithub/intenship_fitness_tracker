<?php session_start(); ?> <!-- Required for messages -->
<!DOCTYPE html>
<html>
<head>
    <title>Add Event</title>
    <style>
        .alert { padding: 15px; margin: 10px 0; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <!-- Message Display -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert error"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Event Form -->
    <form action="save_event.php" method="POST">
        <input type="text" name="event_name" placeholder="Event Name" required>
        <input type="date" name="event_date" required>
        <textarea name="description" placeholder="Description"></textarea>
        <button type="submit">Save Event</button>
    </form>
</body>
</html>
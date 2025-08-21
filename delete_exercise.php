<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $sql = "DELETE FROM exercises WHERE id = $id";
    
    if ($conn->query($sql)) {
        header("Location: view.php?deleted=1");
    } else {
        header("Location: view.php?error=1");
    }
} else {
    header("Location: view.php");
}
exit();
?>
<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
checkLogin();

$id = (int)$_GET['id'];

// Hapus artikel
$query = "DELETE FROM articles WHERE id = $id";
if (mysqli_query($conn, $query)) {
    header("Location: index.php?success=1");
} else {
    header("Location: index.php?error=1");
}
exit(); 
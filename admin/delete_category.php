<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
checkLogin();

$id = (int)$_GET['id'];

// Cek apakah kategori masih digunakan oleh artikel
$check_query = "SELECT COUNT(*) as count FROM articles WHERE category_id = $id";
$result = mysqli_query($conn, $check_query);
$row = mysqli_fetch_assoc($result);

if ($row['count'] > 0) {
    header("Location: categories.php?error=category_in_use");
    exit();
}

// Hapus kategori
$query = "DELETE FROM categories WHERE id = $id";
if (mysqli_query($conn, $query)) {
    header("Location: categories.php?success=1");
} else {
    header("Location: categories.php?error=1");
}
exit(); 
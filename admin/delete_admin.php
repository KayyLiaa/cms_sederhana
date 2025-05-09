<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
checkLogin();

// Cek apakah user adalah super admin
$admin = getAdmin($_SESSION['admin_id']);
if ($admin['role'] !== 'super_admin') {
    header("Location: index.php");
    exit();
}

// Cek ID admin
if (!isset($_GET['id'])) {
    header("Location: manage_admins.php");
    exit();
}

$id = (int)$_GET['id'];

// Tidak bisa menghapus diri sendiri
if ($id == $_SESSION['admin_id']) {
    header("Location: manage_admins.php?error=1");
    exit();
}

// Ambil data admin yang akan dihapus
$admin_data = getAdmin($id);
if (!$admin_data) {
    header("Location: manage_admins.php");
    exit();
}

// Hapus foto jika ada
if ($admin_data['photo'] && file_exists('../uploads/admins/' . $admin_data['photo'])) {
    unlink('../uploads/admins/' . $admin_data['photo']);
}

// Hapus admin
$query = "DELETE FROM admins WHERE id = $id";
if (mysqli_query($conn, $query)) {
    header("Location: manage_admins.php?success=4");
} else {
    header("Location: manage_admins.php?error=2");
}
exit(); 
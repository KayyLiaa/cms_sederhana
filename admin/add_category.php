<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
checkLogin();

$error = '';
if (isset($_POST['submit'])) {
    $name = sanitize($_POST['name']);
    if (!empty($name)) {
        if (addCategory($name)) {
            header("Location: categories.php?success=1");
            exit();
        } else {
            $error = "Gagal menambah kategori: " . mysqli_error($conn);
        }
    } else {
        $error = "Nama kategori tidak boleh kosong.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tambah Kategori</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(120deg, #FFD700 0%, #f4f6f9 100%);
            min-height: 100vh;
            font-family: 'Source Sans Pro', sans-serif;
        }
        .centered-card {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            border: none;
        }
        .card-header {
            background: #FFD700;
            color: #333;
            border-radius: 16px 16px 0 0;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            border-bottom: none;
        }
        .form-control:focus {
            border-color: #FFD700;
            box-shadow: 0 0 0 0.2rem rgba(255,215,0,.25);
        }
        .btn-primary {
            background-color: #FFD700;
            color: #333;
            border: none;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #e6c200;
            color: #333;
        }
        .btn-secondary {
            font-weight: bold;
        }
        .icon-category {
            font-size: 2.5rem;
            color: #FFD700;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="centered-card">
    <div class="card" style="width: 100%; max-width: 420px;">
        <div class="card-header">
            <i class="fas fa-folder-plus icon-category"></i><br>
            Tambah Kategori Baru
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="name"><i class="fas fa-tag"></i> Nama Kategori</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan nama kategori" required autofocus>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="categories.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                    <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
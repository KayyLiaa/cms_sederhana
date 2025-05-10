<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    // Validasi
    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        $error = "Semua kolom harus diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif ($password !== $confirm) {
        $error = "Konfirmasi password tidak sama.";
    } else {
        // Cek username/email unik
        $query = "SELECT id FROM admins WHERE username = '$username'";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $error = "Username atau email sudah terdaftar.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO admins (username, password, created_at) VALUES ('$username', '$hashed', NOW())";
            if (mysqli_query($conn, $query)) {
                $success = "Akun berhasil dibuat. Silakan login.";
            } else {
                $error = "Gagal membuat akun: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - CMS Sederhana</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <style>
        :root {
            --primary-color: #FFD700;
            --secondary-color: #808080;
            --light-color: #FFFFFF;
            --dark-color: #333333;
        }
        body {
            font-family: 'Source Sans Pro', sans-serif;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://source.unsplash.com/random/1920x1080/?office') center/cover;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .row {
            width: 100%;
            margin: 0;
        }
        .col-md-6 {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0;
        }
        .login-box {
            background-color: var(--light-color);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-logo h1 {
            color: var(--dark-color);
            font-size: 2rem;
            font-weight: bold;
            margin: 0;
        }
        .login-logo p {
            color: var(--secondary-color);
            margin: 10px 0 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 5px;
            padding: 12px;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
        }
        .btn-login {
            background-color: var(--primary-color);
            border: none;
            border-radius: 5px;
            color: var(--dark-color);
            font-weight: bold;
            padding: 12px;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background-color: #e6c200;
            transform: translateY(-2px);
        }
        .alert {
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .back-to-home {
            color: var(--light-color);
            text-align: center;
            margin-top: 20px;
        }
        .back-to-home a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: bold;
        }
        .back-to-home a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login-box">
                    <div class="login-logo">
                        <h1>CMS Sederhana</h1>
                        <p>Form Registrasi Akun Baru</p>
                    </div>
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Password</label>
                            <input type="password" name="confirm" class="form-control" required>
                        </div>
                        <button type="submit" name="register" class="btn btn-login">Daftar</button>
                    </form>
                </div>
                <div class="back-to-home">
                    <a href="login.php">
                        <i class="fas fa-arrow-left"></i> Kembali ke Sign In
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
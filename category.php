<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Ambil ID kategori dari URL
$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data kategori
$query = "SELECT * FROM categories WHERE id = $category_id";
$result = mysqli_query($conn, $query);
$category = mysqli_fetch_assoc($result);

// Jika kategori tidak ditemukan, redirect ke beranda
if (!$category) {
    header("Location: index.php");
    exit();
}

// Ambil artikel berdasarkan kategori
$articles = getArticlesByCategory($category_id);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($category['name']); ?> - CMS Sederhana</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #FFD700;
            --secondary-color: #808080;
            --light-color: #FFFFFF;
            --dark-color: #333333;
        }

        body {
            font-family: 'Source Sans Pro', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: var(--light-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            color: var(--dark-color) !important;
            font-weight: bold;
        }

        .category-header {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://source.unsplash.com/random/1920x1080/?category') center/cover;
            color: var(--light-color);
            padding: 80px 0;
            margin-bottom: 40px;
        }

        .category-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            margin-bottom: 30px;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-title {
            font-weight: bold;
            color: var(--dark-color);
        }

        .card-text {
            color: var(--secondary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            color: var(--dark-color);
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #e6c200;
            color: var(--dark-color);
        }

        .category-badge {
            background-color: var(--primary-color);
            color: var(--dark-color);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .article-meta {
            color: var(--secondary-color);
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">CMS Sederhana</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Category Header -->
    <header class="category-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="category-title"><?php echo htmlspecialchars($category['name']); ?></h1>
                    <p class="lead">Temukan artikel-artikel menarik dalam kategori ini</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Articles -->
    <main class="container">
        <div class="row">
            <?php if (empty($articles)): ?>
                <div class="col-12 text-center">
                    <p class="lead">Belum ada artikel dalam kategori ini.</p>
                </div>
            <?php else: ?>
                <?php foreach ($articles as $article): ?>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <span class="category-badge mb-2 d-inline-block">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </span>
                                <h5 class="card-title"><?php echo htmlspecialchars($article['title']); ?></h5>
                                <div class="article-meta">
                                    <i class="far fa-calendar-alt"></i> <?php echo date('d F Y', strtotime($article['created_at'])); ?>
                                </div>
                                <p class="card-text">
                                    <?php echo substr(strip_tags($article['content']), 0, 100) . '...'; ?>
                                </p>
                                <a href="article.php?id=<?php echo $article['id']; ?>" class="btn btn-primary">
                                    Baca Selengkapnya
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>CMS Sederhana</h5>
                    <p>Platform manajemen konten sederhana dan mudah digunakan.</p>
                </div>
                <div class="col-md-6 text-md-right">
                    <p>&copy; <?php echo date('Y'); ?> CMS Sederhana. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
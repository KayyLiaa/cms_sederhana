<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Ambil semua kategori
$categories = [];
$query = "SELECT * FROM categories ORDER BY name ASC";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
}

// Ambil artikel terbaru
$latest_articles = [];
$query = "SELECT a.*, c.name as category_name 
          FROM articles a 
          LEFT JOIN categories c ON a.category_id = c.id 
          ORDER BY a.created_at DESC 
          LIMIT 6";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $latest_articles[] = $row;
    }
}

// Ambil artikel berdasarkan kategori
$category_articles = [];
foreach ($categories as $category) {
    $query = "SELECT a.*, c.name as category_name 
              FROM articles a 
              LEFT JOIN categories c ON a.category_id = c.id 
              WHERE a.category_id = {$category['id']} 
              ORDER BY a.created_at DESC 
              LIMIT 3";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $articles = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $articles[] = $row;
        }
        $category_articles[$category['name']] = $articles;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CMS Sederhana</title>

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
            background-color: #f4f6f9;
        }

        .navbar {
            background-color: var(--light-color);
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: var(--dark-color) !important;
        }

        .nav-link {
            color: var(--dark-color) !important;
            font-weight: 500;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://source.unsplash.com/random/1920x1080/?news') center/cover;
            color: var(--light-color);
            padding: 100px 0;
            margin-bottom: 50px;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 30px;
        }

        .category-card {
            background-color: var(--light-color);
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            transition: transform 0.3s ease;
            margin-bottom: 30px;
        }

        .category-card:hover {
            transform: translateY(-5px);
        }

        .category-header {
            background-color: var(--primary-color);
            color: var(--dark-color);
            padding: 15px;
            border-radius: 10px 10px 0 0;
            font-weight: bold;
        }

        .article-card {
            background-color: var(--light-color);
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            transition: transform 0.3s ease;
            margin-bottom: 30px;
            overflow: hidden;
        }

        .article-card:hover {
            transform: translateY(-5px);
        }

        .article-image {
            height: 200px;
            object-fit: cover;
        }

        .article-content {
            padding: 20px;
        }

        .article-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: var(--dark-color);
        }

        .article-meta {
            color: var(--secondary-color);
            font-size: 0.875rem;
            margin-bottom: 10px;
        }

        .article-excerpt {
            color: var(--dark-color);
            margin-bottom: 15px;
        }

        .read-more {
            color: var(--primary-color);
            font-weight: bold;
            text-decoration: none;
        }

        .read-more:hover {
            color: var(--dark-color);
            text-decoration: none;
        }

        .footer {
            background-color: var(--dark-color);
            color: var(--light-color);
            padding: 50px 0;
            margin-top: 50px;
        }

        .footer-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .footer-link {
            color: var(--light-color);
            text-decoration: none;
        }

        .footer-link:hover {
            color: var(--primary-color);
            text-decoration: none;
        }

        .social-link {
            color: var(--light-color);
            font-size: 1.5rem;
            margin-right: 15px;
        }

        .social-link:hover {
            color: var(--primary-color);
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

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="hero-title">Selamat Datang di CMS Sederhana</h1>
            <p class="hero-subtitle">Temukan berbagai artikel menarik dari berbagai kategori</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container">
        <!-- Latest Articles -->
        <section class="mb-5">
            <h2 class="text-center mb-4">Artikel Terbaru</h2>
            <div class="row">
                <?php if (!empty($latest_articles)): ?>
                    <?php foreach ($latest_articles as $article): ?>
                        <div class="col-md-4">
                            <div class="article-card">
                                <img src="https://source.unsplash.com/random/800x600/?news" class="article-image w-100" alt="<?php echo htmlspecialchars($article['title']); ?>">
                                <div class="article-content">
                                    <h3 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h3>
                                    <div class="article-meta">
                                        <i class="fas fa-folder"></i> <?php echo htmlspecialchars($article['category_name'] ?? 'Uncategorized'); ?> |
                                        <i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($article['created_at'])); ?>
                                    </div>
                                    <p class="article-excerpt">
                                        <?php echo htmlspecialchars(substr(strip_tags($article['content']), 0, 150)) . '...'; ?>
                                    </p>
                                    <a href="article.php?id=<?php echo $article['id']; ?>" class="read-more">
                                        Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p>Belum ada artikel yang dipublikasikan.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Category Articles -->
        <?php if (!empty($category_articles)): ?>
            <?php foreach ($category_articles as $category_name => $articles): ?>
                <section class="mb-5">
                    <h2 class="text-center mb-4"><?php echo htmlspecialchars($category_name); ?></h2>
                    <div class="row">
                        <?php foreach ($articles as $article): ?>
                            <div class="col-md-4">
                                <div class="article-card">
                                    <img src="https://source.unsplash.com/random/800x600/?<?php echo urlencode($category_name); ?>" class="article-image w-100" alt="<?php echo htmlspecialchars($article['title']); ?>">
                                    <div class="article-content">
                                        <h3 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h3>
                                        <div class="article-meta">
                                            <i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($article['created_at'])); ?>
                                        </div>
                                        <p class="article-excerpt">
                                            <?php echo htmlspecialchars(substr(strip_tags($article['content']), 0, 150)) . '...'; ?>
                                        </p>
                                        <a href="article.php?id=<?php echo $article['id']; ?>" class="read-more">
                                            Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Categories -->
        <section class="mb-5">
            <h2 class="text-center mb-4">Kategori</h2>
            <div class="row">
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <div class="col-md-4">
                            <div class="category-card">
                                <div class="category-header">
                                    <i class="fas fa-folder"></i> <?php echo htmlspecialchars($category['name']); ?>
                                </div>
                                <div class="p-3">
                                    <a href="category.php?id=<?php echo $category['id']; ?>" class="btn btn-outline-primary btn-block">
                                        Lihat Artikel
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p>Belum ada kategori yang tersedia.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h3 class="footer-title">CMS Sederhana</h3>
                    <p>Platform manajemen konten sederhana untuk mengelola artikel dan kategori dengan mudah.</p>
                </div>
                <div class="col-md-4">
                    <h3 class="footer-title">Kategori</h3>
                    <ul class="list-unstyled">
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <li>
                                    <a href="category.php?id=<?php echo $category['id']; ?>" class="footer-link">
                                        <i class="fas fa-angle-right"></i> <?php echo htmlspecialchars($category['name']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>Belum ada kategori</li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h3 class="footer-title">Ikuti Kami</h3>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> CMS Sederhana. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
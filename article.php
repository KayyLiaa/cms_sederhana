<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Ambil ID artikel dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data artikel
$article = getArticle($id);

// Jika artikel tidak ditemukan, redirect ke beranda
if (!$article) {
    header("Location: index.php");
    exit();
}

// Ambil artikel terkait (dari kategori yang sama)
$related_articles = getArticlesByCategory($article['category_id'], 3);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($article['title']); ?> - CMS Sederhana</title>

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

        .article-header {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://source.unsplash.com/random/1920x1080/?news') center/cover;
            color: var(--light-color);
            padding: 100px 0;
            margin-bottom: 40px;
        }

        .article-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .article-meta {
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .article-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--dark-color);
        }

        .related-articles {
            background-color: var(--light-color);
            padding: 40px 0;
            margin-top: 40px;
        }

        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
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

    <!-- Article Header -->
    <header class="article-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <span class="category-badge mb-3 d-inline-block">
                        <?php echo htmlspecialchars($article['category_name']); ?>
                    </span>
                    <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
                    <div class="article-meta">
                        <i class="far fa-calendar-alt"></i> <?php echo date('d F Y', strtotime($article['created_at'])); ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Article Content -->
    <main class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <article class="article-content">
                    <?php echo nl2br(htmlspecialchars($article['content'])); ?>
                </article>
            </div>
        </div>
    </main>

    <!-- Related Articles -->
    <section class="related-articles">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="text-center mb-4">Artikel Terkait</h2>
                </div>
            </div>
            <div class="row">
                <?php foreach ($related_articles as $related): ?>
                    <?php if ($related['id'] != $article['id']): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <span class="category-badge mb-2 d-inline-block">
                                        <?php echo htmlspecialchars($related['category_name']); ?>
                                    </span>
                                    <h5 class="card-title"><?php echo htmlspecialchars($related['title']); ?></h5>
                                    <p class="card-text">
                                        <?php echo substr(strip_tags($related['content']), 0, 100) . '...'; ?>
                                    </p>
                                    <a href="article.php?id=<?php echo $related['id']; ?>" class="btn btn-primary">
                                        Baca Selengkapnya
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

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
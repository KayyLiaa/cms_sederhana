<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$articles = getArticles(10); // Tampilkan 10 artikel terbaru
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Sederhana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">CMS Sederhana</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <?php while ($article = mysqli_fetch_assoc($articles)): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="card-title"><?php echo $article['title']; ?></h2>
                        <p class="text-muted">
                            Kategori: <?php echo $article['category_name']; ?> | 
                            Tanggal: <?php echo date('d/m/Y', strtotime($article['created_at'])); ?>
                        </p>
                        <p class="card-text">
                            <?php 
                            $content = strip_tags($article['content']);
                            echo substr($content, 0, 200) . '...';
                            ?>
                        </p>
                        <a href="article.php?id=<?php echo $article['id']; ?>" class="btn btn-primary">Baca Selengkapnya</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Kategori</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <?php 
                            $categories = getCategories();
                            while ($category = mysqli_fetch_assoc($categories)): 
                            ?>
                            <li class="mb-2">
                                <a href="category.php?id=<?php echo $category['id']; ?>" class="text-decoration-none">
                                    <?php echo $category['name']; ?>
                                </a>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
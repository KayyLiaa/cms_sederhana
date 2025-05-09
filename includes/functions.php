<?php
session_start();

function checkLogin() {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }
}

function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

function getCategories() {
    global $conn;
    $query = "SELECT * FROM categories ORDER BY name ASC";
    $result = mysqli_query($conn, $query);
    return $result;
}

function getArticles($limit = null) {
    global $conn;
    $query = "SELECT a.*, c.name as category_name 
              FROM articles a 
              LEFT JOIN categories c ON a.category_id = c.id 
              ORDER BY a.created_at DESC";
    
    if ($limit) {
        $query .= " LIMIT " . (int)$limit;
    }
    
    $result = mysqli_query($conn, $query);
    return $result;
}

function getArticle($id) {
    global $conn;
    $id = (int)$id;
    $query = "SELECT a.*, c.name as category_name 
              FROM articles a 
              LEFT JOIN categories c ON a.category_id = c.id 
              WHERE a.id = $id";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}
?> 
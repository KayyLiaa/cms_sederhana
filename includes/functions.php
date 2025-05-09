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

function getCategories($search = null) {
    global $conn;
    $query = "SELECT * FROM categories WHERE 1=1";
    
    if ($search) {
        $search = sanitize($search);
        $query .= " AND name LIKE '%$search%'";
    }
    
    $query .= " ORDER BY name ASC";
    $result = mysqli_query($conn, $query);
    return $result;
}

function getArticles($limit = null, $search = null) {
    global $conn;
    $query = "SELECT a.*, c.name as category_name 
              FROM articles a 
              LEFT JOIN categories c ON a.category_id = c.id 
              WHERE 1=1";
    
    if ($search) {
        $search = sanitize($search);
        $query .= " AND (a.title LIKE '%$search%' OR a.content LIKE '%$search%' OR c.name LIKE '%$search%')";
    }
    
    $query .= " ORDER BY a.created_at DESC";
    
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

function getCategory($id) {
    global $conn;
    $query = "SELECT * FROM categories WHERE id = $id";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}
?> 
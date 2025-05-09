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
    return mysqli_real_escape_string($conn, trim($data));
}

function getAdmin($id) {
    global $conn;
    $id = (int)$id;
    $query = "SELECT * FROM admins WHERE id = $id";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function getCategories() {
    global $conn;
    $query = "SELECT * FROM categories ORDER BY name ASC";
    $result = mysqli_query($conn, $query);
    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
    return $categories;
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

function getArticles($limit = null, $search = null) {
    global $conn;
    $query = "SELECT a.*, c.name as category_name 
              FROM articles a 
              LEFT JOIN categories c ON a.category_id = c.id 
              WHERE 1=1";
    
    if ($search) {
        $search = sanitize($search);
        $query .= " AND (a.title LIKE '%$search%' 
                        OR a.content LIKE '%$search%' 
                        OR c.name LIKE '%$search%')";
    }
    
    $query .= " ORDER BY a.created_at DESC";
    
    if ($limit) {
        $limit = (int)$limit;
        $query .= " LIMIT $limit";
    }
    
    $result = mysqli_query($conn, $query);
    $articles = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $articles[] = $row;
    }
    return $articles;
}

function getArticlesByCategory($category_id, $limit = null) {
    global $conn;
    $category_id = (int)$category_id;
    $query = "SELECT a.*, c.name as category_name 
              FROM articles a 
              LEFT JOIN categories c ON a.category_id = c.id 
              WHERE a.category_id = $category_id 
              ORDER BY a.created_at DESC";
    
    if ($limit) {
        $limit = (int)$limit;
        $query .= " LIMIT $limit";
    }
    
    $result = mysqli_query($conn, $query);
    $articles = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $articles[] = $row;
    }
    return $articles;
}

function addArticle($title, $content, $category_id) {
    global $conn;
    $title = sanitize($title);
    $content = sanitize($content);
    $category_id = (int)$category_id;
    
    $query = "INSERT INTO articles (title, content, category_id) 
              VALUES ('$title', '$content', $category_id)";
    
    return mysqli_query($conn, $query);
}

function updateArticle($id, $title, $content, $category_id) {
    global $conn;
    $id = (int)$id;
    $title = sanitize($title);
    $content = sanitize($content);
    $category_id = (int)$category_id;
    
    $query = "UPDATE articles 
              SET title = '$title', 
                  content = '$content', 
                  category_id = $category_id 
              WHERE id = $id";
    
    return mysqli_query($conn, $query);
}

function deleteArticle($id) {
    global $conn;
    $id = (int)$id;
    $query = "DELETE FROM articles WHERE id = $id";
    return mysqli_query($conn, $query);
}

function addCategory($name) {
    global $conn;
    $name = sanitize($name);
    $query = "INSERT INTO categories (name) VALUES ('$name')";
    return mysqli_query($conn, $query);
}

function updateCategory($id, $name) {
    global $conn;
    $id = (int)$id;
    $name = sanitize($name);
    $query = "UPDATE categories SET name = '$name' WHERE id = $id";
    return mysqli_query($conn, $query);
}

function deleteCategory($id) {
    global $conn;
    $id = (int)$id;
    $query = "DELETE FROM categories WHERE id = $id";
    return mysqli_query($conn, $query);
}

function addAdmin($username, $password, $email, $full_name, $phone, $address, $role, $photo = null) {
    global $conn;
    $username = sanitize($username);
    $email = sanitize($email);
    $full_name = sanitize($full_name);
    $phone = sanitize($phone);
    $address = sanitize($address);
    $role = sanitize($role);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $photo_sql = $photo ? ", photo = '$photo'" : "";
    
    $query = "INSERT INTO admins (username, password, email, full_name, phone, address, role $photo_sql) 
              VALUES ('$username', '$hashed_password', '$email', '$full_name', '$phone', '$address', '$role')";
    
    return mysqli_query($conn, $query);
}

function updateAdmin($id, $username, $email, $full_name, $phone, $address, $role, $photo = null, $password = null) {
    global $conn;
    $id = (int)$id;
    $username = sanitize($username);
    $email = sanitize($email);
    $full_name = sanitize($full_name);
    $phone = sanitize($phone);
    $address = sanitize($address);
    $role = sanitize($role);
    
    $photo_sql = $photo ? ", photo = '$photo'" : "";
    $password_sql = $password ? ", password = '" . password_hash($password, PASSWORD_DEFAULT) . "'" : "";
    
    $query = "UPDATE admins 
              SET username = '$username', 
                  email = '$email', 
                  full_name = '$full_name', 
                  phone = '$phone', 
                  address = '$address', 
                  role = '$role' 
                  $photo_sql 
                  $password_sql 
              WHERE id = $id";
    
    return mysqli_query($conn, $query);
}

function deleteAdmin($id) {
    global $conn;
    $id = (int)$id;
    $query = "DELETE FROM admins WHERE id = $id";
    return mysqli_query($conn, $query);
}

function checkUsername($username, $exclude_id = null) {
    global $conn;
    $username = sanitize($username);
    $exclude_sql = $exclude_id ? "AND id != " . (int)$exclude_id : "";
    $query = "SELECT id FROM admins WHERE username = '$username' $exclude_sql";
    $result = mysqli_query($conn, $query);
    return mysqli_num_rows($result) > 0;
}

function checkEmail($email, $exclude_id = null) {
    global $conn;
    $email = sanitize($email);
    $exclude_sql = $exclude_id ? "AND id != " . (int)$exclude_id : "";
    $query = "SELECT id FROM admins WHERE email = '$email' $exclude_sql";
    $result = mysqli_query($conn, $query);
    return mysqli_num_rows($result) > 0;
}
?> 
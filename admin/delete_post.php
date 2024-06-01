<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !$_SESSION["is_admin"]) {
    header("Location: ../login.php");
    exit;
}

include '../db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Xóa bài viết
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid post ID.";
    exit;
}

$conn->close();

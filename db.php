<?php
$servername = "localhost";
$username = "root";
$password = "nhatduy123";
$dbname = "technology_blog";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

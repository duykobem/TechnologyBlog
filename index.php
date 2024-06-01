<?php
include 'db.php';

// Lấy danh sách bài viết từ cơ sở dữ liệu
$sql = "SELECT id, title, created_at FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Technology Blog</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h1>Technology Blog</h1>
    <div class="posts">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='post'>";
                echo "<h2><a href='post.php?id=" . $row["id"] . "'>" . $row["title"] . "</a></h2>";
                echo "<p>" . $row["created_at"] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No posts found.</p>";
        }
        $conn->close();
        ?>
    </div>
</body>

</html>
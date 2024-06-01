<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Lấy chi tiết bài viết từ cơ sở dữ liệu
    $sql = "SELECT title, content, created_at FROM posts WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $post = $result->fetch_assoc();
    } else {
        echo "Post not found.";
        exit;
    }
} else {
    echo "Invalid post ID.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title><?php echo $post['title']; ?> - Technology Blog</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h1><?php echo $post['title']; ?></h1>
    <p><?php echo $post['created_at']; ?></p>
    <div class="content">
        <?php echo nl2br($post['content']); ?>
    </div>
    <p><a href="index.php">Back to Home</a></p>
</body>

</html>
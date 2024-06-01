<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !$_SESSION["is_admin"]) {
    header("Location: ../login.php");
    exit;
}

include '../db.php';

$id = $title = $content = "";
$titleErr = $contentErr = "";

// Lấy thông tin bài viết hiện tại
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT title, content FROM posts WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $post = $result->fetch_assoc();
        $title = $post['title'];
        $content = $post['content'];
    } else {
        echo "Post not found.";
        exit;
    }
} else {
    echo "Invalid post ID.";
    exit;
}

// Xử lý dữ liệu khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["title"])) {
        $titleErr = "Title is required";
    } else {
        $title = $_POST["title"];
    }

    if (empty($_POST["content"])) {
        $contentErr = "Content is required";
    } else {
        $content = $_POST["content"];
    }

    if ($title && $content) {
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $content, $id);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Post - Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>

<body>
    <h1>Edit Post</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id;?>">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title);?>">
        <span class="error"><?php echo $titleErr;?></span><br><br>

        <label for="content">Content:</label><br>
        <textarea id="content" name="content" rows="10" cols="30"><?php echo htmlspecialchars($content);?></textarea>
        <span class="error"><?php echo $contentErr;?></span><br><br>

        <input type="submit" value="Update Post">
    </form>
    <p><a href="index.php">Back to Admin Panel</a></p>
</body>

</html>
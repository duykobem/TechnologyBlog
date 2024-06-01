<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !$_SESSION["is_admin"]) {
    header("Location: ../login.php");
    exit;
}

include '../db.php';

$title = $content = "";
$titleErr = $contentErr = "";

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
        $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $content);

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
    <title>Add New Post - Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>

<body>
    <h1>Add New Post</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" value="<?php echo $title;?>">
        <span class="error"><?php echo $titleErr;?></span><br><br>

        <label for="content">Content:</label><br>
        <textarea id="content" name="content" rows="10" cols="30"><?php echo $content;?></textarea>
        <span class="error"><?php echo $contentErr;?></span><br><br>

        <input type="submit" value="Add Post">
    </form>
    <p><a href="index.php">Back to Admin Panel</a></p>
</body>

</html>
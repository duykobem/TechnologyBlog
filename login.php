<?php
include 'db.php';
session_start();

$username = $password = "";
$usernameErr = $passwordErr = $loginErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = $_POST["username"];
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = $_POST["password"];
    }

    if ($username && $password) {
        $stmt = $conn->prepare("SELECT id, username, password, is_admin FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $username, $hashed_password, $is_admin);
        
        if ($stmt->num_rows == 1) {
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;
                $_SESSION["is_admin"] = $is_admin;

                if ($is_admin) {
                    header("Location: admin/index.php");
                } else {
                    header("Location: index.php");
                }
                exit;
            } else {
                $loginErr = "Invalid password.";
            }
        } else {
            $loginErr = "No account found with that username.";
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login - Technology Blog</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h1>Login</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" value="<?php echo $username;?>">
        <span class="error"><?php echo $usernameErr;?></span><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" value="<?php echo $password;?>">
        <span class="error"><?php echo $passwordErr;?></span><br><br>

        <span class="error"><?php echo $loginErr;?></span><br><br>

        <input type="submit" value="Login">
    </form>
    <p><a href="register.php">Don't have an account? Register here</a></p>
</body>

</html>
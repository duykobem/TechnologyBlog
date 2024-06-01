<?php
include 'db.php';

$username = $password = $confirm_password = "";
$usernameErr = $passwordErr = $confirm_passwordErr = "";

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

    if (empty($_POST["confirm_password"])) {
        $confirm_passwordErr = "Confirm password is required";
    } else {
        $confirm_password = $_POST["confirm_password"];
    }

    if ($password !== $confirm_password) {
        $confirm_passwordErr = "Passwords do not match";
    }

    if ($username && $password && $password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            header("Location: login.php");
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
    <title>Register - Technology Blog</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h1>Register</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" value="<?php echo $username;?>">
        <span class="error"><?php echo $usernameErr;?></span><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" value="<?php echo $password;?>">
        <span class="error"><?php echo $passwordErr;?></span><br><br>

        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" value="<?php echo $confirm_password;?>">
        <span class="error"><?php echo $confirm_passwordErr;?></span><br><br>

        <input type="submit" value="Register">
    </form>
    <p><a href="login.php">Already have an account? Login here</a></p>
</body>

</html>
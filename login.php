<?php
require_once 'classes/User.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user_id = User::login($username, $password);
    if ($user_id) {
        $_SESSION['user_id'] = $user_id;
        header("Location: index.php");
    } else {
        echo "Error: Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<style>
    .loginContainer {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
</style>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5 loginContainer">
        <h1>Login</h1>
        <form action="login.php" method="post">
            <div class="form-group">
                <label>Username: </label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="form-group">
                <label>Password: </label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <input type="submit" class="btn btn-primary" value="Login">
        </form>
        <p class="mt-3">Don't have an account? <a href="register.php">Register now</a></p>
    </div>
    <?php include ("footer.php") ?>

</body>

</html>
<?php
require_once 'classes/User.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (User::register($username, $email, $password)) {
        header("Location: login.php");
    } else {
        echo "Error: Could not register user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<style>
    .registerContainer {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
</style>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5 registerContainer">
        <h1>Register</h1>
        <form action="register.php" method="post">
            <div class="form-group">
                <label>Username: </label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="form-group">
                <label>Email: </label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label>Password: </label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <input type="submit" class="btn btn-primary" value="Register">
        </form>
        <p class="mt-3">Already have an account? <a href="login.php">Login</a></p>
    </div>
    <?php include ("footer.php") ?>

</body>

</html>
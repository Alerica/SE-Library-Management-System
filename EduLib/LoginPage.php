<?php
session_start();
include "sql.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];


    $sql = "SELECT * FROM visitor WHERE Email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // Cek password jika ada dalam database
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            $_SESSION['ID'] = $user['UserID'];
            $_SESSION['email'] = $email;
            $_SESSION['role'] = 'visitor';
            header("Location: HomePage.php");
            exit;
        } else {
            echo "<script>alert('Password salah!'); window.location='LoginPage.php';</script>";
            exit;
        }
    }


    $sql = "SELECT * FROM librarian WHERE Email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            $_SESSION['ID'] = $user['LibrarianID'];
            $_SESSION['email'] = $email;
            $_SESSION['role'] = 'librarian';
            header("Location: dashboard_penjaga.php");
            exit;
        } else {
            echo "<script>alert('Password salah!'); window.location='LoginPage.php';</script>";
            exit;
        }
    }

    $sql = "SELECT * FROM admin WHERE Email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            $_SESSION['ID'] = $user['AdminID'];
            $_SESSION['email'] = $email;
            $_SESSION['role'] = 'admin';
            header("Location: dashboard_admin.php");
            exit;
        } else {
            echo "<script>alert('Password salah!'); window.location='LoginPage.php';</script>";
            exit;
        }
    }
    echo "<script>alert('Login gagal! Email tidak ditemukan.'); window.location='LoginPage.php';</script>";
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="./css/loginpage.css">
</head>
<body>
    <div class="container">
        <div class="image-section">
            <img src="LoginPageImage.png" alt="Library Illustration">
        </div>
        <div class="login-section">
            <h1>Welcome</h1>
            <p>Login to your account</p>
            <form method="POST">
                <label for="email">Email</label>
                <input type="text" name="email" placeholder="E-mail" required>


                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Password" required>

                <div class="remember">
                    <input type="checkbox" id="remember">
                    <label for="remember">Remember Me</label>
                </div>

                <button type="submit">Sign In</button>
            </form>
            <p style="margin-top: 20px;">Don't have an account? <a href="RegisterPage.php">Sign Up</a></p>
        </div>
    </div>
</body>
</html>

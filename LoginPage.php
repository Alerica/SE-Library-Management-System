<?php
session_start();
include "sql.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['ID'];
    $password = $_POST['password'];

    // Cek di tabel visitor
    $sql = "SELECT * FROM visitor WHERE UserID = '$id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            $_SESSION['ID'] = $user['UserID'];
            $_SESSION['role'] = 'visitor';
            header("Location: ./visitor/HomePage.php");
            exit;
        } else {
            echo "<script>alert('Password salah!'); history.back();</script>";
            exit;
        }
    }

    // Cek di tabel librarian
    $sql = "SELECT * FROM librarian WHERE LibrarianID = '$id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['PASSWORD'])) {
            $_SESSION['ID'] = $user['LibrarianID'];
            $_SESSION['role'] = 'librarian';
            header("Location: ./librarian/librarianDashboard.php");
            exit;
        } else {
            echo "<script>alert('Password salah!'); history.back();</script>";
            exit;
        }
    }

    // Cek di tabel admin
    $sql = "SELECT * FROM admin WHERE AdminID = '$id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            $_SESSION['ID'] = $user['AdminID'];
            $_SESSION['role'] = 'admin';
            header("Location: ./admin/adminDashboard.php");
            exit;
        } else {
            echo "<script>alert('Password salah!'); history.back();</script>";
            exit;
        }
    }

    echo "<script>alert('Login gagal! ID tidak valid.'); history.back();</script>";
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
            <img src="./assets/LoginPageImage.png" alt="Library Illustration">
        </div>
        <div class="login-section">
            <h1>Welcome</h1>
            <p>Login to your account</p>
            <form method="POST">
                <label for="ID">ID</label>
                <input type="text" name="ID" placeholder="ID" required>


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

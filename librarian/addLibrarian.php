<?php
session_start();
include "../sql.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak!'); window.location.href='login.php';</script>";
    exit;
}

$last_id = $conn->query("SELECT MAX(LibrarianID) AS last_id FROM Librarian")->fetch_assoc();
$new_id = 'LB001';

if ($last_id['last_id']) {
    $num = (int) substr($last_id['last_id'], 2);
    $new_num = str_pad($num + 1, 3, '0', STR_PAD_LEFT);
    $new_id = 'LB' . $new_num;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];


    if (!preg_match('/^62[0-9]{8,15}$/', $phone)) {
        $error_message = "Invalid phone number. It should start with 62 and be between 8 to 15 digits.";
    }




    $email_check_query = "SELECT * FROM librarian WHERE Email = '$email'";
    $email_check_result = $conn->query($email_check_query);
    if ($email_check_result->num_rows > 0) {
        $error_message = "The email is already in use.";
    }


    $phone_check_query = "SELECT * FROM librarian WHERE Phone = '$phone'";
    $phone_check_result = $conn->query($phone_check_query);
    if ($phone_check_result->num_rows > 0) {
        $error_message = "The phone number is already in use.";
    }
    $check_password =  $_POST['confirm-password'];
    if($check_password !== $password){
        $error_message = "The password is not matching";
    }

    if (isset($error_message)) {
        echo "<script>alert('$error_message');</script>";
    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);


        $sql = "INSERT INTO librarian (LibrarianID, Name , Email , Phone , PASSWORD) VALUES ('$new_id','$username', '$email','$phone', '$hashed_password')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Berhasil ditambahkan!'); window.location='user.php';</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adding Librarian</title>
    <link rel="stylesheet" href="../css/addLibrarian.css">
    <script defer src="adminDashboard.js"></script>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar-content">
            <h2>Education Library</h2>
            <p>Explore, Learn and Grow</p>
            <a href="librarianDashboard.php">Dashboard</a>
            <a href="book.php">Book</a>
            <a href="Check.php">Aktivitas Peminjaman</a>
            <a href="../LoginPage.php">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Adding Librarian</h1>
        <p class="instruction">Please fill in the column below</p>

        <form method="post">
            <div class="form-row">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" placeholder="Kalimashada" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" placeholder="628 1234 5678" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" name="confirm-password" placeholder="****************" required>
            </div>

            <p class="note">
                Make sure that the librarian has agreed to the agreement that was agreed upon during the employment contract.
                The username and password are confidential and must not be distributed, only given to the librarian for access.
            </p>

            <button type="submit" class="submit-btn">Create Librarian Account</button>
        </form>
    </div>

</body>
</html>

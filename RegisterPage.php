<?php
session_start();
include "sql.php";


$last_id = $conn->query("SELECT MAX(UserID) AS last_id FROM visitor")->fetch_assoc();
$new_id = 'AD001';

if ($last_id['last_id']) {
    $num = (int) substr($last_id['last_id'], 2);
    $new_num = str_pad($num + 1, 3, '0', STR_PAD_LEFT);
    $new_id = 'AD' . $new_num;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];


    if (!preg_match('/^62[0-9]{8,15}$/', $phone)) {
        $error_message = "Invalid phone number. It should start with 62 and be between 8 to 15 digits.";
    }




    $email_check_query = "SELECT * FROM visitor WHERE Email = '$email'";
    $email_check_result = $conn->query($email_check_query);
    if ($email_check_result->num_rows > 0) {
        $error_message = "The email is already in use.";
    }


    $phone_check_query = "SELECT * FROM visitor WHERE Phone = '$phone'";
    $phone_check_result = $conn->query($phone_check_query);
    if ($phone_check_result->num_rows > 0) {
        $error_message = "The phone number is already in use.";
    }


    if (isset($error_message)) {
        echo "<script>alert('$error_message');</script>";
    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);


        $sql = "INSERT INTO visitor (UserID, Name, Email, Phone, Password) VALUES ('$new_id', '$username', '$email', '$phone', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['ID'] = $new_id;
            $_SESSION['role'] = 'visitor';
            echo "<script>alert('Registration successful! ID = $new_id'); window.location='Visitor/HomePage.php';</script>";

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
    <title>Register Form</title>
    <link rel="stylesheet" href="css/registerpage.css">
</head>
<body>
    <div class="container">
        <div class="register-box">
            <h2>Register to get started</h2>
            <p class="subtext">Please fill in the column below</p>

            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Kalimashada" required>
                    </div>

                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" placeholder="628 1234 5678" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Email" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>

                <div class="terms">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">I agree to the <a href="#">Terms & Conditions</a></label>
                </div>

                <button type="submit" class="register-btn">Create Account</button>
            </form>

            <p class="footer-text">Powered by EduLib</p>
        </div>
    </div>
</body>
</html>

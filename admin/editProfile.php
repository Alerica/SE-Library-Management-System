<?php
session_start();
include '../sql.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak!'); window.location.href='../LoginPage.php';</script>";
    exit;
}

$table = '';
$idField = '';
$idValue = '';
$title = '';

if (isset($_GET['Librarianid'])) {
    $table = 'Librarian';
    $idField = 'LibrarianID';
    $idValue = $_GET['Librarianid'];
    $title = 'Edit Librarian';
} elseif (isset($_GET['Visitorid'])) {
    $table = 'Visitor';
    $idField = 'UserID';
    $idValue = $_GET['Visitorid'];
    $title = 'Edit Visitor';
} else {
    echo "<script>alert('ID tidak ditemukan'); window.location.href='user.php';</script>";
    exit;
}



$query = "SELECT * FROM $table WHERE $idField = '$idValue'";
$result = $conn->query($query);
$user = $result->fetch_assoc();

if (!$user) {
    echo "<script>alert('Data tidak ada'); window.location.href='user.php';</script>";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['username'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $check_password = $_POST['confirm-password'];


    $email_check_query = "SELECT * FROM $table WHERE Email = '$email'";
    $email_check_result = $conn->query($email_check_query);
    $phone_check_query = "SELECT * FROM $table WHERE Phone = '$phone'";
    $phone_check_result = $conn->query($phone_check_query);

    if ($check_password !== $password) {
        echo "<script>alert('Password tidak cocok!');</script>";
    }


    else if (!preg_match('/^62[0-9]{8,15}$/', $phone)) {
        echo "<script>alert('Invalid phone number. It should start with 62 and be between 8 to 15 digits.');</script>";
    }




    else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $updateQuery = "UPDATE $table
                        SET Name = '$name', Email = '$email', Phone = '$phone', Password = '$hashed_password'
                        WHERE $idField = '$idValue'";

        if ($conn->query($updateQuery)) {
            echo "<script>alert('Data berhasil diubah!'); window.location.href='user.php';</script>";
            exit;
        } else {
            echo "Gagal update data: " . $conn->error;
        }
    }
}
?>






<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
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
            <a href="adminDashboard.php">Dashboard</a>
            <a href="user.php">User</a>
            <a href="Remove.php">Removed Users List</a>
            <a href="../LoginPage.php">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1><?= $title ?></h1>
        <p class="instruction">Please fill in the column below</p>

        <form method="post">
            <div class="form-row">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" placeholder="Kalimashada" required
                        value="<?= htmlspecialchars($_POST['username'] ?? $user['Name']) ?>">
                </div>

                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" placeholder="62812345678" required
                        value="<?= htmlspecialchars($_POST['phone'] ?? $user['Phone']) ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" placeholder="Email" required
                    value="<?= htmlspecialchars($_POST['email'] ?? $user['Email']) ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" name="confirm-password" placeholder="****************" required>
            </div>

            <button type="submit" class="submit-btn"><?= $title ?> Account</button>
        </form>
    </div>
</body>

</html>
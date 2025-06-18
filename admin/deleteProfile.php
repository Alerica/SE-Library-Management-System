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
$roleUser = '';

if (isset($_GET['Librarianid'])) {
    $table = 'Librarian';
    $idField = 'LibrarianID';
    $idValue = $_GET['Librarianid'];
    $title = 'Delete Librarian';
    $roleUser = 'Librarian';
} elseif (isset($_GET['Visitorid'])) {
    $table = 'Visitor';
    $idField = 'UserID';
    $idValue = $_GET['Visitorid'];
    $title = 'Delete Visitor';
    $roleUser = 'Visitor';
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
    $reason = $_POST['reason'];
    $adminId = $_SESSION['ID'];
    $currentDate = date('Y-m-d');
    $namaUser = $user['Name'];
    $canBeDeleted = true;



    if ($canBeDeleted) {

        $lastReport = $conn->query("SELECT MAX(ReportID) AS last_id FROM report")->fetch_assoc();
        $newReportID = 'RE001';

        if ($lastReport['last_id']) {
            $num = (int) substr($lastReport['last_id'], 2);
            $newNum = str_pad($num + 1, 3, '0', STR_PAD_LEFT);
            $newReportID = 'RE' . $newNum;
        }

        $deleteUser = "DELETE FROM $table WHERE $idField = '$idValue'";
        if ($conn->query($deleteUser)) {

            $insertReport = "INSERT INTO report (ReportID, ReportDate, AdminID, Description, Nama, Role)
                             VALUES ('$newReportID', '$currentDate', '$adminId', '$reason', '$namaUser', '$roleUser')";

            if ($conn->query($insertReport)) {
                echo "<script>alert('User berhasil dihapus dan laporan telah dibuat!'); window.location.href='user.php';</script>";
                exit;
            } else {

                echo "<script>alert('Gagal memasukkan laporan  "."'); window.location.href='user.php';</script>";
                exit;
            }
        } else {
            echo "<script>alert('Gagal menghapus user: Visitor masih minjam buku " . "'); window.location.href='user.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('User tidak dapat dihapus karena ada hubungan data yang masih aktif.'); window.location.href='user.php';</script>";
        exit;
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

        <form method="post" style="display: flex; flex-direction: column; gap: 16px; max-width: 500px;">
    <div class="form-group" style="display: flex; flex-direction: column;">
        <label for="reason" style="margin-bottom: 8px; font-weight: bold;">Berikan Alasan</label>
        <textarea name="reason" rows="5" cols="50" placeholder="Alasan anda" required
            style="font-size: 16px; padding: 10px; box-sizing: border-box;"></textarea>
    </div>

    <button type="submit" class="submit-btn"
        style="padding: 10px 20px;">
        <?= $title ?> Account
    </button>
</form>

    </div>
</body>

</html>
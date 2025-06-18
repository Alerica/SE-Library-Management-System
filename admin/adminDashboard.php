<?php
session_start();
include "../sql.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak!'); window.location.href='../LoginPage.php';</script>";
    exit;
}

// Mengambil data admin
$admin_id = $_SESSION['ID'];
$sql_admin = "SELECT * FROM admin WHERE AdminID = ?";
$stmt = $conn->prepare($sql_admin);
$stmt->bind_param("s", $admin_id);
$stmt->execute();
$result_admin = $stmt->get_result();
$admin_data = $result_admin->fetch_assoc();

// Query untuk buku paling banyak dipinjam
$buku = "none";
$sql = "SELECT
            b.Title,
            COUNT(t.TransactionID) AS total
        FROM
            book b
        LEFT JOIN
            transactiondetail t ON b.ISBN = t.ISBN
        GROUP BY
            b.ISBN,
            b.Title
        ORDER BY
            total DESC
        LIMIT 1";

$result = $conn->query($sql);
if($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $buku = $row['Title'];
}

// Total transaksi
$sql1 = "SELECT COUNT(TransactionID) AS total FROM transactiondetail";
$result1 = $conn->query($sql1);
if ($result1 && $result1->num_rows > 0) {
    $row1 = $result1->fetch_assoc();
    $total = $row1['total'];
}

// Total visitor
$sql2 = "SELECT COUNT(UserID) AS total FROM visitor";
$result2 = $conn->query($sql2);
if ($result2 && $result2->num_rows > 0) {
    $row2 = $result2->fetch_assoc();
    $total_visitor = $row2['total'];
}

// Total librarian
$sql3 = "SELECT COUNT(LibrarianID) AS total FROM librarian";
$result3 = $conn->query($sql3);
if ($result3 && $result3->num_rows > 0) {
    $row3 = $result3->fetch_assoc();
    $total_librarian = $row3['total'];
}

// Total buku
$sql4 = "SELECT COUNT(ISBN) AS total FROM book";
$result4 = $conn->query($sql4);
if ($result4 && $result4->num_rows > 0) {
    $row4 = $result4->fetch_assoc();
    $total_books = $row4['total'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/adminDashboard.css">
    <script defer src="adminDashboard.js"></script>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar-content">
            <h2>Education Library</h2>
            <p>Admin Control Panel</p>
            <a href="adminDashboard.php">Dashboard</a>
            <a href="user.php">User</a>
            <a href="Remove.php">Removed Users List</a>
            <a href="../LoginPage.php">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="welcome-section">
            <h1>Welcome, <?php echo htmlspecialchars($admin_data['Name']); ?>!</h1>
            <p class="admin-id">Admin ID: <?php echo htmlspecialchars($admin_data['AdminID']); ?></p>
        </div>

        <div class="dashboard-grid">
            <!-- Main Stats -->
            <div class="main-stats">
                <div class="stat-box primary">
                    <p class="stat-title">Total Books</p>
                    <p class="stat-value"><?php echo $total_books; ?></p>
                    <p class="stat-description">Books in Library</p>
                </div>
                <div class="stat-box success">
                    <p class="stat-title">Total Users</p>
                    <p class="stat-value"><?php echo $total_visitor; ?></p>
                    <p class="stat-description">Registered Users</p>
                </div>
                <div class="stat-box warning">
                    <p class="stat-title">Total Librarians</p>
                    <p class="stat-value"><?php echo $total_librarian; ?></p>
                    <p class="stat-description">Active Librarians</p>
                </div>
                <div class="stat-box info">
                    <p class="stat-title">Total Transactions</p>
                    <p class="stat-value"><?php echo $total; ?></p>
                    <p class="stat-description">Book Borrowings</p>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="additional-info">
                <div class="info-box">
                    <h3>Most Popular Book</h3>
                    <p class="book-title"><?php echo $buku; ?></p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

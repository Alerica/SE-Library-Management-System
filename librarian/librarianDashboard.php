<?php
session_start();
include "../sql.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'librarian') {
    echo "<script>alert('Akses ditolak!'); window.location.href='../LoginPage.php';</script>";
    exit;
}

// Mengambil data librarian
$librarian_id = $_SESSION['ID'];
$sql_librarian = "SELECT * FROM librarian WHERE LibrarianID = ?";
$stmt = $conn->prepare($sql_librarian);
$stmt->bind_param("s", $librarian_id);
$stmt->execute();
$result_librarian = $stmt->get_result();
$librarian_data = $result_librarian->fetch_assoc();

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

$sql1 = "SELECT COUNT(TransactionID) AS total FROM transactiondetail";
$result1 = $conn->query($sql1);

if ($result1 && $result1->num_rows > 0) {
    $row1 = $result1->fetch_assoc();
    $total = $row1['total'];
}
$sql2 = "SELECT COUNT(UserID) AS total FROM visitor";
$result2 = $conn->query($sql2);

if ($result2 && $result2->num_rows > 0) {
    $row2 = $result2->fetch_assoc();
    $total_visitor = $row2['total'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Dashboard</title>
    <link rel="stylesheet" href="../css/adminDashboard.css">
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
        <div class="welcome-section">
            <h1>Welcome, <?php echo htmlspecialchars($librarian_data['Name']); ?>!</h1>
            <p class="librarian-id">ID: <?php echo htmlspecialchars($librarian_data['LibrarianID']); ?></p>
        </div>

        <div class="stats-container">
            <div class="stat-box">
                <p class="stat-title">The Most Borrowed Book</p>
                <p class="stat-value"> <?php echo $buku; ?></p>
            </div>
            <div class="stat-box">
                <p class="stat-title">Total All Borrowed Book</p>
                <p class="stat-value"><?php echo $total; ?> Book</p>
            </div>
            <div class="stat-box">
                <p class="stat-title">Total Account has Register</p>
                <p class="stat-value"><?php echo $total_visitor; ?> Account </p>
            </div>
        </div>
    </div>

</body>
</html>

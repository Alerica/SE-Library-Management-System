<?php
session_start();
include "../sql.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak!'); window.location.href='../LoginPage.php';</script>";
    exit;
}

$sql = "SELECT
            ReportID ,
            ReportDate,
            AdminID,
            Nama ,
            Role,
            Description
        FROM report";

$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity List</title>
    <link rel="stylesheet" href="../css/book.css">
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
        <h1>Activity List</h1>
        <!-- <a href="addBook.php" class="add-book">Adding Book</a> -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Removed By</th>
                    <th>Name User</th>
                    <th>Role</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                            <?php echo htmlspecialchars($row['ReportID']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['ReportDate']); ?></td>
                            <td><?php echo htmlspecialchars($row['AdminID']); ?></td>
                            <td><?php echo htmlspecialchars($row['Nama']); ?></td>
                            <td>
                                <?php echo $row['Role'] ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['Description']) ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>



            </tbody>
        </table>
    </div>

</body>

</html>
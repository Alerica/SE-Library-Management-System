<?php
session_start();
include '../sql.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak!'); window.location.href='../LoginPage.php';</script>";
    exit;
}

$queryAdmin = "SELECT LibrarianID, Name, Email, Phone FROM Librarian";
$resultAdmin = $conn->query($queryAdmin);


$queryVisitor = "SELECT UserID, Name, Email, Phone FROM visitor";
$resultVisitor = $conn->query($queryVisitor);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="../css/user.css">
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
        <h1>Librarian List</h1>
        <a href="addLibrarian.php" class="add-user">Adding New Librarian</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultAdmin->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['LibrarianID']); ?></td>
                        <td><?php echo htmlspecialchars($row['Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Email']); ?></td>
                        <td><?php echo htmlspecialchars($row['Phone']); ?></td>
                        <td>
                            <div class="button-container">
                            <form method="get" action="editProfile.php" style="display: inline;">
                                    <input type="hidden" name="Librarianid" value="<?php echo $row['LibrarianID']; ?>">
                                    <button class="edit-btn" type="submit">
                                        <img src="../assets/editIcon.png" alt="Edit" width="32" height="32">
                                    </button>
                                </form>
                                <form method="get" action="deleteProfile.php" style="display: inline;"
                                    onsubmit="return confirm('Yakin ingin menghapus Librarian ini?');">
                                    <input type="hidden" name="Librarianid" value="<?php echo $row['LibrarianID']; ?>">
                                    <button class="delete-btn" type="submit">
                                        <img src="../assets/deleteIcon.png" alt="Delete" width="32" height="32">
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h1>User List</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultVisitor->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['UserID']); ?></td>
                        <td><?php echo htmlspecialchars($row['Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Email']); ?></td>
                        <td><?php echo htmlspecialchars($row['Phone']); ?></td>
                        <td>
                            <div class="button-container">
                                <form method="get" action="editProfile.php" style="display: inline;">
                                    <input type="hidden" name="Visitorid" value="<?php echo $row['UserID']; ?>">
                                    <button class="edit-btn" type="submit">
                                        <img src="../assets/editIcon.png" alt="Edit" width="32" height="32">
                                    </button>
                                </form>
                                <form method="get" action="deleteProfile.php" style="display: inline;"
                                    onsubmit="return confirm('Yakin ingin menghapus Visitor ini?');">
                                    <input type="hidden" name="Visitorid" value="<?php echo $row['UserID']; ?>">
                                    <button class="delete-btn" type="submit">
                                        <img src="../assets/deleteIcon.png" alt="Delete" width="32" height="32">
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>

</html>
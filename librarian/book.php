<?php
session_start();
include "../sql.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'librarian') {
    echo "<script>alert('Akses ditolak!'); window.location.href='../LoginPage.php';</script>";
    exit;
}

$sql = "SELECT
            ImagePath ,
            Title ,
            Author,
            Publisher ,
            BookStatus,
            ISBN
        FROM book";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book List</title>
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
            <a href="librarianDashboard.php">Dashboard</a>
            <a href="book.php">Book</a>
            <a href="Check.php">Aktivitas Peminjaman</a>
            <a href="../LoginPage.php">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Book List</h1>
        <a href="addBook.php" class="add-book">Adding Book</a>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Publisher</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="image-mid">
                                <img src="<?php echo htmlspecialchars($row['ImagePath']); ?>"
                                    alt="<?php echo htmlspecialchars($row['Title']); ?>" class="book-cover">
                            </td>
                            <td><?php echo htmlspecialchars($row['Title']); ?></td>
                            <td><?php echo htmlspecialchars($row['Author']); ?></td>
                            <td><?php echo htmlspecialchars($row['Publisher']); ?></td>
                            <td class="<?php echo $row['BookStatus'] ? 'available' : 'not-available'; ?>">
                                <?php echo $row['BookStatus'] ? 'Available' : 'Not Available'; ?>
                            </td>
                            <td>
                                <div class="button-container">
                                    <form method="get" action="editbook.php" style="display: inline;">
                                        <input type="hidden" name="isbn" value="<?php echo $row['ISBN']; ?>">
                                        <button class="edit-btn" type="submit">
                                            <img src="../assets/editIcon.png" alt="Edit" width="32" height="32">
                                        </button>
                                    </form>
                                    <form method="post" action="deletebook.php" style="display: inline;"
                                        onsubmit="return confirm('Yakin ingin menghapus buku ini?');">
                                        <input type="hidden" name="isbn1" value="<?php echo $row['ISBN']; ?>">
                                        <button class="delete-btn" type="submit">
                                            <img src="../assets/deleteIcon.png" alt="Delete" width="32" height="32">
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>



            </tbody>
        </table>
    </div>

</body>

</html>
<?php
session_start();
include '../sql.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'librarian') {
    echo "<script>alert('Akses ditolak!'); window.location.href='../LoginPage.php';</script>";
    exit;
}
$isbn = $_GET['isbn'] ?? '';

if ($isbn === '') {
    die("ISBN tidak ditemukan.");
}

$query = "SELECT * FROM book WHERE ISBN = '$isbn'";
$result = $conn->query($query);
$book = $result->fetch_assoc();

if (!$book) {
    die("Buku tidak ditemukan.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['Title'];
    $author = $_POST['Author'];
    $publisher = $_POST['Publisher'];
    $publishedYear = $_POST['PublishedYear'];
    $description = $_POST['Description'];

    $updateQuery = "UPDATE book
                    SET Title = '$title', Author = '$author', Publisher = '$publisher',
                        PublishedYear = '$publishedYear', Description = '$description'
                    WHERE ISBN = '$isbn'";

    if ($conn->query($updateQuery)) {
        echo "<script>alert('Buku berhasil diperbarui'); window.location.href='book.php';</script>";
    } else {
        echo "Gagal update buku: " . $conn->error;
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adding Librarian</title>
    <link rel="stylesheet" href="../css/addBook.css">
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
        <h1>Adding Book</h1>
        <p class="instruction">Please fill in the column below</p>

        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="ISBN">ISBN</label>
                    <input type="text" name="ISBN" value="<?= htmlspecialchars($book['ISBN']) ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="author">Author</label>
                    <input type="text" name="Author" value="<?= htmlspecialchars($book['Author']) ?>">
                </div>

                <div class="form-group">
                    <label for="published">Published Year</label>
                    <input type="text" name="PublishedYear" value="<?= htmlspecialchars($book['PublishedYear']) ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="status">Set Status Book</label>
                    <input type="text" name="BookStatus" value="<?= htmlspecialchars($book['BookStatus']) ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="publisher">Publisher</label>
                    <input type="text" name="Publisher" value="<?= htmlspecialchars($book['Publisher']) ?>">

                </div>
            </div>

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="Title" value="<?= htmlspecialchars($book['Title']) ?>">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" name="Description" value="<?= htmlspecialchars($book['Description']) ?>">
            </div>

            <p class="note">
                Books that will be published to this library managemnet system must comply with the guidelines and not violate any rules.
                Make sure that the books uploaded di not contain SARA, hatred, and other things that can cause harm to certain parties.
            </p>

            <button type="submit" class="submit-btn">Edit book</button>
        </form>
    </div>

</body>
</html>

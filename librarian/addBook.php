<?php
session_start();
include '../sql.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isbn = $_POST['ISBN'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $publishedYear = $_POST['published'];
    $description = $_POST['description'];

    $statusText = strtolower($_POST['status']);
    $bookStatus = ($statusText === 'available') ? 1 : 0;


    $folder = "../uploads/";


    $filename = basename($_FILES["image"]["name"]);


    $filepath = $folder . $filename;


    if (move_uploaded_file($_FILES["image"]["tmp_name"], $filepath)) {

        $query = "INSERT INTO book (ISBN, Title, Author, Publisher, PublishedYear, BookStatus, imagepath, Description)
                  VALUES ('$isbn', '$title', '$author', '$publisher', '$publishedYear', $bookStatus, '$filepath', '$description')";

        if ($conn->query($query)) {
            echo "<script>alert('Buku berhasil diupload!'); window.location.href='book.php';</script>";
        } else {
            echo "Gagal menyimpan ke database: " . $conn->error;
        }
    } else {
        echo "Gagal mengupload file.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adding Book</title>
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
            <a href="adminDashboard.php">Dashboard</a>
            <a href="book.php">Book</a>
            <a href="user.php">User</a>
            <a href="Check.php">Aktivitas Peminjaman</a>
            <a>Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1>Adding Book</h1>
        <p class="instruction">Please fill in the column below</p>

        <form method="post" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label for="ISBN">ISBN</label><br>
                    <input type="text" name="ISBN" placeholder="14153652472266" required>
                </div>

                <div class="form-group">
                    <label>Author:</label><br>
                    <input type="text" name="author" placeholder="Andry Hakim" required>
                </div>

                <div class="form-group">
                    <label>Published Year:</label><br>
                    <input type="text" name="published" placeholder="2025" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Status (Available / Not Available):</label><br>
                    <input type="text" name="status" placeholder="Available" required>
                </div>

                <div class="form-group">
                    <label>Publisher:</label><br>
                    <input type="text" name="publisher" placeholder="Gramedia" required>
                </div>
            </div>

            <div class="form-group">
                <label for="title">Title</label><br>
                <input type="text" name="title" placeholder="How To Find Underplaceholder Assets in Market Stock IHSG" required>
            </div>

            <div class="form-group">
                <label>Description:</label><br>
                <input type="text" name="description" placeholder="Panduan investasi jangka panjang dengan prinsip nilai dan kehati-hatian."><br><br>
            </div>

            <div>
                <label>Upload Gambar:</label><br>
                <input type="file" name="image" accept="image/*" required><br><br>
            </div>

            <p class="note">
                Books that will be published to this library management system must comply with the guidelines and not
                violate any rules.
                Make sure that the books uploaded do not contain SARA, hatred, and other things that can cause harm to
                certain parties.
            </p>

            <button type="submit" class="submit-btn">Publish book</button>
        </form>
    </div>

</body>

</html>

<?php
session_start();
include '../sql.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'librarian') {
    echo "<script>alert('Akses ditolak!'); window.location.href='../LoginPage.php';</script>";
    exit;
}
$isbn = $_POST['isbn1'] ?? '';

if ($isbn === '') {
    die("<script>alert('ISBN tidak ditemukan.'); window.location.href='book.php';</script>");
}


$checkQuery = "SELECT BookStatus FROM book WHERE ISBN = '" . mysqli_real_escape_string($conn, $isbn) . "'";
$result = mysqli_query($conn, $checkQuery);

if(mysqli_num_rows($result) == 0) {
    die("<script>alert('Buku tidak ditemukan!'); window.location.href='book.php';</script>");
}

$row = mysqli_fetch_assoc($result);
$bookStatus = $row['BookStatus'];

if($bookStatus == '0') {
    echo "<script>
        alert('Buku tidak dapat dihapus karena sedang dipinjam!');
        window.location.href='book.php';
    </script>";
    exit;
}


$deleteQuery = "DELETE FROM book WHERE ISBN = '" . mysqli_real_escape_string($conn, $isbn) . "'";

if(mysqli_query($conn, $deleteQuery)) {
    echo "<script>
        alert('Buku berhasil dihapus!');
        window.location.href='book.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal menghapus buku: " . mysqli_error($conn) . "');
        window.location.href='book.php';
    </script>";
}
?>
<?php
session_start();
include "../sql.php";


if (!isset($_SESSION['ID'])) {
        echo "<script>alert('Akses ditolak!'); window.location.href='../LoginPage.php';</script>";
    exit;
}


$ISBN = $_POST['isbn'] ?? '';
$UserID = $_SESSION['ID'];


if (empty($ISBN)) {
    die("<script>alert('ISBN tidak valid!'); window.history.back();</script>");
}


$conn->begin_transaction();

try {



    $update_transaction = $conn->prepare("
        UPDATE transactiondetail
        SET ReturnDate = NOW() , TransactionStatus = 'Buku Telah dikembalikan'
        WHERE ISBN = ?
        AND UserID = ?
        AND ReturnDate IS NULL
    ");
    $update_transaction->bind_param("si", $ISBN, $UserID);
    $update_transaction->execute();


    if ($update_transaction->affected_rows === 0) {
        throw new Exception("Tidak ada transaksi peminjaman aktif untuk buku ini");
    }


    $update_book = $conn->prepare("
        UPDATE book
        SET BookStatus = 1
        WHERE ISBN = ?
    ");
    $update_book->bind_param("s", $ISBN);
    $update_book->execute();


    $conn->commit();

    echo "<script>
        alert('Buku berhasil dikembalikan!');
        window.location.href = 'Pinjam.php';
    </script>";

} catch (Exception $e) {
    $conn->rollback();

    echo "<script>
        alert('Gagal mengembalikan buku: ". addslashes($e->getMessage()) ."');
        window.history.back();
    </script>";
} finally {
    $conn->close();
}
?>
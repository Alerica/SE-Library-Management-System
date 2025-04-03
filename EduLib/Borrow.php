<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "sql.php";




if (!isset($_GET['isbn']) || empty($_GET['isbn'])) {
    die("<script>alert('ISBN tidak valid!'); window.history.back();</script>");
}

$ISBN = $conn->real_escape_string($_GET['isbn']);
$UserID = $_SESSION['ID'] ?? null;


if (!$UserID) {
    die("<script>alert('Anda harus login!'); window.location='LoginPage.php';</script>");
}


$conn->begin_transaction();

try {
    $check_book = $conn->prepare("SELECT BookStatus FROM book WHERE ISBN = ? FOR UPDATE");
    $check_book->bind_param("s", $ISBN);
    $check_book->execute();
    $book_status = $check_book->get_result()->fetch_assoc();

    if (!$book_status) {
        throw new Exception("Buku tidak ditemukan!");
    }

    if ($book_status['BookStatus'] == 0) {
        throw new Exception("Buku sedang dipinjam!");
    }




    $last_id = $conn->query("SELECT MAX(TransactionID) AS last_id FROM transactiondetail")->fetch_assoc();
    $new_id = 'TR001';

    if ($last_id['last_id']) {
        $num = (int) substr($last_id['last_id'], 2);
        $new_num = str_pad($num + 1, 3, '0', STR_PAD_LEFT);
        $new_id = 'TR' . $new_num;
    }




    $insert = $conn->prepare("INSERT INTO transactiondetail
        (TransactionID, LoanDate, ReturnDate, ISBN, UserID, LibrarianID)
        VALUES (?, NOW(), NULL, ?, ?, NULL)");
    $insert->bind_param("sss", $new_id, $ISBN, $UserID);

    if (!$insert->execute()) {
        throw new Exception("Gagal membuat transaksi: " . $conn->error);
    }





    $update = $conn->prepare("UPDATE book SET BookStatus = 0 WHERE ISBN = ?");
    $update->bind_param("s", $ISBN);

    if (!$update->execute()) {
        throw new Exception("Gagal update status buku: " . $conn->error);
    }





    $conn->commit();

    echo "<script>
        alert('Peminjaman berhasil!');
        window.location.href='Detail.php?id=$ISBN';
    </script>";




} catch (Exception $e) {
    $conn->rollback();

    echo "<script>
        alert('Error: ". addslashes($e->getMessage()) ."');
        window.history.back();
    </script>";
} finally {
    $conn->close();
}


?>
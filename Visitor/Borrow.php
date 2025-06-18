<?php

session_start();
include "../sql.php";

if (!isset($_GET['isbn']) || empty($_GET['isbn'])) {
    die("<script>alert('ISBN tidak valid!'); window.history.back();</script>");
}

$ISBN = $conn->real_escape_string($_GET['isbn']);
$UserID = $_SESSION['ID'] ?? null;




if (!$UserID) {
    echo "<script>alert('Akses ditolak!'); window.location.href='../LoginPage.php';</script>";
}





$conn->begin_transaction();

try {
    $check_book_query = "SELECT BookStatus FROM book WHERE ISBN = '$ISBN' FOR UPDATE";
    $result = $conn->query($check_book_query);
    $book_status = $result->fetch_assoc();

    if (!$book_status) {
        throw new Exception("Buku tidak ditemukan!");
    }

    if ($book_status['BookStatus'] == 0) {
        throw new Exception("Buku sedang dipinjam!");
    }

    $last_id_result = $conn->query("SELECT MAX(TransactionID) AS last_id FROM transactiondetail");
    $last_id = $last_id_result->fetch_assoc();
    $new_id = 'TR001';

    if ($last_id['last_id']) {
        $num = (int) substr($last_id['last_id'], 2);
        $new_num = str_pad($num + 1, 3, '0', STR_PAD_LEFT);
        $new_id = 'TR' . $new_num;
    }

    $insert_query = "
        INSERT INTO transactiondetail (TransactionID, LoanDate, ReturnDate, TransactionStatus, ISBN, UserID, LibrarianID)
        VALUES ('$new_id', NOW(), NULL, 'Menunggu Konfirmasi Librarian' , '$ISBN', '$UserID', NULL)
    ";

    if (!$conn->query($insert_query)) {
        throw new Exception("Gagal membuat transaksi: " . $conn->error);
    }

    $update_query = "UPDATE book SET BookStatus = 0 WHERE ISBN = '$ISBN'";

    if (!$conn->query($update_query)) {
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

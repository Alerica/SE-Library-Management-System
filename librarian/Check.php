<?php
session_start();
include '../sql.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'librarian') {
    echo "<script>alert('Akses ditolak!'); window.location.href='../LoginPage.php';</script>";
    exit;
}

if (isset($_POST['action'])) {
    $transactionId = $_POST['transaction_id'];
    $librarianId = $_SESSION['ID'];
    $isbn = $_POST['isbn'];

    if ($_POST['action'] == 'approve') {
        $query = "UPDATE transactiondetail
                 SET TransactionStatus = 'Telah di konfirmasi Librarian',
                     LibrarianID = '$librarianId'
                 WHERE TransactionID = '$transactionId'";

        mysqli_query($conn, $query);

    } else if ($_POST['action'] == 'reject') {

        $query = "UPDATE transactiondetail
                 SET TransactionStatus = 'Tidak di konfirmasi Librarian',
                     ReturnDate = CURDATE(),
                     LibrarianID = '$librarianId'
                 WHERE TransactionID = '$transactionId'";

        mysqli_query($conn, $query);


        $updateBook = "UPDATE book
                      SET BookStatus = '1'
                      WHERE ISBN = '$isbn'";
        mysqli_query($conn, $updateBook);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}


$queryPending = "SELECT
                t.TransactionID,
                v.Name AS NamaUser,
                b.Title AS JudulBuku,
                t.ISBN as ISBN,
                t.LoanDate
              FROM transactiondetail t
              JOIN visitor v ON t.UserID = v.UserID
              JOIN book b ON t.ISBN = b.ISBN
              WHERE t.LibrarianID IS NULL
                AND t.ReturnDate IS NULL";

$resultPending = mysqli_query($conn, $queryPending);


$queryCompleted = "SELECT
                  t.TransactionID,
                  v.Name AS NamaUser,
                  b.Title AS JudulBuku,
                  t.LoanDate,
                  t.TransactionStatus
            FROM transactiondetail t
                JOIN visitor v ON t.UserID = v.UserID
                JOIN book b ON t.ISBN = b.ISBN";

$resultCompleted = mysqli_query($conn, $queryCompleted);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Dashboard</title>
    <link rel="stylesheet" href="../css/Check.css">
    <script defer src="adminDashboard.js"></script>
</head>

<body>

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

    <div class="content">
        <h2>Permintaan Peminjaman 🕒</h2>

        <?php if (mysqli_num_rows($resultPending) > 0): ?>
            <table>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Peminjam</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Aksi</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($resultPending)): ?>
                    <tr>
                        <td><?= $row['TransactionID'] ?></td>
                        <td><?= $row['NamaUser'] ?></td>
                        <td><?= $row['JudulBuku'] ?></td>
                        <td><?= $row['LoanDate'] ?></td>
                        <td>

                            <div class="action-group">
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="transaction_id" value="<?= $row['TransactionID'] ?>">
                                    <input type="hidden" name="isbn" value="<?= $row['ISBN'] ?>">
                                    <button type="submit" name="action" value="approve" class="btn approve-btn"
                                        title="Setujui Pinjaman">
                                        ✅
                                    </button>
                                    <button type="submit" name="action" value="reject" class="btn reject-btn"
                                        title="Tolak Pinjaman">
                                        ❌
                                    </button>

                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>Tidak ada permintaan peminjaman saat ini 🎉</p>
        <?php endif; ?>

        <h2 style="margin-top: 40px;">Riwayat Peminjaman 📋</h2>

        <?php if (mysqli_num_rows($resultCompleted) > 0): ?>
            <table>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Peminjam</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Status</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($resultCompleted)): ?>
                    <tr>
                        <td><?= $row['TransactionID'] ?></td>
                        <td><?= $row['NamaUser'] ?></td>
                        <td><?= $row['JudulBuku'] ?></td>
                        <td><?= $row['LoanDate'] ?></td>
                        <td><?= $row['TransactionStatus'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>Belum ada riwayat peminjaman</p>
        <?php endif; ?>
    </div>


</body>

</html>
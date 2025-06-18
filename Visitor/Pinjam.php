<?php
session_start();
include "../sql.php";

if (!isset($_SESSION['ID'])) {
    echo "<script>alert('Akses ditolak!'); window.location.href='../LoginPage.php';</script>";
    exit;
}


$UserID = $_SESSION['ID'];

$query = "SELECT
            b.ISBN,
            b.Title,
            b.Author,
            b.PublishedYear,
            b.imagepath,
            t.LoanDate,
            t.ReturnDate,
            t.TransactionStatus
          FROM transactiondetail t
          JOIN book b ON t.ISBN = b.ISBN
          WHERE t.UserID = '$UserID'
          AND t.ReturnDate IS NULL";


$result = $conn->query($query);
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Pinjaman </title>
    <link rel="stylesheet" type="text/css" href="../css/Pinjam.css">
</head>

<body>
    <header>
        <div class="navbar">
            <div class="logo">
                <h1>Education Library</h1>
                <p>Explore, Learn and Grow</p>
            </div>
            <nav>
                <ul>
                    <li><a href="HomePage.php">Home</a></li>
                    <li class="nav-item dropdown">
                        <a href="search.php" class="nav-link" id="bookDropdown">Book ▾</a>
                        <ul class="dropdown-menu" id="dropdownMenu">
                            <li><a href="search.php">Physical Book</a></li>
                            <li><a href="search.php">E-Book</a></li>
                        </ul>
                    </li>
                    <li><a href="AboutUs.php">About Us</a></li>
                    <li><a href="../LoginPage.php">Log Out</a></li>
                </ul>
            </nav>
        </div>
    </header>


    <div class="container">
    <nav class="breadcrumb">
        <h4><a href="HomePage.php">Home</a> <span>›</span> <a href="Pinjam.php">Total on Hand</a></h4>
    </nav>


    <?php if ($result && $result->num_rows > 0): ?>
            <div class="book-list">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="book-card">

                        <div>
                            <img src="<?php echo htmlspecialchars($row['imagepath']); ?>"
                                alt="<?php echo htmlspecialchars($row['Title']); ?>" class="book-cover">
                        </div>


                        <div class="book-info">
                            <h3><?php echo htmlspecialchars($row['Title']); ?></h3>
                            <p>Penulis: <?php echo htmlspecialchars($row['Author']); ?></p>
                            <p>Tahun: <?php echo htmlspecialchars($row['PublishedYear']); ?></p>
                            <p>Tanggal Pinjam: <?php echo date('d M Y', strtotime($row['LoanDate'])); ?></p>
                            <p>Status: <?php echo htmlspecialchars($row['TransactionStatus']); ?></p>
                            <div>
                                <form action="Return.php" method="post">
                                    <input type="hidden" name="isbn" value="<?php echo $row['ISBN']; ?>">
                                    <button type="submit" class="action-btn">Kembalikan Buku</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div>
                <h2 style="text-align: center;">Anda tidak memiliki buku yang sedang dipinjam.</h2>
            </div>
        <?php endif; ?>
    </div>
    <footer>
        <p>Copyright © Education Library. All rights reserved</p>
    </footer>

</body>

</html>
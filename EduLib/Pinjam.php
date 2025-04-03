<?php
session_start();
include "sql.php";

// Validasi session
if (!isset($_SESSION['ID'])) {
    header("Location: LoginPage.php");
    exit;
}

$UserID = $_SESSION['ID'];

// Query untuk mendapatkan buku yang sedang dipinjam
$query = "SELECT
            b.ISBN,
            b.Title,
            b.Author,
            b.PublishedYear,
            b.imagepath,
            t.LoanDate,
            t.ReturnDate
          FROM transactiondetail t
          JOIN book b ON t.ISBN = b.ISBN
          WHERE t.UserID = ?
          AND t.ReturnDate IS NULL
          ORDER BY t.LoanDate DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $UserID);
$stmt->execute();
$result = $stmt->get_result();
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="./css/Pinjam.css">
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
                    <li><a href="Profile.php">Profile</a></li>
                </ul>
            </nav>
        </div>
    </header>


    <div class="container">
    <nav class="breadcrumb">
        <h4><a href="HomePage.php">Home</a> <span>›</span> <a href="Pinjam.php">Total on Hand</a></h4>
    </nav>


        <?php if ($result->num_rows > 0): ?>
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
                <h2 style=" text-align: center;">Anda tidak memiliki buku yang sedang dipinjam.</h2>
            </div>

        <?php endif; ?>
    </div>
    <footer>
        <p>Copyright © Education Library. All rights reserved</p>
    </footer>

</body>

</html>
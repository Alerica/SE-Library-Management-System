<?php
session_start();
include "sql.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'visitor') {
    echo "<script>
            alert('Please login first!');
            window.location='LoginPage.php';
          </script>";
    exit;
}


$isbn = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($isbn > 0) {
    // Query untuk mengambil detail buku
    $sql = "SELECT * FROM book WHERE ISBN = $isbn";
    $result = $conn->query($sql);
} else {
    $result = false;
}


if (!isset($_SESSION['ID'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location='LoginPage.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_SESSION['ID'];
    $bookID = $_GET['id'];
    $reportText = "User telah melakukan peminjaman buku dengan ID $bookID";


    $sql = "INSERT INTO report (ReportID, ReportDate, AdminID, ISBN) VALUES (?, NOW() , ?, ?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $bookID, $userID, $reportText);

    if ($stmt->execute()) {
        echo "<script>alert('Laporan berhasil dibuat!');</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan: " . $stmt->error . "');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/Detail.css">
    <title>Detail Buku</title>
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
                        <a href="" class="nav-link" id="bookDropdown">Book ▾</a>
                        <ul class="dropdown-menu" id="dropdownMenu">
                            <li><a href="search.php">Physical Book</a></li>
                            <li><a href="search.php">E-Book</a></li>
                        </ul>
                    </li>
                    <li><a href="Aboutus.php">About Us</a></li>
                    <li><a href="Profile.php">Profile</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <nav class="breadcrumb">
        <h4><a href="HomePage.php">Home</a> <span>›</span> <a href="search.php">Physical Book</a> <span>›</span> <a>Detail
                Book</a> </h4>
    </nav>





    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="book-card">


                <div class="book1">
                    <img src="<?php echo $row['imagepath']; ?>" alt="" style="width: 250px; height: auto; margin: 0px; ">
                    <h4>Status: <?php echo ($row['BookStatus'] == 1) ? 'Available' : 'Not Available'; ?></h4>
                    <form action="Borrow.php" method="GET">
                        <input type="hidden" name="isbn" value="<?php echo $row['ISBN']; ?>">
                        <button type="submit" class="borrow">Borrow Book</button>
                    </form>
                    <button class="favorite">Favorite</button>

                </div>


                <div class="book-info">
                    <h1><?php echo $row['Title']; ?></h1>
                    <p style="margin:0px;"><?php echo $row['Author']; ?></p>
                    <div class="custom-line"></div>
                    <p><?php echo $row['Description']; ?></p>

                    <div class="custom-line"></div>
                    <h2>Detail</h2>
                    <div style="display: flex;">

                        <div style="margin-right: 90px;">

                            <p>ISBN <br>
                            <h4><?php echo $row['ISBN']; ?></h4>
                            </p>

                            <p>Publisher <br>
                            <h4><?php echo $row['Publisher']; ?></h4>
                            </p>

                            <p>Tahun <br>
                            <h4><?php echo $row['PublishedYear']; ?></h4>
                            </p>
                        </div>

                        <div>
                            <p>Publisher <br>
                            <h4><?php echo $row['Publisher']; ?></h4>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Tidak ada buku yang ditemukan.</p>
    <?php endif; ?>


    <?php $conn->close(); ?>





    <footer>
        <p>Copyright © Education Library. All rights reserved</p>
    </footer>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const bookDropdown = document.getElementById("bookDropdown");
        const dropdownMenu = document.getElementById("dropdownMenu");

        bookDropdown.addEventListener("click", function (event) {
            event.preventDefault();
            dropdownMenu.classList.toggle("show");
        });

        document.addEventListener("click", function (event) {
            if (!bookDropdown.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.remove("show");
            }
        });
    });
</script>

</html>
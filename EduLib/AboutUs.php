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

$search_query = isset($_GET['query']) ? $_GET['query'] : '';
$sort = $_GET['sort'] ?? 'newest';


$results_per_page = 4;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;


$base_query = "SELECT * FROM book";
$count_query = "SELECT COUNT(*) AS total FROM book";

if (!empty($search_query)) {
    $filter = " WHERE Title LIKE '%$search_query%' OR Author LIKE '%$search_query%'";
    $base_query .= $filter;
    $count_query .= $filter;
}


$base_query .= " ORDER BY PublishedYear " . ($sort === 'newest' ? "DESC" : "ASC");


$result_count = $conn->query($count_query);
$row_count = $result_count->fetch_assoc();
$total_pages = ceil($row_count['total'] / $results_per_page);


$base_query .= " LIMIT $start_from, $results_per_page";
$result = $conn->query($base_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" type="text/css" href="css/Aboutus.css">
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
                        <a href="" class="nav-link active" id="bookDropdown">Book ▾</a>
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
        <h4><a href="HomePage.php">Home</a> <span>›</span> <a href="AboutUs.php">About Us</a></h4>
    </nav>

    <div class="content">
        <div class="container">
            <h1>Education Library</h1>
            <p><em>Explore, Learn and Grow</em></p>
            <p>EduLib adalah platform perpustakaan digital yang menyediakan akses ke berbagai koleksi buku fisik dan e-book. Dengan misi Explore, Learn, and Grow, EduLib bertujuan mendukung pembelajaran dengan layanan pencarian buku, dukungan pelanggan, dan jam operasional yang fleksibel. </p>
        </div>
        <div class="separator"></div>
        <div class="sidebar">
            <h2>EduLib Support</h2>
            <div class="support">
                <p class="bold">Mail us to</p>
                <p>edulib@gmail.com</p>
                <p>edulibsupport@gmail.com</p>
                <p class="bold">Chat us through</p>
                <p>+62 8123 5136 - Budi</p>
                <p>+62 8353 7244 - Melisa</p>
                <p class="bold">Operational hours</p>
                <p>Monday - Saturday: 08.30 - 20.00 WIB</p>
                <p>Sunday: 08.30 - 15.00 WIB</p>
            </div>
        </div>
    </div>



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
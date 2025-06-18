<?php
session_start();
include "../sql.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'visitor') {
    echo "<script>alert('Akses ditolak!'); window.location.href='../LoginPage.php';</script>";
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" type="text/css" href="../css/Aboutus.css">
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
                        <a href="" id="bookDropdown">Book ▾</a>
                        <ul class="dropdown-menu" id="dropdownMenu">
                            <li><a href="search.php">Physical Book</a></li>
                            <li><a href="search.php">E-Book</a></li>
                        </ul>
                    </li>
                    <li><a href="Aboutus.php" class="nav-link active" >About Us</a></li>
                    <li><a href="../LoginPage.php">Log Out</a></li>
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
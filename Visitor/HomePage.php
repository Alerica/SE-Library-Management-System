<?php
session_start();
include "../sql.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'visitor') {
    echo "<script>alert('Akses ditolak!'); window.location.href='../LoginPage.php';</script>";
    exit;
}

$results_per_page = 4;

$start_from = 0;

$user_id = $_SESSION['ID'];

$user_name = "";
$user_query = "SELECT Name FROM visitor WHERE UserID = '" . $user_id . "'";
$user_result = $conn->query($user_query);

if ($user_result && $user_result->num_rows > 0) {
    $user_row = $user_result->fetch_assoc();
    $user_name = $user_row['Name'];
}

$transaction_query = "SELECT COUNT(*) AS transaction_count FROM transactiondetail WHERE UserID = '" . $user_id . "' AND ReturnDate IS NULL";
$transaction_result = $conn->query($transaction_query);
$transaction_count = 0;

if ($transaction_result && $transaction_result->num_rows > 0) {
    $row = $transaction_result->fetch_assoc();
    $transaction_count = $row['transaction_count'];
}

$active_transaction_query = "
    SELECT COUNT(*) AS active_count
    FROM transactiondetail
    WHERE UserID = '" . $user_id . "'
";


$active_result = $conn->query($active_transaction_query);
$active_transactions = 0;

if ($active_result && $active_result->num_rows > 0) {
    $row = $active_result->fetch_assoc();
    $active_transactions = $row['active_count'];
}

$read_transaction_query = "
    SELECT COUNT(*) AS read_count
    FROM transactiondetail
    WHERE UserID = '" . $user_id . "' AND ReturnDate IS NOT NULL";

$read_result = $conn->query($read_transaction_query);
$read_transactions = 0;

if ($read_result && $read_result->num_rows > 0) {
    $row = $read_result->fetch_assoc();
    $read_transactions = $row['read_count'];
}



$base_query = "SELECT * FROM book";
$count_query = "SELECT COUNT(*) AS total FROM book";
$result_count = $conn->query($count_query);



$base_query .= " LIMIT $start_from, $results_per_page";
$result = $conn->query($base_query);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Education Library</title>
    <link rel="stylesheet" type="text/css" href="../css/HomePage.css">
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
                    <li><a href="HomePage.php" class="active">Home</a></li>
                    <li class="nav-item dropdown">
                        <a href="" class="nav-link" id="bookDropdown">Book ▾</a>
                        <ul class="dropdown-menu" id="dropdownMenu">
                            <li><a href="search.php">Physical Book</a></li>
                            <li><a href="search.php">E-Book</a></li>
                        </ul>
                    </li>
                    <li><a href="Aboutus.php">About Us</a></li>
                    <li><a href="../LoginPage.php">Log Out</a></li>
                </ul>
            </nav>
        </div>
    </header>



    <section class="search-section">
        <h2>Which book will you read today?
            <form action="search.php" method="GET" class="search-form">
                <input type="text" name="query" placeholder="Type Here" required>
                <button type="submit"></button>
            </form>
        </h2>
    </section>











    <div class="welcome-section" style="text-align: center; margin-bottom: 20px;">
        <h2>Welcome, <span><?php echo htmlspecialchars($user_name); ?></span>!</h2>
        <p>Your User ID: <strong><?php echo htmlspecialchars($user_id); ?></strong></p>
    </div>

    <section class="stats">
        <div class="stat-box">
            <p class="title"><Strong>Total on hand:</Strong></p>
            <div class="content">
                <span class="quantity"><?php echo $transaction_count; ?> &nbsp; Book</span>

            </div>


        </div>
        <div class="stat-box">
            <p class="title"><strong>Total Borrows:</strong></p>
            <div class="content">
                <span class="quantity"><?php echo $active_transactions; ?> &nbsp; Book</span>

            </div>
        </div>



        <div class="stat-box">
            <p class="title"> <strong>Total Read:</strong></p>
            <div class="content">
                <span class="quantity"><?php echo $read_transactions; ?> &nbsp; Book</span>

            </div>
        </div>



    </section>






    <section class="buttons" style="display: flex; justify-content: center; gap: 50px;">


        <!-- <div class="buttons-mid">
            <button>Talk to Librarian</button>
            <img style="margin-left: 200px;">
        </div> -->

        <div class="borrowed-section">
            <h2 class="section-title">Currently Borrowed Books</h2>
            <p class="section-description">View and manage all books you currently have checked out from the library.</p>

            <a href="pinjam.php" class="borrowed-btn">
                <span class="borrowed-icon">📚</span>View Borrowed Books
            </a>
        </div>

    </section>








    <section class="newest-collection">
        <h2>Newest Collection</h2>
        <div class="collection-container">

            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '
                    <a href="Detail.php?id=' . $row['ISBN'] . '" style="text-decoration: none;">
                        <div class="book-card">
                            <img src="' . $row['imagepath'] . '" alt="' . htmlspecialchars($row['Title']) . '">
                            <p><br>' . htmlspecialchars($row['Title']) . '</p>
                        </div>
                    </a>';


                }
            }
            ?>


        </div>
    </section>

    <div class="content">
        <section class="faq">
            <h2>Common FAQs</h2>
            <div class="faq-item">
                <strong>Question:</strong> What actions can cause violations and fines?<br><br>
                <span>Answer:</span> Late returns, lost or damaged books, or unauthorized access.
            </div>
            <div class="faq-item">
                <strong>Question:</strong> Is there a limit to how many books I can borrow?<br><br>
                <span>Answer:</span> Yes, up to 5 books at a time.
            </div>
            <div class="faq-item">
                <strong>Question:</strong> How do I check my borrowed books and due dates?<br> <br>
                <span>Answer:</span> Log into your account to check.
            </div>
            <div class="faq-item">
                <strong>Question:</strong> How long can I borrow a book?<br><br>
                <span>Answer:</span> Up to 2 weeks with renewal.
            </div>
        </section>

        <section class="news">
            <h2>News</h2>
            <div class="news-box">
                <h3>Website Maintenance Notice</h3>
                <p>The system will undergo maintenance on 27 February 2025, from 07:00 to 10:00.</p>
            </div>
            <div class="news-box">
                <h3>New Book Arrivals Update</h3>
                <p>New collections will be available by mid-March 2025.</p>
            </div>
            <div class="news-box">
                <h3>Extended Return Deadlines</h3>
                <p>Due to holidays, return deadlines are extended until April 2, 2025.</p>
            </div>
            <div class="news-box">
                <h3>Digital Resources Upgrade</h3>
                <p>Our e-book database will be upgraded on March 15, 2025.</p>
            </div>
        </section>
    </div>

    <footer>
        <p>Copyright © Education Library. All rights reserved</p>
    </footer>
</body>








<!-- Ini buat drop down -->
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
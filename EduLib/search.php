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
    <link rel="stylesheet" type="text/css" href="css/search.css">
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
        <h4><a href="HomePage.php">Home</a> <span>›</span> <a href="search.php">Physical Book</a></h4>
    </nav>




    <div class="container">

        <div class="left-panel">
            <h4>&nbsp;Search</h4>
            <form action="search.php" method="GET" class="search-form">



                <div class="search-box">
                    <input type="text" name="query" placeholder="Type Here"
                        value="<?php echo htmlspecialchars($search_query); ?>">
                    <button class="search" type="submit">🔍</button>
                </div>


                <div style="display:flex; gap: 20px; justify-content: center; align-items: center;">
                    <div>
                        <h3>Sort by</h3>
                        <select name="sort" onchange="this.form.submit()"> <!-- Tambahkan name dan onchange -->
                            <option value="newest" <?= ($_GET['sort'] ?? 'newest') === 'newest' ? 'selected' : '' ?>>Newest
                            </option>
                            <option value="older" <?= ($_GET['sort'] ?? 'newest') === 'older' ? 'selected' : '' ?>>Older
                            </option>
                        </select>
                    </div>

                    <!-- <div> -->
                    <!-- <h3>Category</h3> -->
                    <!-- <select>
                        <option>Newest</option>
                        <option>Popular</option>
                        <option>Price</option>
                    </select> -->
                    <!-- </div> -->
                </div>
                <div class="button-container">
                    <button class="action-btn" style="background-color:rgb(255, 202, 97);">Search</button>
                    <a href="search.php" class="action-btn"
               style="background-color:rgb(114, 186, 202); text-decoration: none; text-align: center;">Clear All</a>

                </div>

            </form>






        </div>

        <div class="right-panel">
            <?php if (!empty($search_query)): ?>
                <h2 style="margin-bottom: 20px;">Hasil buku dari "<?php echo htmlspecialchars($search_query); ?>"</h2>
            <?php endif; ?>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="book-card">
                        <img src="<?php echo htmlspecialchars($row['imagepath']); ?>"
                            alt="<?php echo htmlspecialchars($row['Title']); ?>">
                        <div class="book-info">
                            <h1><?php echo htmlspecialchars($row['Title']); ?></h1>
                            <h4 style="margin: 8px;"><?php echo htmlspecialchars($row['Publisher']); ?></h4>
                            <p><?php echo htmlspecialchars($row['Author']); ?></p>
                            <p>Tahun: <?php echo htmlspecialchars($row['PublishedYear']); ?></p>
                            <p>Status: <?php echo htmlspecialchars($row['BookStatus']); ?></p>
                            <div class="buttons">
                                <button class="favorite">Favorite</button>
                                <a href="Detail.php?id=<?php echo $row['ISBN']; ?>">
                                    <button class="detail">Detail Book</button>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

    </div>
    <div class="pagination">
        <?php if ($total_pages > 1): ?>
            <a href="?page=<?= ($page > 1) ? $page - 1 : 1 ?>" class="<?= ($page == 1) ? 'disabled' : '' ?>">&lt;</a>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= ($page == $i) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <a href="?page=<?= ($page < $total_pages) ? $page + 1 : $total_pages ?>"
                class="<?= ($page == $total_pages) ? 'disabled' : '' ?>">&gt;</a>
        <?php endif; ?>
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
<?php
//Start session to manage logged-in navigation state. 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Include these connections and helper functions.
include 'database.php';
include 'csrf.php';

$query = "";
$result = null;
$searched = false;
$message = "";

//Check whether the user has submitted a search query.
if (isset($_GET['query'])) {
    $searched = true;
    $query = trim($_GET['query']);

    //Show a message if the search input is empty.
    if ($query === "") {
        $message = "Please enter a name or programming language to search.";
    } else {
        $search = "%" . $query . "%";

        //Use a prepared statement to safely search by name or key programming language. 
        $stmt = $conn->prepare("SELECT id, name, email, keyprogramming FROM cvs WHERE name LIKE ? OR keyprogramming LIKE ?");
        $stmt->bind_param("ss", $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Search CVs | AstonCV</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <header class="topbar">
        <div class="container topbar-inner">
            <div class="brand">
                <h1 class="name">AstonCV</h1>
            </div>

            <nav class="nav">
                <!-- Display different navigation links depending on whether the user is logged in -->
                <a href="index.php" class="nav-link">Home</a>
                <a href="search.php" class="nav-link active">Search CVs</a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="updatecv.php" class="nav-link">My Profile</a>
                    <form method="POST" action="logout.php" style="display:inline;">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                        <button type="submit" class="nav-link" style="background:none; border:none; cursor:pointer;">Logout</button>
                    </form>
                <?php else: ?>
                    <a href="login.php" class="nav-link">Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="contact-me search-panel">
            <h2 class="page-title">Search CVs</h2>
            <p class="page-subtitle">Find candidates by name or key programming language.</p>

            <form action="search.php" method="GET" class="search-bar">
                <input
                    type="text"
                    name="query"
                    placeholder="Try: PHP, Python, JavaScript, or a candidate name"
                    value="<?php echo htmlspecialchars($query); ?>"
                >
                <button type="submit" class="btn">Search</button>
            </form>

            <?php if (!$searched): ?>
                <div class="modal-block search-help">
                    <p><strong>Search tips:</strong></p>
                    <p>Use a candidate’s name or a programming language such as PHP, Python, or JavaScript.</p>
                </div>
            <?php elseif ($message !== ""): ?>
                <div class="modal-block">
                    <p><?php echo htmlspecialchars($message); ?></p>
                </div>
            <?php else: ?>
                <div class="results-header">
                    <h3>Search Results</h3>
                    <p>
                        Results for:
                        <strong><?php echo htmlspecialchars($query); ?></strong>
                    </p>
                </div>

                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="cv-card">
                            <div class="cv-info">
                                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                                <p><?php echo htmlspecialchars($row['email']); ?></p>
                            </div>

                            <div class="cv-language">
                                <p>Key Language: <?php echo htmlspecialchars(!empty($row['keyprogramming']) ? $row['keyprogramming'] : 'Not provided'); ?></p>
                            </div>

                            <div class="cv-button">
                                <a href="viewcv.php?id=<?php echo $row['id']; ?>" class="btn">View CV</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="modal-block">
                        <p>No matching CVs found. Try a different name or programming language.</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
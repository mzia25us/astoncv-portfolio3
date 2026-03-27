<?php
session_start();
//Include database connection.
include 'database.php';
include 'csrf.php';
//Validate the CV ID from the URL to ensure it exists and is numeric.
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid CV ID.");
}

//Cast ID to integer for additional safety.
$id = (int)$_GET['id'];

//Use a prepared statement to safely retrieve CV data (prevents SQL injection).
$stmt = $conn->prepare("SELECT * FROM cvs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

//Check if the CV is in the database.
if ($result->num_rows === 0) {
    die("CV not found.");
}

//Fetch CV data as an associative array.
$cv = $result->fetch_assoc();

//Prepare and validate the URL link.
//If no protocol is given then add 'https://' for correct linking.
$link = trim($cv['URLlinks'] ?? '');
if ($link !== '' && !preg_match('#^https?://#', $link)) {
    $link = 'https://' . $link;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>View CV | AstonCV</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <header class="topbar">
        <div class="container topbar-inner">
            <h1 class="name">AstonCV</h1>
            <nav class="nav">
                    <a href="index.php" class="nav-link">Home</a>
                    <a href="search.php" class="nav-link">Search CVs</a>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="updatecv.php" class="nav-link">My Profile</a>

                        <form method="POST" action="logout.php" style="display:inline;">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                            <button type="submit" class="nav-link" style="background: none; border:none; cursor:pointer;">Logout</button>
                        </form>
                    <?php else: ?>
                        <a href="login.php" class="nav-link">Login</a>
                    <?php endif; ?>
                </nav>
            </div>
        </header>

        <main class="container">
            <div class="card">
                <div class="card-header">CV Details</div>
                <div class="card-body">
                    <!-- Output is escaped using htmlspecialchars() to prevent XSS attacks -->
                    <p><strong>Name:</strong> <?php echo htmlspecialchars(!empty($cv['name']) ? $cv['name'] : 'Not provided'); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars(!empty($cv['email']) ? $cv['email'] : 'Not provided'); ?></p>
                    <p><strong>Education:</strong> <?php echo htmlspecialchars(!empty($cv['education']) ? $cv['education'] : 'Not provided'); ?></p>
                    <p><strong>Key Programming Language:</strong> <?php echo htmlspecialchars(!empty($cv['keyprogramming']) ? $cv['keyprogramming'] : 'Not provided'); ?></p>
                    <p><strong>Profile:</strong> <?php echo htmlspecialchars(!empty($cv['profile']) ? $cv['profile'] : 'Not provided'); ?></p>

                    <p>
                        <strong>Links:</strong>
                        <?php if (!empty($cv['URLlinks'])): ?>
                            <a href="<?php echo htmlspecialchars($link); ?>" target="_blank" rel="noopener noreferrer">
                                <?php echo htmlspecialchars($cv['URLlinks']);?>
                            </a>
                        <?php else: ?>
                            No links provided.
                        <?php endif; ?>
                    </p>
                    <a href="index.php" class="btn back-btn">Back to Home</a>
                </div>
            </div>
        </main>
</body>
</html>
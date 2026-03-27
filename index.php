<?php
//Start session to manage logged-in navigation state. 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Include database connection and CSRF helper functions
include 'database.php';
include 'csrf.php';

//Retrieve basic CV information for display on the homepage. 
$sql = "SELECT id, name, email, keyprogramming FROM cvs";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <title>Home | AstonCV</title>
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
                    <a href="index.php" class="nav-link active">Home</a>
                    <a href="search.php" class="nav-link">Search CVs</a>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="updatecv.php" class="nav-link">My Profile</a>
                        <form method="POST" action="logout.php" style="display:inline;">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                            <button type="submit" class="nav-link" style="background:none; border: none; cursor: pointer;">Logout</button>
                        </form>
                    <?php else: ?>
                        <a href="login.php" class="nav-link">Login</a>
                    <?php endif; ?>
                </nav>
            </div>
        </header>

        <main class="container">
            <h2>Browse Programmer CVs</h2>
            <p class="page-subtitle">Explore developer profiles, discover key skills and find the right candidate with confidence.</p>

            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="cv-card">
                        <div class="cv-info">
                            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                            <p><?php echo htmlspecialchars($row['email']); ?></p>
                        </div>
                        
                        <div class="cv-language">
                            <p>Key Language: <?php echo htmlspecialchars(!empty($row['keyprogramming']) ? $row['keyprogramming'] : 'Not provided'); ?></p>
                        </div>
                        
                        <div class="cv-button">
                            <a href="viewcv.php?id=<?php echo $row['id'];?>" class="btn">View CV</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No CVs found.</p>
            <?php endif; ?>
        </main>
    </body>
</html>
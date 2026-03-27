<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Start session and include required files.
session_start();
include 'database.php';
include 'csrf.php';

//Session timeout is 30 minutes of inactivity.
$timeout_duration = 1800; //which is 30 minutes.

//If session has expired, destroy it and redirect to login.
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit();
}

//Ensure only logged-in users can access this page.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

//Update last activity timestamp.
$_SESSION['last_activity'] = time();

$user_id = $_SESSION['user_id'];
$errors = [];
$success = "";

//Retrieve current user's CV details securely using a prepared statement. 
$stmt = $conn->prepare("SELECT name, email, keyprogramming, profile, education, URLlinks FROM cvs WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

//Ensure user exists in the database.
if ($result->num_rows !== 1){
    die("User not found. Please register your account.");
}

$user = $result->fetch_assoc();

//Assign values (or default to empty if not set).
$name = $user['name'] ?? '';
$email = $user['email'] ?? '';
$keyprogramming = $user['keyprogramming'] ?? '';
$profile = $user['profile'] ?? '';
$education = $user['education'] ?? '';
$URLlinks = $user['URLlinks'] ?? '';

//Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';

    //Verify CSRF token to protect against forged requests.
    if (!verify_csrf_token($csrf_token)) {
        $errors[] = "Invalid request. Please refresh page and try again.";
    }

    //Sanitise and retrieve user input.
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $keyprogramming = trim($_POST['keyprogramming'] ?? '');
    $profile = trim($_POST['profile'] ?? '');
    $education = trim($_POST['education'] ?? '');
    $URLlinks = trim($_POST['URLlinks'] ?? '');

    //Ensure URL has correct format. 
    if ($URLlinks !== '' && !preg_match('#^https?://#', $URLlinks)) {
        $URLlinks = 'https://' . $URLlinks;
    }

    //Validate URL format.
    if ($URLlinks !== '' && !filter_var($URLlinks, FILTER_VALIDATE_URL)) {
        $errors[] = "Please enter a valid URL link.";
    }

    //Basic Validation Checks.
    if ($name === '') {
        $errors[] = "Name is required.";
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email is required.";
    }

    //Check if email is already used by another account.
    if (empty($errors)) {
        $check_stmt = $conn->prepare("SELECT id FROM cvs WHERE email = ? AND id != ?");
        $check_stmt->bind_param("si", $email, $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $errors[] = "That email is already being used by another account.";
        }
    }

    //Update CV details securely using a prepared statement.
    if (empty($errors)) {
        $update_stmt = $conn->prepare("UPDATE cvs SET name = ?, email = ?, keyprogramming = ?, profile = ?, education = ?, URLlinks = ? WHERE id = ?");
        $update_stmt->bind_param("ssssssi", $name, $email, $keyprogramming, $profile, $education, $URLlinks, $user_id );

        if ($update_stmt->execute()) {
            $success = "Your CV has been updated successfully.";
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
        } else {
            $errors[] = "Something went wrong while updating your CV.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Update CV | AstonCV</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <link rel="stylesheet" href="register.css">
</head>

<body>
    <header class="topbar main-header">
        <div class="container topbar-inner">
            <div class="brand">
                <h1 class="name">AstonCV</h1>
            </div>

            <nav class="nav">
                <a href="index.php" class="nav-link">Home</a>
                <a href="search.php" class="nav-link">Search CVs</a>
                <a href="updatecv.php" class="nav-link active">My Profile</a>
                <form method="POST" action="logout.php" style="display:inline;">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                    <button type="submit" class="nav-link" style="background:none; border: none; cursor: pointer;">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="container">
        <h2 class="page-title">Hello, <?php echo htmlspecialchars($name); ?>!</h2>
        <p class="page-subtitle">Update your CV and keep your profile details up to date.</p>

        <section class="contact-me">

        <?php if (!empty($errors)): ?>
            <div class="modal-block">
                <?php foreach ($errors as $error): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="modal-block">
                <p><?php echo htmlspecialchars($success); ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" action="updatecv.php">
             <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>"> 
            <div class="grid">
                <div class="field">
                    <label for="name">Full Name </label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        required
                        value="<?php echo htmlspecialchars($name); ?>"
                    />
                </div>

                <div class="field">
                    <label for="email">Email </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        required
                        value="<?php echo htmlspecialchars($email); ?>"
                    />
                </div>

                <div class="field">
                    <label for="keyprogramming">Key Programming Language </label>
                    <input
                        id="keyprogramming"
                        name="keyprogramming"
                        type="text"
                        placeholder="e.g. PHP, JavaScript, Python"
                        value="<?php echo htmlspecialchars($keyprogramming); ?>"
                    />
                </div>

                <div class="field">
                    <label for="education">Education </label>
                    <input
                        id="education"
                        name="education"
                        type="text"
                        placeholder="e.g. Aston University"
                        value="<?php echo htmlspecialchars($education); ?>"
                    />
                </div>
            </div>

            <div class="field full">
                <label for="profile">Profile </label>
                <textarea
                    id="profile"
                    name="profile"
                    rows="5"
                    placeholder="Write a short profile about yourself..."
                ><?php echo htmlspecialchars($profile); ?></textarea>
            </div>

            <div class="field full">
                <label for="URLlinks">Links </label>
                <textarea
                    id="URLlinks"
                    name="URLlinks"
                    rows="3"
                    placeholder="e.g. github.com/yourname"
                ><?php echo htmlspecialchars($URLlinks); ?></textarea>
            </div>

            <div class="submit">
                <button type="submit" class="btn">Save Changes</button>
                <p class="hint">Your CV details will be updated in the AstonCV database.</p>
            </div>
        </form>
        </section>
    </main>
</body>
</html>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Start session and include required files.
session_start();
include 'database.php';
include 'csrf.php';

$errors = [];
$success = "";

$name = "";
$email = "";

//Process the registration form when submitted. 
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $csrf_token = $_POST['csrf_token'] ?? '';

    //Verify CSRF token to protect against forged form submissions.
    if (!verify_csrf_token($csrf_token)) {
        $errors[] = "Invalid request. Please refresh page and try again.";
    }

    //Retrieve and trim user input.
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    //Validate required fields and password strength.
    if ($name === '') {
        $errors[] = "Name is required.";
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email is required.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must include at least one uppercase letter.";
    }

    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must include at least one lowercase letter.";
    }


    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must include at least one number.";
    }

    if (!preg_match('/[\W_]/', $password)) {
        $errors[] = "Password must include at least one special character.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    //Check whether the email already exists in the database.
    if (empty($errors)) {
        $check_stmt = $conn->prepare("SELECT id FROM cvs WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $errors[] = "That email is already registered. Please log in.";
        }
    }

    //If validation passes, hash the password and insert new user.
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO cvs (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            $success = "Registration was successful. You can now log in.";

            //Clear form values after successful registration.
            $name = "";
            $email = "";
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Register | Aston CV</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <link rel="stylesheet" href="register.css">
</head>

<body>
    <header class="topbar main-header">
        <div class="container topbar-inner">
            <div class="brand">
                <h1 class="name">Aston CV</h1>
            </div>
            
            <nav class="nav">
                <a href="index.php" class="nav-link">Home</a>
                <a href="search.php" class="nav-link">Search CVs</a>
                <a href="login.php" class="nav-link">Login</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <h2 class="page-title">Create your account</h2>
        <p class="page-subtitle">Register to add and manage your CV on AstonCV.</p>

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

        <form method="POST" action="register.php">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>"> 
            <div class="grid">
                <div class="field">
                    <label for="name">Full Name </label>
                    <input 
                        id="name"
                        name="name"
                        type="text"
                        placeholder="e.g John Smith"
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
                        placeholder="e.g name@gmail.com"
                        required
                        value="<?php echo htmlspecialchars($email); ?>"
                    />
                </div>

                <div class="field">
                    <label for="password">Password </label>
                    <input 
                        id="password"
                        name="password"
                        type="password"
                        placeholder="At least 8 characters"
                        required
                    />
                    <p class="hint">Use 8 characters with an uppercase, lowercase letter, number and special character.</p>
                </div>

                <div class="field">
                    <label for="confirm_password">Confirm Password </label>
                    <input 
                        id="confirm_password"
                        name="confirm_password"
                        type="password"
                        placeholder="Retype your password"
                        required
                    />
                </div>
            </div>

            <div class="submit">
                <button type="submit" class="btn">Register</button>
                <p class="hint">After registering, you can log in and complete your CV details.</p>
            </div>
        </form>
        </section>
    </main>
</body>
</html>


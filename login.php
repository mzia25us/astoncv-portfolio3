<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Start session with required files. 
session_start();
include 'database.php';
include 'csrf.php';

$errors = [];

//Show a message if the previous session expired due to inactivity.
if (isset($_GET['timeout'])) {
    $errors[] = "Your session has expired due to inactivity. Please log in again.";
}

$email = "";

//Process login form whenn submitted.
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $csrf_token = $_POST['csrf_token'] ?? '';

    //Verify CSRF token to protect against forged requests.
    if (!verify_csrf_token($csrf_token)) {
        $errors[] = "Invalid request. Please refresh page and try again.";
    }

    //Retrieve and validate user input.
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '') {
        $errors[] = "Email is required.";
    }

    if ($password === '') {
        $errors[] = "Password is required.";
    }

    //If validation passes, check login credentials securely.
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, name, email, password FROM cvs WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            //Verify the submitted password against the hashed password.
            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);

                //Store user details in the session after successful login.
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['last_activity'] = time();

                header("Location: updatecv.php");
                exit();
            } else {
                $errors[] = "Incorrect password.";
            }
        } else {
            $errors[] = "No account found with that email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Login | AstonCV</title>
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
                <a href="login.php" class="nav-link active">Login</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <h2 class="page-title">Log in to your account</h2>
        <p class="page-subtitle">Access your profile and update your CV details.</p>

        <section class="contact-me">

            <?php if (!empty($errors)): ?>
                <div class="modal-block">
                    <?php foreach ($errors as $error): ?>
                        <p class="error"><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>"> 
                <div class="grid">
                    <div class="field">
                        <label for="email">Email </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            placeholder="e.g. name@gmail.com"
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
                            placeholder="Enter your password"
                            required
                        />
                    </div>
                </div>

                <div class="submit">
                    <button type="submit" class="btn">Login</button>
                    <p class="hint">Don't have an account yet? <a href="register.php">Sign up now to get ahead in your career!</a></p>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
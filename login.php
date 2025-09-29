<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    
    $sql = "SELECT id, username, password, role, unit FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['unit'] = $user['unit'];
        header("Location: " . ($user['role'] == 'tutor' ? "tutor_dashboard.php" : ($user['role'] == 'admin' ? "manage_content.php" : "dashboard.php")));
    } else {
        $error = "Invalid email or password.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BrainWave</title>
    <link rel="icon" href="img/logo.png" type="image/png" >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        .body{
            background-image: url('img/background.svg');
            background-size: cover;
        }
        .container{
            max-height: 60vh;
            max-width: 450px;
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            padding: 20px;
            position: relative;
        }
    </style>
</head>
<body class="body">
    <br>
    <br>
    <br>
    <br>
    <div class="container py-5">
        <h2 class="text-center">Login</h2>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST" class="w-70 mx-auto">
            <div class="mb-3">
                <label for="email" class="form-label" style="color: black;">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label" style="color: black;">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-outline-dark w-100">Login</button>
        </form>
        <p class="text-center mt-3" style="color: black;">Don't have an account? <a href="register.php" class="text-success">Register here</a></p>
    </div>
    <footer class="bg-dark fixed-bottom text-white text-center py-4 mt-auto">
        <p>&copy; 2025 BrainWave. All rights reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
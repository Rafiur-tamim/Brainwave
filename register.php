<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $conn->real_escape_string($_POST['role']);
    $unit = $role == 'student' ? $conn->real_escape_string($_POST['unit']) : NULL;
    
    $sql = "INSERT INTO users (username, email, password, role, unit) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $username, $email, $password, $role, $unit);
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        $_SESSION['unit'] = $unit;
        header("Location: " . ($role == 'tutor' ? "tutor_dashboard.php" : "dashboard.php"));
    } else {
        $error = "Error: " . $conn->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BrainWave</title>
    <link rel="icon" href="img/logo.png" type="image/png" >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        .body {
            background-image: url('img/background.svg');
            background-size: cover;
        }
        .container {
            max-height: 90vh;
            max-width: 750px;
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            padding: 20px;
            align-items: center;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.82);
            border: 0px 0px 3px solid #0a0202;
            border-radius: 4px;
            padding: 7px;
    transition: 0.3s;
}
.form-control:focus {
    border-color: #007bff;
    outline: none;
}
    </style>
</head>
<body class="body">
    <br>
    <div class="container py-5">
        <h2 class="text-center">Register</h2>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST" class="w-50 mx-auto">
            <div class="mb-3">
                <label for="username" class="form-label" style="color: black;">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label" style="color: black;">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label" style="color: black;">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label" style="color: black;">Role</label>
                <select class="form-control" id="role" name="role" required onchange="toggleUnitField()">
                    <option value="student">Student</option>
                    <option value="tutor">Tutor</option>
                </select>
            </div>
            <div class="mb-3" id="unit_field">
                <label for="unit" class="form-label" style="color: black;">Admission Unit</label>
                <select class="form-control" id="unit" name="unit">
                    <option value="Unit A">Unit A (Science)</option>
                    <option value="Unit B">Unit B (Science, Business Studies)</option>
                    <option value="Unit C">Unit C (Science, Business Studies, Arts)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-outline-success w-100">Register</button>
        </form>
        <p class="text-center mt-3" style="color: black;">Already have an account? <a href="login.php" class="text-success">Login here</a></p>
    </div>
    <div>
        <br>
        <br>
    </div>

    <br>
    <br>
    <footer class="bg-dark fixed-bottom text-white text-center py-3">
        <p>&copy; 2025 BrainWave. All rights reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleUnitField() {
            const role = document.getElementById('role').value;
            const unitField = document.getElementById('unit_field');
            unitField.style.display = role === 'student' ? 'block' : 'none';
            document.getElementById('unit').required = role === 'student';
        }
        toggleUnitField(); // Initial call
    </script>
</body>
</html>
<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'tutor') {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// Fetch student performance
$sql = "SELECT u.username, r.score, r.total_questions, t.title, t.subject, r.completed_at 
        FROM user_results r 
        JOIN users u ON r.user_id = u.id 
        JOIN tests t ON r.test_id = t.id 
        WHERE u.role = 'student'";
$results = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Dashboard - BrainWave</title>
    <link rel="icon" href="img/logo.png" type="image/png" >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        .body{
            background-image: url('img/background.svg');
            background-size: cover;
            background-position: center;
        }

    </style>
</head>
<body class="body">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php"><img src="img/logo.png" alt="BrainWave Logo" class="logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container py-5">
        <h2>Welcome, Tutor <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>!</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Manage Study Materials</h5>
                        <p class="card-text">Upload, edit, or update study materials.</p>
                        <a href="upload.php" class="btn btn-primary">Manage Materials</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Manage Tests</h5>
                        <p class="card-text">Create or edit tests and questions.</p>
                        <a href="manage_content.php" class="btn btn-primary">Manage Tests</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Schedule Live Classes</h5>
                        <p class="card-text">Schedule and manage live classes.</p>
                        <a href="live_classes.php" class="btn btn-primary">Schedule Classes</a>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-4">
                <h3>Student Performance</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Test</th>
                            <th>Subject</th>
                            <th>Score</th>
                            <th>Total Questions</th>
                            <th>Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($result = $results->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($result['username']); ?></td>
                                <td><?php echo htmlspecialchars($result['title']); ?></td>
                                <td><?php echo htmlspecialchars($result['subject']); ?></td>
                                <td><?php echo $result['score']; ?></td>
                                <td><?php echo $result['total_questions']; ?></td>
                                <td><?php echo $result['completed_at']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <footer class="bg-dark fixed-bottom text-white text-center py-3">
        <p>&copy; 2025 BrainWave. All rights reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
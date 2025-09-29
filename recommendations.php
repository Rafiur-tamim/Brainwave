<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$unit = $_SESSION['unit'];
// Recommend materials/tests for weaker subjects (average score < 50%)
$sql = "SELECT subject FROM user_progress WHERE user_id = $user_id AND unit = '$unit' AND score / total_attempts < 50";
$weak_subjects = $conn->query($sql);
$recommended_materials = [];
$recommended_tests = [];
while ($row = $weak_subjects->fetch_assoc()) {
    $subject = $row['subject'];
    $sql = "SELECT * FROM study_materials WHERE unit = '$unit' AND subject = '$subject' AND difficulty = 'easy' LIMIT 2";
    $materials = $conn->query($sql);
    while ($material = $materials->fetch_assoc()) {
        $recommended_materials[] = $material;
    }
    $sql = "SELECT * FROM tests WHERE unit = '$unit' AND subject = '$subject' AND difficulty = 'easy' LIMIT 2";
    $tests = $conn->query($sql);
    while ($test = $tests->fetch_assoc()) {
        $recommended_tests[] = $test;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recommendations - BrainWave</title>
    <link rel="icon" href="img/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-image: url('img/background.svg');
            background-size: cover;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        h1,h2,h3,h4,h5,h6 { color: white; }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 60px;
            height: 100%;
            background: #111;
            color: #fff;
            transition: width 0.4s;
            z-index: 1000;
            overflow: hidden;
        }
        .sidebar.expanded { width: 220px; }
        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 60px;
            font-size: 1.5rem;
            border-bottom: 1px solid #333;
            padding: 0 10px;
        }
        .sidebar.expanded .sidebar-header { justify-content: space-between; }
        .menu-toggle { cursor: pointer; font-size: 1.5rem; color: #fff; }

        .sidebar a {
            display: flex;
            align-items: center;
            color: #fff;
            padding: 15px 15px 15px 25px;
            text-decoration: none;
            white-space: nowrap;
            overflow: hidden;
            transition: background 0.3s, padding-left 0.3s;
        }
        .sidebar a i { width: 25px; text-align: center; margin-right: 10px; font-size: 1.2rem; }
        .sidebar a span { opacity: 0; transition: opacity 0.3s; }
        .sidebar.expanded a span { opacity: 1; }
        .sidebar a:hover { background: #00838d; border-radius: 0 25px 25px 0; }

        /* Content */
        .content {
            transition: margin-left 0.4s;
            margin-left: 60px;
            padding: 80px 20px 20px 20px;
        }
        .content.shifted { margin-left: 220px; }

        /* Card styling */
        .material-card { display: flex; flex-direction: column; height: 100%; }
        .material-card .card-body { flex: 1; display: flex; flex-direction: column; }
        .material-card .btn { margin-top: auto; }

        /* Hover effect on cards */
        .card:hover { transform: translateY(-5px); transition: transform 0.3s; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }

        /* Responsive */
        @media (max-width: 992px) {
            .content { margin-left: 60px; padding: 80px 15px 15px 15px; }
        }
        @media (max-width: 576px) {
            .content { margin-left: 0; padding: 100px 10px 10px 10px; }
            .sidebar { width: 100%; height: auto; position: relative; }
            .sidebar.expanded { width: 100%; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div id="logo"><i class="fas fa-brain"></i></div>
            <div class="menu-toggle" id="menuToggle"><i class="fas fa-bars" id="toggleIcon"></i></div>
        </div>
        <a href="index.php"><i class="fas fa-home"></i> <span>Home</span></a>
        <a href="materials.php"><i class="fas fa-book"></i> <span>Study Materials</span></a>
        <a href="tests.php"><i class="fas fa-pencil-alt"></i> <span>Mock Tests</span></a>
        <a href="analytics.php"><i class="fas fa-chart-line"></i> <span>Analytics</span></a>
        <a href="recommendations.php"><i class="fas fa-lightbulb"></i> <span>Recommendations</span></a>
        <a href="badges.php"><i class="fas fa-award"></i> <span>Badges</span></a>
        <a href="leaderboard.php"><i class="fas fa-trophy"></i> <span>Leaderboard</span></a>
        <a href="forums.php"><i class="fas fa-comments"></i> <span>Discussion Forums</span></a>
        <a href="live_classes.php"><i class="fas fa-video"></i> <span>Live Classes</span></a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
    </div>

    <!-- Main Content -->
    <div class="content" id="mainContent">
        <div class="container py-5">
            <h2>Personalized Recommendations for <?php echo htmlspecialchars($unit); ?></h2>

            <h3>Recommended Study Materials</h3>
            <div class="row">
                <?php foreach ($recommended_materials as $material): ?>
                    <div class="col-md-4 mb-4 d-flex">
                        <div class="card w-100 material-card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($material['title']); ?></h5>
                                <p class="card-text">
                                    Subject: <?php echo $material['subject']; ?><br>
                                    Type: <?php echo $material['type']; ?><br>
                                    Difficulty: <?php echo $material['difficulty']; ?>
                                </p>
                                <a href="<?php echo $material['file_path']; ?>" class="btn btn-primary">Access</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <h3>Recommended Tests</h3>
            <div class="row">
                <?php foreach ($recommended_tests as $test): ?>
                    <div class="col-md-4 mb-4 d-flex">
                        <div class="card w-100 material-card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($test['title']); ?></h5>
                                <p class="card-text">
                                    Subject: <?php echo $test['subject']; ?><br>
                                    Difficulty: <?php echo $test['difficulty']; ?><br>
                                    Duration: <?php echo $test['duration']; ?> minutes
                                </p>
                                <a href="take_test.php?id=<?php echo $test['id']; ?>" class="btn btn-primary">Start Test</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 BrainWave. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const menuToggle = document.getElementById('menuToggle');
        const toggleIcon = document.getElementById('toggleIcon');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('expanded');
            mainContent.classList.toggle('shifted');
            toggleIcon.classList.toggle('fa-bars');
            toggleIcon.classList.toggle('fa-xmark');
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>
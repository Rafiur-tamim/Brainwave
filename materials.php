<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$unit = $_SESSION['unit'];
$subject = isset($_GET['subject']) ? $conn->real_escape_string($_GET['subject']) : '';
$sql = "SELECT * FROM study_materials WHERE unit = '$unit'" . ($subject ? " AND subject = '$subject'" : "");
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Study Materials - BrainWave</title>
<link rel="icon" href="img/logo.png" type="image/png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
/* Background & Body */
body {
    font-family: 'Nunito', sans-serif;
    background-image: url('img/background.svg');
    background-size: cover;
    margin: 0;
    padding: 0;
    overflow-x: hidden;
}

/* Sidebar */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 60px; /* collapsed */
    height: 100%;
    background: #111;
    color: #fff;
    transition: width 0.4s;
    z-index: 1000;
    overflow: hidden;
}

.sidebar.expanded {
    width: 220px; /* expanded */
}

.sidebar-header {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 60px;
    font-size: 1.5rem;
    border-bottom: 1px solid #333;
    padding: 0 10px;
}

.sidebar.expanded .sidebar-header {
    justify-content: space-between;
}

.menu-toggle {
    cursor: pointer;
    font-size: 1.5rem;
    color: #fff;
}

/* Sidebar Links */
.sidebar a {
    display: flex;
    align-items: center;
    color: #fff;
    padding: 15px;
    text-decoration: none;
    white-space: nowrap;
    overflow: hidden;
    transition: background 0.3s, padding-left 0.3s;
}

.sidebar a i {
    width: 25px;
    text-align: center;
    margin-right: 10px;
    font-size: 1.2rem;
}

.sidebar a span {
    opacity: 0;
    transition: opacity 0.3s;
}

.sidebar.expanded a span {
    opacity: 1;
}

.sidebar a:hover {
    background: #00838d;
    border-radius: 0 25px 25px 0;
}

/* Content */
.content {
    transition: margin-left 0.4s;
    margin-left: 60px; /* collapsed sidebar */
    padding: 80px 20px 20px 20px;
}

.content.shifted {
    margin-left: 220px; /* expanded sidebar */
}

/* Card styling for same height */
.material-card {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.material-card .card-body {
    flex: 1;
}

/* Responsive grid */
@media (max-width: 992px) {
    .content {
        margin-left: 60px;
        padding: 80px 15px 15px 15px;
    }
}
@media (max-width: 576px) {
    .content {
        margin-left: 0;
        padding: 100px 10px 10px 10px;
    }
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }
    .sidebar.expanded {
        width: 100%;
    }
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
    <a href="index.php"><i class="fas fa-home"></i> <span> Home</span></a>
    <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> <span> Dashboard</span></a>
    <a href="tests.php"><i class="fas fa-pencil-alt"></i> <span> Mock Tests</span></a>
    <a href="analytics.php"><i class="fas fa-chart-line"></i> <span> Analytics</span></a>
    <a href="recommendations.php"><i class="fas fa-lightbulb"></i> <span> Recommendations</span></a>
    <a href="badges.php"><i class="fas fa-award"></i> <span> Badges</span></a>
    <a href="leaderboard.php"><i class="fas fa-trophy"></i> <span> Leaderboard</span></a>
    <a href="forums.php"><i class="fas fa-comments"></i> <span> Discussion Forums</span></a>
    <a href="live_classes.php"><i class="fas fa-video"></i> <span> Live Classes</span></a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span> Logout</span></a>
</div>

<!-- Main Content -->
<div class="content" id="mainContent">
    <div class="container py-3">
        <h2 style="color:white;">Study Materials for <?php echo htmlspecialchars($unit); ?></h2>
        <form class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <select class="form-control" name="subject" onchange="this.form.submit()">
                        <option value="">All Subjects</option>
                        <?php
                        $subjects = $unit == 'Unit A' ? ['Physics', 'Chemistry', 'Biology', 'Mathematics'] :
                            ($unit == 'Unit B' ? ['Physics', 'Chemistry', 'Biology', 'Mathematics', 'Business Studies', 'Accounting', 'Economics'] :
                            ['Physics', 'Chemistry', 'Biology', 'Mathematics', 'Business Studies', 'Accounting', 'Economics', 'History', 'Literature', 'Sociology']);
                        foreach ($subjects as $subj) {
                            echo "<option value='$subj'" . ($subject == $subj ? " selected" : "") . ">$subj</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </form>

        <div class="row g-3">
            <?php while ($material = $result->fetch_assoc()): ?>
                <div class="col-md-4 col-sm-6">
                    <div class="card material-card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($material['title']); ?></h5>
                            <p class="card-text">
                                Type: <?php echo $material['type']; ?><br>
                                Subject: <?php echo $material['subject']; ?><br>
                                Difficulty: <?php echo $material['difficulty']; ?>
                            </p>
                            <?php if ($material['type'] == 'video'): ?>
                                <video controls class="w-100" style="max-height: 200px;">
                                    <source src="<?php echo htmlspecialchars($material['file_path']); ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            <?php else: ?>
                                <a href="<?php echo htmlspecialchars($material['file_path']); ?>" class="btn btn-primary" target="_blank">Access</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3 fixed-bottom">
    <p>&copy; 2025 BrainWave. All rights reserved.</p>
</footer>

<!-- JS -->
<script>
const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('mainContent');
const menuToggle = document.getElementById('menuToggle');
const toggleIcon = document.getElementById('toggleIcon');

menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('expanded');
    mainContent.classList.toggle('shifted');
    toggleIcon.classList.toggle('fa-bars'); // hamburger icon
    toggleIcon.classList.toggle('fa-xmark'); // close icon
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

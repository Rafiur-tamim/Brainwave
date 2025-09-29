<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - BrainWave</title>
<link rel="icon" href="img/logo.png" type="image/png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
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
    width: 60px; /* collapsed width */
    height: 100%;
    background: #111;
    color: #fff;
    transition: width 0.4s;
    z-index: 1000;
    overflow: hidden;
  }

  .sidebar.expanded {
    width: 220px; /* expanded width */
  }

  /* Sidebar header */
  .sidebar-header {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 60px;
    font-size: 1.5rem;
    border-bottom: 1px solid #333;
  }

  .sidebar.expanded .sidebar-header {
    justify-content: space-between;
    padding: 0 15px;
  }

  /* Hamburger / Close */
  .menu-toggle {
    cursor: pointer;
    font-size: 1.5rem;
  }

  /* Sidebar links */
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
  /*make space between icon and text*/
  .sidebar a {
    padding-left: 25px;
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
    padding-left: 25px;
  }

  /* Content shift */
  .content {
    transition: margin-left 0.4s;
    margin-left: 60px; /* collapsed margin */
    padding: 20px;
  }

  .content.shifted {
    margin-left: 220px; /* expanded margin */
  }
  #content {
    margin-left: 70px;
    padding: 80px 20px 20px 20px; /* padding-top ensures content below toggle button */
    transition: margin-left 0.3s;
    z-index: 100;
}
#sidebar.expanded ~ #content { margin-left: 220px; }

  /* Dashboard cards */
  .card-title {
    font-weight: bold;
  }
  /* ================= Dashboard Cards ================= */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}
.dashboard-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    padding: 25px 15px;
    transition: transform 0.3s, box-shadow 0.3s;
    min-height: 250px;
}
.dashboard-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
}
.dashboard-card i {
    font-size: 50px;
    color: #0d6efd;
    margin-bottom: 15px;
}
.dashboard-card h5 { font-weight: 600; margin-bottom: 10px; text-align: center; }
.dashboard-card p { flex-grow: 1; font-size: 0.95rem; color: #555; text-align: center; }
.dashboard-card a.btn { margin-top: auto; }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <div id="logo"><i class="fas fa-brain"></i></div>
    <div class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></div>
  </div>
  <a href="index.php" title="Home"><i class="fas fa-home"></i> <span> Home</span></a>
  <a href="materials.php" title="Browse Study Materials"><i class="fas fa-book"></i> <span> Study Materials</span></a>
  <a href="tests.php" title="Take Mock Tests"><i class="fas fa-pencil-alt"></i> <span> Mock Tests</span></a>
  <a href="analytics.php" title="View Analytics"><i class="fas fa-chart-line"></i> <span> Analytics</span></a>
  <a href="recommendations.php" title="View Recommendations"><i class="fas fa-lightbulb"></i> <span> Recommendations</span></a>
  <a href="badges.php" title="View Badges"><i class="fas fa-award"></i> <span> Badges</span></a>
  <a href="leaderboard.php" title="View Leaderboard"><i class="fas fa-trophy"></i> <span> Leaderboard</span></a>
  <a href="forums.php" title="Join Discussion Forums"><i class="fas fa-comments"></i> <span> Discussion Forums</span></a>
  <a href="live_classes.php" title="Join Live Classes"><i class="fas fa-video"></i> <span> Live Classes</span></a>
  <a href="logout.php" title="Logout"><i class="fas fa-sign-out-alt"></i> <span> Logout</span></a>
</div>
<div id="content">
    <h2 style="color:white;">Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>!</h2>
    <p style="color:white;">Preparing for <?php echo htmlspecialchars($_SESSION['unit']); ?></p>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <i class="fas fa-book"></i>
            <h5>Study Materials</h5>
            <p>Access eBooks, videos, and articles tailored for your unit.</p>
            <a href="materials.php" class="btn btn-success">Browse</a>
        </div>
        <div class="dashboard-card">
            <i class="fas fa-pencil-alt"></i>
            <h5>Mock Tests</h5>
            <p>Take practice tests and mock exams for your unit.</p>
            <a href="tests.php" class="btn btn-success">Start Test</a>
        </div>
        <div class="dashboard-card">
            <i class="fas fa-chart-line"></i>
            <h5>Analytics</h5>
            <p>View your progress and scores by subject.</p>
            <a href="analytics.php" class="btn btn-success">View Analytics</a>
        </div>
        <div class="dashboard-card">
            <i class="fas fa-award"></i>
            <h5>Badges</h5>
            <p>View your earned badges and achievements.</p>
            <a href="badges.php" class="btn btn-success">View Badges</a>
        </div>
        <div class="dashboard-card">
            <i class="fas fa-users"></i>
            <h5>Discussion Forums</h5>
            <p>Join discussions with peers for your unit's subjects.</p>
            <a href="forums.php" class="btn btn-success">Join Forums</a>
        </div>
        <div class="dashboard-card">
            <i class="fas fa-chalkboard-teacher"></i>
            <h5>Live Classes</h5>
            <p>Join scheduled live classes for your unit.</p>
            <a href="live_classes.php" class="btn btn-success">View Classes</a>
        </div>
    </div>
</div>
<br>
<br>
<br>
<br>

<!--Footer-->
<footer class="bg-dark text-white text-center py-3 mt-4">
  <div class="container">
    <p class="mb-0">© 2023 BrainWave. All rights reserved.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<script>
  const sidebar = document.getElementById('sidebar');
  const mainContent = document.getElementById('mainContent');
  const menuToggle = document.getElementById('menuToggle');
  

 menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('expanded');
    mainContent.classList.toggle('shifted');
    toggleIcon.classList.toggle('fa-bars');   // hamburger icon
    toggleIcon.classList.toggle('fa-cross');  // close icon
});
</script>
</body>
</html>


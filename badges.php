<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// Fetch user test results
$sql_results = "SELECT r.id as result_id, r.score, r.total_questions, t.title as test_name
                FROM user_results r
                JOIN tests t ON r.test_id = t.id
                WHERE r.user_id = ?";
$stmt = $conn->prepare($sql_results);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$results = [];
while($row = $result->fetch_assoc()) {
    $results[] = $row;
}
$stmt->close();

// Award badges based on test score (e.g., >=70% = Gold, >=50% = Silver, >=30% = Bronze)
foreach($results as $r) {
    $percentage = ($r['score'] / $r['total_questions']) * 100;
    if ($percentage >= 70) $badge_name = "Gold Star";
    elseif ($percentage >= 50) $badge_name = "Silver Star";
    elseif ($percentage >= 30) $badge_name = "Bronze Star";
    else $badge_name = null;

    if($badge_name) {
        $stmt_insert = $conn->prepare(
            "INSERT IGNORE INTO badges (user_id, badge_name, description, result_id) VALUES (?, ?, ?, ?)"
        );
        $description = "Scored {$r['score']}/{$r['total_questions']} in {$r['test_name']}";
        $stmt_insert->bind_param("issi", $user_id, $badge_name, $description, $r['result_id']);
        $stmt_insert->execute();
        $stmt_insert->close();
    }
}

// Fetch all badges for this user
$sql_badges = "SELECT * FROM badges WHERE user_id = ?";
$stmt = $conn->prepare($sql_badges);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$badges_result = $stmt->get_result();
$badges = [];
while($b = $badges_result->fetch_assoc()){
    $badges[] = $b;
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Badges - BrainWave</title>
<link rel="icon" href="img/logo.png" type="image/png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body { font-family: 'Nunito', sans-serif; background-image: url('img/background.svg'); background-size: cover; margin:0; padding:0; color:white; }
/* Sidebar */
.sidebar { position:fixed; top:0; left:0; width:60px; height:100%; background:#111; transition:0.4s; z-index:1000; overflow:hidden; }
.sidebar.expanded{ width:220px; }
.sidebar-header { display:flex; align-items:center; justify-content:center; height:60px; font-size:1.5rem; border-bottom:1px solid #333; padding:0 10px; }
.sidebar.expanded .sidebar-header { justify-content:space-between; }
.menu-toggle { cursor:pointer; font-size:1.5rem; color:#fff; }
.sidebar a { display:flex; align-items:center; color:#fff; padding:15px 15px 15px 25px; text-decoration:none; white-space:nowrap; overflow:hidden; transition:0.3s; }
.sidebar a i { width:25px; text-align:center; margin-right:10px; font-size:1.2rem; }
.sidebar a span { opacity:0; transition:0.3s; }
.sidebar.expanded a span { opacity:1; }
.sidebar a:hover { background:#00838d; border-radius:0 25px 25px 0; }
/* Content */
.content { transition:0.4s; margin-left:60px; padding:80px 20px 20px 20px; }
.content.shifted { margin-left:220px; }
.card { background: rgba(0,0,0,0.5); color:white; border:none; }
.card-title { display:flex; align-items:center; gap:10px; }
.card i { font-size:1.5rem; color:gold; }
@media (max-width:992px){.content{margin-left:60px;padding:80px 15px;}}
@media (max-width:576px){.content{margin-left:0;padding:100px 10px;} .sidebar{width:100%;height:auto;position:relative;} .sidebar.expanded{width:100%;}}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div id="logo"><i class="fas fa-brain"></i></div>
        <div class="menu-toggle" id="menuToggle"><i class="fas fa-bars" id="toggleIcon"></i></div>
    </div>
    <a href="index.php" title="Home"><i class="fas fa-home"></i> <span>Home</span></a>
    <a href="dashboard.php" title="Dashboard"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
    <a href="materials.php" title="Study Materials"><i class="fas fa-book"></i> <span>Study Materials</span></a>
    <a href="tests.php" title="Mock Tests"><i class="fas fa-pencil-alt"></i> <span>Mock Tests</span></a>
    <a href="analytics.php" title="Analytics"><i class="fas fa-chart-line"></i> <span>Analytics</span></a>
    <a href="recommendations.php" title="Recommendations"><i class="fas fa-lightbulb"></i> <span>Recommendations</span></a>
    <a href="forums.php" title="Discussion Forums"><i class="fas fa-comments"></i> <span>Discussion Forums</span></a>
    <a href="leaderboard.php" title="Leaderboard"><i class="fas fa-trophy"></i> <span>Leaderboard</span></a>
    <a href="live_classes.php" title="Live Classes"><i class="fas fa-video"></i> <span>Live
    <a href="logout.php" title="Logout"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
</div>

<div class="content" id="mainContent">
    <div class="container py-4">
        <h2>🎖️ Your Badges</h2>
        <div class="row">
            <?php if(count($badges) > 0): ?>
                <?php foreach($badges as $b): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card p-3">
                            <h5 class="card-title">
                                <?php
                                    if($b['badge_name']=="Gold Star") echo '<i class="fas fa-star"></i>';
                                    elseif($b['badge_name']=="Silver Star") echo '<i class="fas fa-star-half-alt"></i>';
                                    else echo '<i class="far fa-star"></i>';
                                ?>
                                <?php echo htmlspecialchars($b['badge_name']); ?>
                            </h5>
                            <p class="card-text"><?php echo htmlspecialchars($b['description']); ?></p>
                            <p>Earned on: <?php echo $b['earned_at']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No badges earned yet. Take some tests to earn badges!</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Sidebar toggle
const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('mainContent');
const menuToggle = document.getElementById('menuToggle');
const toggleIcon = document.getElementById('toggleIcon');
menuToggle.addEventListener('click', ()=>{
    sidebar.classList.toggle('expanded');
    mainContent.classList.toggle('shifted');
    toggleIcon.classList.toggle('fa-bars');
    toggleIcon.classList.toggle('fa-xmark');
});
</script>
</body>
</html>

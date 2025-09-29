<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Assume logged-in username is stored in session
$loggedInUser = $_SESSION['username'] ?? '';

// --- Leaderboard (Top 10) ---
$sql_leader = "SELECT u.username, 
               IFNULL(AVG(r.score / NULLIF(r.total_questions,0)) * 100,0) AS avg_score
               FROM user_results r
               JOIN users u ON r.user_id = u.id
               GROUP BY r.user_id
               ORDER BY avg_score DESC
               LIMIT 10";

$leaderboard_result = $conn->query($sql_leader);

$leaderboard_data = [];
$usernames = [];
$avg_scores = [];

if($leaderboard_result && $leaderboard_result->num_rows > 0){
    while ($row = $leaderboard_result->fetch_assoc()) {
        $leaderboard_data[] = $row; 
        $usernames[] = $row['username']; 
        $avg_scores[] = round($row['avg_score'], 2);
    }
}

// --- Logged-in student test results ---
$student_data = [];
if ($loggedInUser) {
    $sql_student = "SELECT r.id AS test_id, r.score, r.total_questions
                    FROM user_results r
                    JOIN users u ON r.user_id = u.id
                    WHERE u.username = ?
                    ORDER BY r.id";
    $stmt = $conn->prepare($sql_student);
    $stmt->bind_param("s", $loggedInUser);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $student_data[] = [
            'test_name' => "Test " . $row['test_id'],
            'score' => $row['score'],
            'total' => $row['total_questions']
        ];
    }
    $stmt->close();
}

// --- Logged-in student rank ---
$user_rank = null;
$total_students = 0;
if ($loggedInUser) {
    $sql_rank = "SELECT u.username,
                        IFNULL(AVG(r.score / NULLIF(r.total_questions,0)) * 100, 0) AS avg_score
                 FROM user_results r
                 JOIN users u ON r.user_id = u.id
                 GROUP BY r.user_id
                 ORDER BY avg_score DESC";
    $rank_result = $conn->query($sql_rank);

    if ($rank_result) {
        $rank = 1;
        while ($row = $rank_result->fetch_assoc()) {
            $total_students++;
            if ($row['username'] === $loggedInUser) {
                $user_rank = $rank;
            }
            $rank++;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Analytics - BrainWave</title>
<link rel="icon" href="img/logo.png" type="image/png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
.container-bg { background: rgba(0,0,0,0.5); padding:20px; border-radius:10px; margin-bottom:40px; }
.table th, .table td { color:black; }
.chart-container { padding:20px; border-radius:10px; background: rgba(0,0,0,0.5); margin-bottom:40px; }
@media (max-width:992px){.content{margin-left:60px;padding:80px 15px;} }
@media (max-width:576px){.content{margin-left:0;padding:100px 10px;} .sidebar{width:100%;height:auto;position:relative;} .sidebar.expanded{width:100%;} }
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
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
</div>

<div class="content" id="mainContent">
    <div class="container py-4">

        <!-- Leaderboard -->
        <h2>🏆 Top 10 Students</h2>
        <div class="container-bg">
            <table class="table table-bordered">
                <thead>
                    <tr><th>Rank</th><th>Username</th><th>Average Score (%)</th></tr>
                </thead>
                <tbody>
                    <?php if(count($leaderboard_data) > 0): ?>
                        <?php $rank=1; foreach($leaderboard_data as $row): ?>
                            <tr>
                                <td><?php echo $rank++; ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo number_format($row['avg_score'],2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3">No leaderboard data available.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Logged-in Student Progress -->
        <?php if($loggedInUser && count($student_data) > 0): 
            $labels=[]; $data=[];
            foreach($student_data as $test){
                $labels[]=$test['test_name'];
                $data[]=round(($test['score']/$test['total'])*100,2);
            }
        ?>
        <h2>📊 Your Progress</h2>
        <div class="chart-container">
            <canvas id="studentChart"></canvas>
        </div>
        <script>
        const ctx = document.getElementById('studentChart').getContext('2d');
        new Chart(ctx, {
            type:'line',
            data:{
                labels: <?php echo json_encode($labels); ?>,
                datasets:[{
                    label:'<?php echo $loggedInUser; ?> Progress (%)',
                    data: <?php echo json_encode($data); ?>,
                    borderColor:'#00ffc6',
                    backgroundColor:'rgba(0,255,198,0.2)',
                    fill:true,
                    tension:0.3,
                    pointRadius:5,
                    pointHoverRadius:7
                }]
            },
            options:{
                responsive:true,
                plugins:{legend:{labels:{color:'white'}}},
                scales:{
                    y:{beginAtZero:true,max:100,ticks:{color:'white'}},
                    x:{ticks:{color:'white'}}
                }
            }
        });
        </script>

        <!-- Logged-in Student Rank -->
        <?php if($user_rank): ?>
        <div class="container-bg text-center">
            <h4>🏅 Your Current Rank</h4>
            <p>You are ranked <strong>#<?php echo $user_rank; ?></strong> out of <?php echo $total_students; ?> students.</p>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <p>No test data available for your account.</p>
        <?php endif; ?>

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

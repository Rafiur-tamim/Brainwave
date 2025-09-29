<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
$sql = "SELECT u.username, AVG(r.score / r.total_questions) * 100 as avg_score
        FROM user_results r
        JOIN users u ON r.user_id = u.id
        GROUP BY r.user_id
        ORDER BY avg_score DESC
        LIMIT 10";
$leaderboard = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - BrainWave</title>
    <link rel="icon" href="img/logo.png" type="image/png" >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
body {
    font-family: 'Nunito', sans-serif;
    background-image: url('img/background.svg');
    background-size: cover;
    margin: 0;
    padding: 0;
    overflow-x: hidden;
}
h1, h2, h3, h4, h5, h6, p {
    color: white;
}
    </style>
</head>
<body>
    <div class="container py-5">
        <h2>Leaderboard</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Username</th>
                    <th>Average Score (%)</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; while ($row = $leaderboard->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $rank++; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo number_format($row['avg_score'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <footer class="bg-dark text-white text-center py-3 fixed-bottom">
        <p>&copy; 2025 BrainWave. All rights reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
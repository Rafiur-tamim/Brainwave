<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$unit = $_SESSION['unit'];
$subject = isset($_GET['subject']) ? $conn->real_escape_string($_GET['subject']) : '';
$sql = "SELECT * FROM tests WHERE unit = '$unit'" . ($subject ? " AND subject = '$subject'" : "");
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mock Tests - BrainWave</title>
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
        <h2>Mock Tests for <?php echo htmlspecialchars($unit); ?></h2>
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
        <div class="row">
            <?php while ($test = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
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
            <?php endwhile; ?>
        </div>
    </div>
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 BrainWave. All rights reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
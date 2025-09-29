<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit;
}
$test_id = (int)$_GET['id'];
$sql = "SELECT * FROM tests WHERE id = $test_id AND unit = '{$_SESSION['unit']}'";
$test = $conn->query($sql)->fetch_assoc();
if (!$test) {
    header("Location: tests.php");
    exit;
}
$sql = "SELECT * FROM test_questions WHERE test_id = $test_id";
$questions = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $score = 0;
    $total = $questions->num_rows;
    $answers = $_POST['answers'];
    $sql = "SELECT id, correct_answer, explanation FROM test_questions WHERE test_id = $test_id";
    $result = $conn->query($sql);
    $feedback = [];
    while ($row = $result->fetch_assoc()) {
        $correct = isset($answers[$row['id']]) && $answers[$row['id']] == $row['correct_answer'];
        if ($correct) {
            $score++;
        }
        $feedback[$row['id']] = [
            'correct' => $correct,
            'explanation' => $row['explanation']
        ];
    }
    $sql = "INSERT INTO user_results (user_id, test_id, score, total_questions) VALUES ({$_SESSION['user_id']}, $test_id, $score, $total)";
    $conn->query($sql);
    $sql = "INSERT INTO user_progress (user_id, unit, subject, score, total_attempts) 
            VALUES ({$_SESSION['user_id']}, '{$test['unit']}', '{$test['subject']}', $score, 1)
            ON DUPLICATE KEY UPDATE score = score + $score, total_attempts = total_attempts + 1";
    $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Test - BrainWave</title>
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
h1, h2, h3, h4, h5, h6{
    color: white;
}
    </style>
</head>
<body>
    <div class="container py-5">
        <h2><?php echo htmlspecialchars($test['title']); ?></h2>
        <div id="timer" class="mb-4 text-white">Time Remaining: <?php echo $test['duration']; ?>:00</div>
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <h3>Results</h3>
            <p>Score: <?php echo $score; ?> / <?php echo $total; ?></p>
            <?php $questions->data_seek(0); while ($question = $questions->fetch_assoc()): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <p><?php echo htmlspecialchars($question['question_text']); ?></p>
                        <p>Correct Answer: <?php echo $question['correct_answer']; ?></p>
                        <p>Status: <?php echo $feedback[$question['id']]['correct'] ? 'Correct' : 'Incorrect'; ?></p>
                        <p>Explanation: <?php echo htmlspecialchars($feedback[$question['id']]['explanation']); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
            <a href="analytics.php" class="btn btn-primary">View Analytics</a>
        <?php else: ?>
            <form method="POST">
                <?php while ($question = $questions->fetch_assoc()): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <p><?php echo htmlspecialchars($question['question_text']); ?></p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answers[<?php echo $question['id']; ?>]" value="A" required>
                                <label class="form-check-label"><?php echo htmlspecialchars($question['option_a']); ?></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answers[<?php echo $question['id']; ?>]" value="B">
                                <label class="form-check-label"><?php echo htmlspecialchars($question['option_b']); ?></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answers[<?php echo $question['id']; ?>]" value="C">
                                <label class="form-check-label"><?php echo htmlspecialchars($question['option_c']); ?></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answers[<?php echo $question['id']; ?>]" value="D">
                                <label class="form-check-label"><?php echo htmlspecialchars($question['option_d']); ?><//label>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
                <button type="submit" class="btn btn-primary">Submit Test</button>
            </form>
        <?php endif; ?>
    </div>
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 BrainWave. All rights reserved.</p>
    </footer>
    <script>
        let time = <?php echo $test['duration'] * 60; ?>;
        const timer = document.getElementById('timer');
        const interval = setInterval(() => {
            const minutes = Math.floor(time / 60);
            const seconds = time % 60;
            timer.textContent = `Time Remaining: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            time--;
            if (time < 0) {
                clearInterval(interval);
                document.querySelector('form').submit();
            }
        }, 1000);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
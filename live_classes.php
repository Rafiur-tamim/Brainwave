<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$unit = $_SESSION['unit'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $role == 'tutor') {
    $title = $conn->real_escape_string($_POST['title']);
    $unit = $conn->real_escape_string($_POST['unit']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $scheduled_at = $conn->real_escape_string($_POST['scheduled_at']);
    $link = $conn->real_escape_string($_POST['link']);
    
    $sql = "INSERT INTO live_classes (title, unit, subject, tutor_id, scheduled_at, link) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiss", $title, $unit, $subject, $user_id, $scheduled_at, $link);
    if ($stmt->execute()) {
        $message = "Live class scheduled successfully.";
    } else {
        $error = "Error: " . $conn->error;
    }
    $stmt->close();
}

// Determine the parameter based on role
$param = $role == 'tutor' ? $user_id : $unit;

$sql = $role == 'tutor' ? "SELECT * FROM live_classes WHERE tutor_id = ?" : "SELECT * FROM live_classes WHERE unit = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $param);
$stmt->execute();
$classes = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Classes - BrainWave</title>
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
        <h2>Live Classes</h2>
        <?php if (isset($message)) echo "<div class='alert alert-success'>$message</div>"; ?>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <?php if ($role == 'tutor'): ?>
            <h3>Schedule a New Class</h3>
            <form method="POST" class="mb-4">
                <div class="mb-3">
                    <label for="title" class="form-label">Class Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="unit" class="form-label">Unit</label>
                    <select class="form-control" id="unit" name="unit" required>
                        <option value="Unit A">Unit A (Science)</option>
                        <option value="Unit B">Unit B (Science, Business Studies)</option>
                        <option value="Unit C">Unit C (Science, Business Studies, Arts)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="subject" class="form-label">Subject</label>
                    <select class="form-control" id="subject" name="subject" required>
                        <option value="Physics">Physics</option>
                        <option value="Chemistry">Chemistry</option>
                        <option value="Biology">Biology</option>
                        <option value="Mathematics">Mathematics</option>
                        <option value="Business Studies">Business Studies</option>
                        <option value="Accounting">Accounting</option>
                        <option value="Economics">Economics</option>
                        <option value="History">History</option>
                        <option value="Literature">Literature</option>
                        <option value="Sociology">Sociology</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="scheduled_at" class="form-label">Scheduled Date & Time</label>
                    <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at" required>
                </div>
                <div class="mb-3">
                    <label for="link" class="form-label">Meeting Link (e.g., Zoom)</label>
                    <input type="url" class="form-control" id="link" name="link" required>
                </div>
                <button type="submit" class="btn btn-primary">Schedule Class</button>
            </form>
        <?php endif; ?>
        <h3 style="color:white;">Upcoming Classes</h3>
        <div class="row">
            <?php while ($class = $classes->fetch_assoc()): ?>
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($class['title']); ?></h5>
                            <p class="card-text">
                                Unit: <?php echo htmlspecialchars($class['unit']); ?><br>
                                Subject: <?php echo htmlspecialchars($class['subject']); ?><br>
                                Scheduled: <?php echo $class['scheduled_at']; ?>
                            </p>
                            <a href="<?php echo htmlspecialchars($class['link']); ?>" class="btn btn-primary" target="_blank">Join Class</a>
                            <?php if ($role == 'tutor'): ?>
                                <a href="delete_class.php?id=<?php echo $class['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this class?');">Delete</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <footer class="bg-dark text-white text-center py-3 fixed-bottom">
        <p>&copy; 2025 BrainWave. All rights reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $stmt->close(); ?>
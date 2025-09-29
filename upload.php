<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'tutor'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $type = $_POST['type'];
    $unit = $_POST['unit'];
    $subject = $_POST['subject'];
    $difficulty = $_POST['difficulty'];
    
    $target_dir = "Uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    
    $file = $_FILES['file'];
    $file_name = basename($file['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_extensions = ['pdf', 'mp4', 'avi', 'mov'];
    $max_file_size = 100 * 1024 * 1024; // 100MB
    
    if (!in_array($file_ext, $allowed_extensions)) {
        $error = "Invalid file type. Only PDF and video files (.pdf, .mp4, .avi, .mov) are allowed.";
    } elseif ($file['size'] > $max_file_size) {
        $error = "File size exceeds 100MB limit.";
    } elseif ($file['error'] !== UPLOAD_ERR_OK) {
        $error = "File upload error: " . $file['error'];
    } else {
        $new_file_name = uniqid() . '.' . $file_ext;
        $file_path = $target_dir . $new_file_name;
        
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            $sql = "INSERT INTO study_materials (title, type, unit, subject, difficulty, file_path, uploaded_by) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $title, $type, $unit, $subject, $difficulty, $file_path, $_SESSION['user_id']);
            
            if ($stmt->execute()) {
                $message = "File uploaded successfully.";
            } else {
                $error = "Database error: " . $conn->error;
                unlink($file_path);
            }
            $stmt->close();
        } else {
            $error = "Failed to move uploaded file.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Material - BrainWave</title>
    <link rel="icon" href="img/logo.png" type="image/png" >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container py-5">
        <h2>Upload Study Material</h2>
        <?php if (isset($message)) echo "<div class='alert alert-success'>$message</div>"; ?>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="ebook">eBook (PDF)</option>
                    <option value="video">Video (MP4, AVI, MOV)</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="unit" class="form-label">Unit</label>
                <select class="form-control" id="unit" name="unit" required>
                    <option value="Unit A">Unit A (Science, Business Studies, Arts)</option>
                    <option value="Unit B">Unit B (Arts)</option>
                    <option value="Unit C">Unit C (Business Studies, Arts)</option>
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
                <label for="difficulty" class="form-label">Difficulty</label>
                <select class="form-control" id="difficulty" name="difficulty" required>
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">Upload File (PDF or Video)</label>
                <input type="file" class="form-control" id="file" name="file" accept=".pdf,.mp4,.avi,.mov" required>
                <small class="form-text text-muted">Max file size: 100MB. Allowed formats: PDF, MP4, AVI, MOV.</small>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>
    <footer class="bg-dark text-white text-center py-3 fixed-bottom">
        <p>&copy; 2025 BrainWave. All rights reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
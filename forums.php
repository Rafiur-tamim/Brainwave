<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$unit = $_SESSION['unit'];
$subject = isset($_GET['subject']) ? $conn->real_escape_string($_GET['subject']) : '';
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_post'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $post_subject = $conn->real_escape_string($_POST['subject']);
    $sql = "INSERT INTO forum_posts (user_id, unit, subject, title, content) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $user_id, $unit, $post_subject, $title, $content);
    if ($stmt->execute()) {
        $message = "Post created successfully.";
    } else {
        $error = "Error creating post: " . $conn->error;
    }
    $stmt->close();
}

// Prepare the search term for LIKE clause
$search_term = $subject ? "%$subject%" : "%";

// SQL query with proper LIKE clause
$sql = "SELECT p.*, u.username FROM forum_posts p JOIN users u ON p.user_id = u.id WHERE p.unit = ? AND p.subject LIKE ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $unit, $search_term);
$stmt->execute();
$posts = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion Forums - BrainWave</title>
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
        <h2>Discussion Forums for <?php echo htmlspecialchars($unit); ?></h2>
        <?php if (isset($message)) echo "<div class='alert alert-success'>$message</div>"; ?>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
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
        <h3>Create a New Post</h3>
        <form method="POST" class="mb-4">
            <div class="mb-3">
                <label for="post_subject" class="form-label">Subject</label>
                <select class="form-control" id="post_subject" name="subject" required>
                    <?php foreach ($subjects as $subj): ?>
                        <option value="<?php echo $subj; ?>"><?php echo $subj; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Post Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
            </div>
            <button type="submit" name="create_post" class="btn btn-primary">Create Post</button>
        </form>
        <h3>Forum Posts</h3>
        <div class="row">
            <?php while ($post = $posts->fetch_assoc()): ?>
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h5>
                            <p class="card-text">
                                Subject: <?php echo htmlspecialchars($post['subject']); ?><br>
                                Posted by: <?php echo htmlspecialchars($post['username']); ?><br>
                                Posted on: <?php echo $post['created_at']; ?>
                            </p>
                            <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                            <a href="post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary">View Post</a>
                            <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'tutor'): ?>
                                <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                            <?php endif; ?>
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
<?php $stmt->close(); ?>
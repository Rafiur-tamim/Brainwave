<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit;
}
$post_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_comment'])) {
    $content = $conn->real_escape_string($_POST['content']);
    $sql = "INSERT INTO forum_comments (post_id, user_id, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $post_id, $user_id, $content);
    if ($stmt->execute()) {
        $message = "Comment added successfully.";
    } else {
        $error = "Error adding comment: " . $conn->error;
    }
    $stmt->close();
}

$sql = "SELECT p.*, u.username FROM forum_posts p JOIN users u ON p.user_id = u.id WHERE p.id = ? AND p.unit = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $post_id, $_SESSION['unit']);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
if (!$post) {
    header("Location: forums.php");
    exit;
}

$sql = "SELECT c.*, u.username FROM forum_comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ? ORDER BY c.created_at";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$comments = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post - BrainWave</title>
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
        <h2><?php echo htmlspecialchars($post['title']); ?></h2>
        <?php if (isset($message)) echo "<div class='alert alert-success'>$message</div>"; ?>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <div class="card mb-4">
            <div class="card-body">
                <p class="card-text">
                    Subject: <?php echo htmlspecialchars($post['subject']); ?><br>
                    Posted by: <?php echo htmlspecialchars($post['username']); ?><br>
                    Posted on: <?php echo $post['created_at']; ?>
                </p>
                <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
            </div>
        </div>
        <h3>Comments</h3>
        <?php while ($comment = $comments->fetch_assoc()): ?>
            <div class="card mb-2">
                <div class="card-body">
                    <p class="card-text" style="font-size: 0.9em; color: #105c6dff;">
                        Commented by: <?php echo htmlspecialchars($comment['username']); ?><br>
                        Commented on: <?php echo $comment['created_at']; ?>
                    </p>
                    <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                    <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'tutor'): ?>
                        <a href="delete_comment.php?id=<?php echo $comment['id']; ?>&post_id=<?php echo $post_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this comment?');">Delete</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
        <h3>Add a Comment</h3>
        <form method="POST" class="mb-4">
            <div class="mb-3">
                <label for="content" class="form-label text-white">Comment</label>
                <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
            </div>
            <button type="submit" name="add_comment" class="btn btn-primary">Add Comment</button>
        </form>
        <a href="forums.php" class="btn btn-secondary">Back to Forums</a>
    </div>
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 BrainWave. All rights reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $stmt->close(); ?>
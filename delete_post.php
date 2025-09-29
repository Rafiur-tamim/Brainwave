<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'tutor']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit;
}
$post_id = (int)$_GET['id'];

// Delete comments first
$sql = "DELETE FROM forum_comments WHERE post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$stmt->close();

// Delete post
$sql = "DELETE FROM forum_posts WHERE id = ? AND unit = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $post_id, $_SESSION['unit']);
$stmt->execute();
$stmt->close();

header("Location: forums.php");
exit;
?>
<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'tutor']) || !isset($_GET['id']) || !isset($_GET['post_id'])) {
    header("Location: login.php");
    exit;
}
$comment_id = (int)$_GET['id'];
$post_id = (int)$_GET['post_id'];

$sql = "DELETE FROM forum_comments WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $comment_id);
$stmt->execute();
$stmt->close();

header("Location: post.php?id=$post_id");
exit;
?>
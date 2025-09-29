<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'tutor']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit;
}
$question_id = (int)$_GET['id'];
$sql = "DELETE FROM test_questions WHERE id = ? AND test_id IN (SELECT id FROM tests WHERE created_by = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $question_id, $_SESSION['user_id']);
$stmt->execute();
$stmt->close();
header("Location: manage_content.php");
exit;
?>
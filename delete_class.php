<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'tutor' || !isset($_GET['id'])) {
    header("Location: login.php");
    exit;
}
$class_id = (int)$_GET['id'];
$sql = "DELETE FROM live_classes WHERE id = ? AND tutor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $class_id, $_SESSION['user_id']);
$stmt->execute();
$stmt->close();
header("Location: live_classes.php");
exit;
?>
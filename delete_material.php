<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'tutor']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit;
}
$material_id = (int)$_GET['id'];
$sql = "SELECT file_path FROM study_materials WHERE id = ? AND uploaded_by = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $material_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$material = $result->fetch_assoc();
if ($material || $_SESSION['role'] == 'admin') {
    if ($material && file_exists($material['file_path'])) {
        unlink($material['file_path']);
    }
    $sql = "DELETE FROM study_materials WHERE id = ? AND uploaded_by = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $material_id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
}
header("Location: manage_content.php");
exit;
?>
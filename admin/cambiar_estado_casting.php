<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../includes/db.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /castingApp/index.php");
    exit;
}

if(isset($_POST['casting_id']) && isset($_POST['estado'])){

    $casting_id = intval($_POST['casting_id']);
    $estado = $_POST['estado'];

    $stmt = $conn->prepare("UPDATE castings SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $estado, $casting_id);
    $stmt->execute();
}

header("Location: castings.php");
exit;

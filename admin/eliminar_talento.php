<?php
session_start();
include '../includes/db.php';

/* PROTECCION ADMIN */
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /castingApp/index.php");
    exit;
}

if (!isset($_POST['id'])) {
    header("Location: talentos.php");
    exit;
}

$id = $_POST['id'];

/* ELIMINAR TALENTO */
$stmt = $conn->prepare("DELETE FROM talentos WHERE usuario_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

/* REDIRIGIR */
header("Location: talentos.php");
exit;
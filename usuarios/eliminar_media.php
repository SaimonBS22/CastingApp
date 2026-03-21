<?php
session_start();
require_once "../includes/db.php";

if (!isset($_SESSION['user_id'])) die("No autorizado");

$user_id = $_SESSION['user_id'];
$media_id = $_POST['media_id'];

$stmt = $conn->prepare("
  SELECT * FROM talento_media
  WHERE id = ? AND usuario_id = ?
");
$stmt->bind_param("ii", $media_id, $user_id);
$stmt->execute();
$m = $stmt->get_result()->fetch_assoc();

if ($m) {
  if ($m['archivo']) {
    if ($m['tipo'] === 'foto') unlink("../uploads/fotos/".$m['archivo']);
    if ($m['tipo'] === 'video') unlink("../uploads/videos/".$m['archivo']);
  }

  $del = $conn->prepare("DELETE FROM talento_media WHERE id = ?");
  $del->bind_param("i", $media_id);
  $del->execute();
}

header("Location: editar_perfil.php");
exit;

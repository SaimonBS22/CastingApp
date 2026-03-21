<?php
session_start();
include './includes/db.php';

if (!isset($_SESSION['user_id'])) {
  die("No autorizado");
}

$usuario_id = $_SESSION['user_id'];

/* =========================
   EXPERIENCIA + OBSERVACIONES
========================= */
if (isset($_POST['experiencia'])) {
  $stmt = $conn->prepare("
    UPDATE talentos 
    SET experiencia = ?, observaciones = ?
    WHERE usuario_id = ?
  ");
  $stmt->bind_param(
    "ssi",
    $_POST['experiencia'],
    $_POST['observaciones'],
    $usuario_id
  );
  $stmt->execute();
}

/* =========================
   FOTOS
========================= */
if (!empty($_FILES['fotos']['name'][0])) {
  foreach ($_FILES['fotos']['tmp_name'] as $i => $tmp) {

    if ($_FILES['fotos']['error'][$i] === 0) {

      $nombre = uniqid() . "_" . basename($_FILES['fotos']['name'][$i]);
      $destino = "./uploads/fotos/" . $nombre;

      if (move_uploaded_file($tmp, $destino)) {
        $stmt = $conn->prepare("
          INSERT INTO talento_media (usuario_id, tipo, archivo)
          VALUES (?, 'foto', ?)
        ");
        $stmt->bind_param("is", $usuario_id, $nombre);
        $stmt->execute();
      }
    }
  }
}

/* =========================
   VIDEOS
========================= */
if (!empty($_FILES['videos']['name'][0])) {
  foreach ($_FILES['videos']['tmp_name'] as $i => $tmp) {

    if ($_FILES['videos']['error'][$i] === 0) {

      $nombre = uniqid() . "_" . basename($_FILES['videos']['name'][$i]);
      $destino = "./uploads/videos/" . $nombre;

      if (move_uploaded_file($tmp, $destino)) {
        $stmt = $conn->prepare("
          INSERT INTO talento_media (usuario_id, tipo, archivo)
          VALUES (?, 'video', ?)
        ");
        $stmt->bind_param("is", $usuario_id, $nombre);
        $stmt->execute();
      }
    }
  }
}

/* =========================
   LINKS
========================= */
if (!empty($_POST['links'])) {
  foreach ($_POST['links'] as $link) {
    if (!empty(trim($link))) {
      $stmt = $conn->prepare("
        INSERT INTO talento_media (usuario_id, tipo, url)
        VALUES (?, 'link', ?)
      ");
      $stmt->bind_param("is", $usuario_id, $link);
      $stmt->execute();
    }
  }
}

/* =========================
   HABILIDADES
========================= */
$conn->query("DELETE FROM usuario_habilidad WHERE usuario_id = $usuario_id");

if (!empty($_POST['habilidades'])) {
  foreach ($_POST['habilidades'] as $hab) {
    $stmt = $conn->prepare("
      INSERT INTO usuario_habilidad (usuario_id, habilidad_id)
      VALUES (?, ?)
    ");
    $stmt->bind_param("ii", $usuario_id, $hab);
    $stmt->execute();
  }
}

/* =========================
   REDIRECCION
========================= */
header("Location: usuarios/miPerfil.php");
exit;

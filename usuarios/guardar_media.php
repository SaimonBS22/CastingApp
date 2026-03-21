<?php
session_start();
require_once "../includes/db.php";

if (!isset($_SESSION['user_id'])) die("No autorizado");

$user_id = $_SESSION['user_id'];

/* ===== FOTOS ===== */
if (!empty($_FILES['fotos']['name'][0])) {

  foreach ($_FILES['fotos']['tmp_name'] as $i => $tmp) {

    if ($_FILES['fotos']['error'][$i] === 0) {

      $name = uniqid()."_".$_FILES['fotos']['name'][$i];

      move_uploaded_file($tmp, "../uploads/fotos/$name");

      $stmt = $conn->prepare("
        INSERT INTO talento_media (usuario_id, tipo, archivo)
        VALUES (?, 'foto', ?)
      ");

      $stmt->bind_param("is", $user_id, $name);
      $stmt->execute();
    }
  }

}



/* ===== VIDEOS ===== */
if (!empty($_FILES['videos']['name'][0])) {
  foreach ($_FILES['videos']['tmp_name'] as $i => $tmp) {
    $name = uniqid()."_".$_FILES['videos']['name'][$i];
    move_uploaded_file($tmp, "../uploads/videos/$name");

    $stmt = $conn->prepare("
      INSERT INTO talento_media (usuario_id, tipo, archivo)
      VALUES (?, 'video', ?)
    ");
    $stmt->bind_param("is", $user_id, $name);
    $stmt->execute();
  }
}

/* ===== LINKS ===== */
if (!empty($_POST['links'])) {
  foreach ($_POST['links'] as $link) {
    if ($link !== '') {
      $stmt = $conn->prepare("
        INSERT INTO talento_media (usuario_id, tipo, url)
        VALUES (?, 'link', ?)
      ");
      $stmt->bind_param("is", $user_id, $link);
      $stmt->execute();
    }
  }
}

header("Location: miPerfil.php");
exit;


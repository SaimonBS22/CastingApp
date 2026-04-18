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
   OBTENER TALENTO_ID
========================= */
$stmt = $conn->prepare("SELECT id FROM talentos WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$talento = $result->fetch_assoc();

if (!$talento) {
  die("Error: no existe talento");
}

$talento_id = $talento['id'];

/* =========================
   IDIOMAS
========================= */

// borrar anteriores
$conn->query("DELETE FROM idiomas_talento WHERE talento_id = $talento_id");

if (!empty($_POST['idiomas'])) {
  foreach ($_POST['idiomas'] as $idioma_id) {

    // 🚫 evitar guardar selects vacíos
    if (!empty($idioma_id)) {
      $stmt = $conn->prepare("
        INSERT INTO idiomas_talento (talento_id, idioma_id)
        VALUES (?, ?)
      ");
      $stmt->bind_param("ii", $talento_id, $idioma_id);
      $stmt->execute();
    }
  }
}

/* =========================
   HABILIDADES
========================= */

// borrar anteriores
$conn->query("DELETE FROM talento_habilidad WHERE talento_id = $talento_id");

if (!empty($_POST['habilidades'])) {
  foreach ($_POST['habilidades'] as $habilidad_id) {

    // 🚫 evitar guardar selects vacíos
    if (!empty($habilidad_id)) {
      $stmt = $conn->prepare("
        INSERT INTO talento_habilidad (talento_id, habilidad_id)
        VALUES (?, ?)
      ");
      $stmt->bind_param("ii", $talento_id, $habilidad_id);
      $stmt->execute();
    }
  }
}

/* =========================
   REDIRECCION FINAL
========================= */

header("Location: ./usuarios/miPerfil.php");
exit;
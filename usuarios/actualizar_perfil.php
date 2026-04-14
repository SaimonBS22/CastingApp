<?php
session_start();
require_once "../includes/db.php";

if (!isset($_SESSION['user_id'])) {
    die("Acceso no autorizado");
}

$user_id = $_SESSION['user_id'];

/* ===== OBTENER TALENTO_ID ===== */
$stmt = $conn->prepare("SELECT id FROM talentos WHERE usuario_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$talento = $result->fetch_assoc();

$talento_id = $talento['id'];

/* ===== DATOS ===== */
$apellido = $_POST['apellido'] ?? null;
$telefono = $_POST['telefono'] ?? null;
$fecha_nacimiento = $_POST['fecha_nacimiento'] ?: null;
$ubicacion = $_POST['ubicacion'] ?? null;
$genero = $_POST['genero'] ?? null;
$altura = $_POST['altura'] ?? null;
$peso = $_POST['peso'] ?? null;
$color_pelo = $_POST['color_pelo'] ?? null;
$color_ojos = $_POST['color_ojos'] ?? null;
$tez = $_POST['tez'] ?? null;
$talle_ropa = $_POST['talle_ropa'] ?? null;
$talle_calzado = $_POST['talle_calzado'] ?? null;
$experiencia = $_POST['experiencia'] ?? null;
$observaciones = $_POST['observaciones'] ?? null;

$habilidades = $_POST['habilidades'] ?? [];
$idiomas = $_POST['idiomas'] ?? [];

/* ===== UPDATE TALENTO ===== */
$sql = "
UPDATE talentos SET
 apellido=?, telefono=?, fecha_nacimiento=?, ubicacion=?, genero=?,
 altura=?, peso=?, color_pelo=?, color_ojos=?, tez=?,
 talle_ropa=?, talle_calzado=?, experiencia=?, observaciones=?
WHERE usuario_id=?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
 "sssssiissssissi",
 $apellido,$telefono,$fecha_nacimiento,$ubicacion,$genero,
 $altura,$peso,$color_pelo,$color_ojos,$tez,
 $talle_ropa,$talle_calzado,$experiencia,$observaciones,
 $user_id
);
$stmt->execute();


/* =========================
   HABILIDADES (CORREGIDO)
========================= */

// borrar anteriores
$conn->query("DELETE FROM talento_habilidad WHERE talento_id = $talento_id");

if (!empty($habilidades)) {
  $stmt = $conn->prepare("
    INSERT INTO talento_habilidad (talento_id, habilidad_id)
    VALUES (?, ?)
  ");
  foreach ($habilidades as $h) {
    $stmt->bind_param("ii", $talento_id, $h);
    $stmt->execute();
  }
}


/* =========================
   IDIOMAS (NUEVO)
========================= */

// borrar anteriores
$conn->query("DELETE FROM idiomas_talento WHERE talento_id = $talento_id");

if (!empty($idiomas)) {
  $stmt = $conn->prepare("
    INSERT INTO idiomas_talento (talento_id, idioma_id)
    VALUES (?, ?)
  ");
  foreach ($idiomas as $i) {
    $stmt->bind_param("ii", $talento_id, $i);
    $stmt->execute();
  }
}


header("Location: miPerfil.php?edit=success");
exit;
<?php
session_start();
require_once "../includes/db.php";

if (!isset($_SESSION['user_id'])) {
    die("Acceso no autorizado");
}

$user_id = $_SESSION['user_id'];

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

/* ===== Habilidades ===== */
$del = $conn->prepare("DELETE FROM usuario_habilidad WHERE usuario_id=?");
$del->bind_param("i",$user_id);
$del->execute();

if ($habilidades) {
  $ins = $conn->prepare(
    "INSERT INTO usuario_habilidad (usuario_id, habilidad_id) VALUES (?,?)"
  );
  foreach ($habilidades as $h) {
    $ins->bind_param("ii",$user_id,$h);
    $ins->execute();
  }
}

header("Location: miPerfil.php?edit=success");
exit;

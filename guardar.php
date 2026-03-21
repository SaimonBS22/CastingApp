<?php
session_start();
require_once "./includes/db.php";

if (!isset($_SESSION['user_id'])) {
  die("No autorizado");
}

$usuario_id = $_SESSION['user_id'];

$apellido = $_POST['apellido'] ?? null;
$fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
$telefono = $_POST['telefono'] ?? null;
$ubicacion = $_POST['ubicacion'] ?? null;
$genero = $_POST['genero'] ?? null;

/* Verificamos si ya existe el talento */
$check = $conn->prepare("SELECT id FROM talentos WHERE usuario_id = ?");
$check->bind_param("i", $usuario_id);
$check->execute();
$existe = $check->get_result()->num_rows > 0;

if ($existe) {
  $stmt = $conn->prepare("
    UPDATE talentos SET
      apellido = ?, fecha_nacimiento = ?, telefono = ?, ubicacion = ?, genero = ?
    WHERE usuario_id = ?
  ");
  $stmt->bind_param(
    "sssssi",
    $apellido,
    $fecha_nacimiento,
    $telefono,
    $ubicacion,
    $genero,
    $usuario_id
  );
} else {
  $stmt = $conn->prepare("
    INSERT INTO talentos
    (usuario_id, apellido, fecha_nacimiento, telefono, ubicacion, genero)
    VALUES (?, ?, ?, ?, ?, ?)
  ");
  $stmt->bind_param(
    "isssss",
    $usuario_id,
    $apellido,
    $fecha_nacimiento,
    $telefono,
    $ubicacion,
    $genero
  );
}

$stmt->execute();

header("Location: usuarios/postularse2.php");
exit;


<?php
session_start();
require_once "./includes/db.php";

if (!isset($_SESSION['user_id'])) {
  die("No autorizado");
}

$usuario_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
  UPDATE talentos SET
    altura = ?, peso = ?, color_pelo = ?, color_ojos = ?, tez = ?, talle_ropa = ?, talle_calzado = ?
  WHERE usuario_id = ?
");

$stmt->bind_param(
  "iissssii",
  $_POST['altura'],
  $_POST['peso'],
  $_POST['color_pelo'],
  $_POST['color_ojos'],
  $_POST['tez'],
  $_POST['talle_ropa'],
  $_POST['talle_calzado'],
  $usuario_id
);

$stmt->execute();

header("Location: usuarios/postularse3.php");
exit;

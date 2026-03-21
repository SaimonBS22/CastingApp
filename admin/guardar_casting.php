<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../includes/db.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /castingApp/index.php");
    exit;
}

$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$fecha_limite = $_POST['fecha_limite'];

$imagen = null;

/* ===== SUBIR IMAGEN ===== */

if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0){

$nombreImagen = time() . "_" . $_FILES['imagen']['name'];

move_uploaded_file(
$_FILES['imagen']['tmp_name'],
"../uploads/" . $nombreImagen
);

$imagen = $nombreImagen;

}

/* ===== GUARDAR CASTING ===== */

$stmt = $conn->prepare("
INSERT INTO castings (titulo, descripcion, fecha_limite, estado, imagen)
VALUES (?, ?, ?, 'abierto', ?)
");

$stmt->bind_param("ssss", $titulo, $descripcion, $fecha_limite, $imagen);
$stmt->execute();

header("Location: castings.php");
exit;

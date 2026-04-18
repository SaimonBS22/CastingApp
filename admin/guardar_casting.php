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

$casting_id = $conn->insert_id;

/* ===== GUARDAR PERSONAJES ===== */

if (!empty($_POST['personajes']['nombre'])) {

    $nombres = $_POST['personajes']['nombre'];
    $descripciones = $_POST['personajes']['descripcion'];
    $edad_min = $_POST['personajes']['edad_min'];
    $edad_max = $_POST['personajes']['edad_max'];
    $generos = $_POST['personajes']['genero'];

    $stmt = $conn->prepare("
        INSERT INTO personajes (casting_id, nombre, descripcion, edad_min, edad_max, genero)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    for ($i = 0; $i < count($nombres); $i++) {

        if (empty($nombres[$i])) continue;

        $stmt->bind_param(
            "ississ",
            $casting_id,
            $nombres[$i],
            $descripciones[$i],
            $edad_min[$i],
            $edad_max[$i],
            $generos[$i]
        );

        $stmt->execute();
    }
}

header("Location: castings.php");
exit;

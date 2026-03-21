<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../includes/db.php';
include 'header_admin.php';

/* ===== PROTECCION ADMIN ===== */
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /castingApp/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/castingApp/admin/css/crear_casting.css">
<title>Crear Casting</title>
</head>

<body>

<h2 class="titulo-form">Crear Casting</h2>

<div class="form-wrapper">

<form class="form-casting" action="guardar_casting.php" method="POST" enctype="multipart/form-data">

<div class="form-group">
<input type="text" name="titulo" placeholder="Título del casting" required>
</div>

<div class="form-group">
<textarea name="descripcion" placeholder="Descripción del proyecto" required></textarea>
</div>

<div class="form-group">
<label>Fecha límite</label>
<input type="date" name="fecha_limite">
</div>

<div class="form-group">
<label>Imagen del casting</label>
<input type="file" name="imagen" accept="image/*">
</div>

<button type="submit" class="btn-crear-casting">
Crear casting
</button>

</form>

</div>

</body>
</html>

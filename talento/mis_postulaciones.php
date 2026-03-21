<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: /castingApp/login.php");
  exit;
}


$usuario_id = $_SESSION['user_id'];

/* ===== TRAER POSTULACIONES ===== */

$stmt = $conn->prepare("
SELECT 
castings.titulo,
castings.imagen,
postulaciones.estado
FROM postulaciones
JOIN castings 
ON castings.id = postulaciones.casting_id
WHERE postulaciones.usuario_id = ?
ORDER BY postulaciones.fecha_postulacion DESC
");

$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<link rel="stylesheet" href="/castingApp/styles/mis_postulaciones.css">
<title>Mis postulaciones</title>


</head>

<body>
    <?php include '../includes/header.php'; ?>

<h1>Mis postulaciones</h1>

<div class="grid-postulaciones">

<?php while($p = $result->fetch_assoc()): ?>

<div class="card-postulacion">

<?php if(!empty($p['imagen'])): ?>

<img src="../uploads/<?= htmlspecialchars($p['imagen']) ?>">

<?php endif; ?>

<div class="card-body">

<h3><?= htmlspecialchars($p['titulo']) ?></h3>

<br>

<span class="estado <?= strtolower($p['estado']) ?>">
<?= strtoupper($p['estado']) ?>
</span>

</div>

</div>

<?php endwhile; ?>

</div>

<?php 
  include "../includes/footer.php";
?>

</body>
</html>

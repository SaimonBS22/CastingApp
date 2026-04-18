<?php
include '../includes/header.php'; 
require_once("../includes/db.php");
require_once("../includes/auth.php");

$user_id = $_SESSION['user_id'];

if (!isset($_SESSION['user_id'])) {
    header("Location: /castingApp/login.php");
    exit;
}

$castings = $conn->query("
SELECT * FROM castings
WHERE estado='abierto'
AND (fecha_limite IS NULL OR fecha_limite >= CURDATE())
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="/castingApp/styles/casting.css">

<title>Castings</title>
</head>

<body>

<div class="castings-container">

<h1 class="titulo-castings">Castings disponibles</h1>

<?php while($c = $castings->fetch_assoc()): ?>

<div class="casting-card">

    <?php if(!empty($c['imagen'])): ?>
        <img src="../uploads/<?= htmlspecialchars($c['imagen']) ?>" class="casting-img">
    <?php endif; ?>

    <div class="casting-info">

        <h2 class="casting-titulo">
            <?= htmlspecialchars($c['titulo']) ?>
        </h2>

        <p class="casting-desc">
            <?= nl2br(htmlspecialchars($c['descripcion'])) ?>
        </p>

    </div>

    <div class="casting-action">

        <!-- BOTÓN NUEVO -->
        <a href="ver_casting.php?id=<?= $c['id'] ?>" class="btn-postular">
            Ver detalles
        </a>

    </div>

</div>

<?php endwhile; ?>

<a href="mis_postulaciones.php" class="btn-mis-postulaciones">
    Mis postulaciones
</a>

</div>

<?php include "../includes/footer.php"; ?>

</body>
</html>
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

<?php
/* verificar si ya postuló */
$stmt = $conn->prepare("
SELECT id FROM postulaciones
WHERE casting_id=? AND usuario_id=?
");
$stmt->bind_param("ii",$c['id'],$user_id);
$stmt->execute();
$ya = $stmt->get_result()->fetch_assoc();
?>

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

    <?php if($ya): ?>

        <span class="postulado-badge">
            ✔ Ya estás postulado
        </span>

    <?php else: ?>

        <form action="postular.php" method="POST">

            <input type="hidden" name="casting_id" value="<?= $c['id'] ?>">

            <button type="submit" class="btn-postular">
                Postularme
            </button>

        </form>

    <?php endif; ?>

    </div>


</div>

<?php endwhile; ?>

    <a href="mis_postulaciones.php" class="btn-mis-postulaciones">
 Mis postulaciones
</a>

</div>

<?php 
  include "../includes/footer.php";
?>

</body>
</html>

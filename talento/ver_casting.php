<?php
session_start();
include '../includes/header.php';
require_once("../includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: /castingApp/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    die("Casting inválido");
}

$casting_id = intval($_GET['id']);

/* ===== TRAER CASTING ===== */
$stmt = $conn->prepare("SELECT * FROM castings WHERE id=?");
$stmt->bind_param("i", $casting_id);
$stmt->execute();
$casting = $stmt->get_result()->fetch_assoc();

if (!$casting) {
    die("Casting no encontrado");
}

/* ===== TRAER PERSONAJES ===== */
$personajes = $conn->query("
    SELECT * FROM personajes WHERE casting_id = $casting_id
");

/* ===== VER SI YA POSTULÓ ===== */
$stmt = $conn->prepare("
SELECT personaje_id FROM postulaciones
WHERE usuario_id=? AND casting_id=?
");
$stmt->bind_param("ii", $user_id, $casting_id);
$stmt->execute();
$postulaciones = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$postulados = array_column($postulaciones, 'personaje_id');
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($casting['titulo']) ?></title>
<link rel="stylesheet" href="../styles/ver_castings.css">
</head>
<body>

<h1><?= htmlspecialchars($casting['titulo']) ?></h1>

<p><?= nl2br(htmlspecialchars($casting['descripcion'])) ?></p>

<hr>

<h2>Personajes</h2>

<?php if ($personajes->num_rows > 0): ?>

<?php while($p = $personajes->fetch_assoc()): ?>

<div class="personaje-card">

    <h3><?= htmlspecialchars($p['nombre']) ?></h3>

    <p><?= htmlspecialchars($p['descripcion']) ?></p>

    <p>
        Edad: <?= $p['edad_min'] ?> - <?= $p['edad_max'] ?>
    </p>

    <p>
        Género: <?= $p['genero'] ?>
    </p>

    <?php if (in_array($p['id'], $postulados)): ?>

        <span class="postulado-badge">
            ✔ Ya postulado a este personaje
        </span>

    <?php else: ?>

        <form action="postular.php" method="POST">

            <input type="hidden" name="casting_id" value="<?= $casting_id ?>">
            <input type="hidden" name="personaje_id" value="<?= $p['id'] ?>">

            <button type="submit" class="btn-postular">
                Postularme a este personaje
            </button>

        </form>

    <?php endif; ?>

</div>

<hr>

<?php endwhile; ?>

<?php else: ?>

<p>No hay personajes disponibles.</p>

<?php endif; ?>


<?php
include '../includes/footer.php';
?>

</body>
</html>
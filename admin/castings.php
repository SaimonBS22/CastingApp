<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../includes/db.php';
include 'header_admin.php';

/* ===== PROTECCION ADMIN ===== */
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /castingApp/index.php");
    exit;
}

$res = $conn->query("
SELECT 
castings.*,
COUNT(postulaciones.id) AS total_postulados
FROM castings
LEFT JOIN postulaciones 
ON postulaciones.casting_id = castings.id
GROUP BY castings.id
ORDER BY castings.id DESC
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/castingApp/admin/css/castings.css">
    <title>Document</title>
</head>
<body>
    

<h1 class="titulo-castings">Castings</h1>

<div class="header-castings">
    <p class="subtitulo-castings">
        Gestioná tus proyectos y revisá los talentos postulados.
    </p>

    <a href="crear_casting.php" class="btn-crear">
        + Crear casting
    </a>
</div>

<div class="grid-castings">
<?php while($c = $res->fetch_assoc()): ?>

   <div class="card-casting">

        <?php if(!empty($c['imagen'])): ?>

        <img 
        src="../uploads/<?= htmlspecialchars($c['imagen']) ?>" 
        class="casting-img"
        >

        <?php endif; ?>

     <div class="card-header">

            <h3><?= htmlspecialchars($c['titulo']) ?></h3>

            <span class="estado-casting <?= $c['estado'] ?>">
                <?= strtoupper($c['estado']) ?>
            </span>

            

     </div>


        <div class="card-body">

            <p>
            <?= nl2br(htmlspecialchars($c['descripcion'])) ?>
            </p>

            <div class="postulados-info">
            <?= $c['total_postulados'] ?> postulados
        </div>

</div>


   <div class="card-footer">

        <?php if($c['estado'] == 'cerrado'): ?>

        <span class="casting-cerrado">
        Casting cerrado
        </span>

        <?php else: ?>

        <a href="postulados.php?casting_id=<?= $c['id'] ?>" class="btn-detalle">
        Ver postulados →
        </a>

        <?php endif; ?>


        <form action="cambiar_estado_casting.php" method="POST" class="form-estado">

        <input type="hidden" name="casting_id" value="<?= $c['id'] ?>">

        <?php if($c['estado'] == 'abierto'): ?>

        <input type="hidden" name="estado" value="cerrado">

        <button class="btn-cerrar">
        Cerrar casting
        </button>

        <?php else: ?>

        <input type="hidden" name="estado" value="abierto">

        <button class="btn-abrir">
        Abrir casting
        </button>

        <?php endif; ?>

        </form>

   </div>

    </div>

<?php endwhile; ?>
</div>

</body>
</html>
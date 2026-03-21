<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../includes/db.php';
include 'header_admin.php';

/* ===== PROTECCION ADMIN ===== */
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /castingApp/index.php");
    exit;
}

$casting_id = intval($_GET['casting_id']);

/* OBTENER INFO DEL CASTING */
$stmtCasting = $conn->prepare("SELECT titulo FROM castings WHERE id=?");
$stmtCasting->bind_param("i", $casting_id);
$stmtCasting->execute();
$resCasting = $stmtCasting->get_result();
$casting = $resCasting->fetch_assoc();

if (!$casting) {
    die("Casting no encontrado");
}

/* OBTENER POSTULADOS */
$stmt = $conn->prepare("
SELECT 
    usuarios.id AS usuario_id,
    usuarios.nombre,
    usuarios.email,
    postulaciones.estado,
    postulaciones.fecha_postulacion
FROM postulaciones
JOIN usuarios ON usuarios.id = postulaciones.usuario_id
WHERE postulaciones.casting_id = ?
ORDER BY postulaciones.fecha_postulacion DESC
");

$stmt->bind_param("i", $casting_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Postulados</title>

<style>
body{
    font-family: Arial;
    background:#f4f6f8;
    padding:30px;
}

h1{
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
}

th, td{
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:left;
}

th{
    background:#222;
    color:white;
}

tr:hover{
    background:#f1f1f1;
}

.btn{
    padding:8px 12px;
    border:none;
    background:#007bff;
    color:white;
    cursor:pointer;
    border-radius:6px;
    text-decoration:none;
    font-size:14px;
}

.btn:hover{
    background:#0056b3;
}

select{
    padding:6px 10px;
    border-radius:6px;
    border:1px solid #ccc;
    font-size:13px;
    cursor:pointer;
}

.volver{
    margin-bottom:20px;
    display:inline-block;
}
</style>

</head>
<body>

<a class="btn volver" href="castings.php">← Volver a castings</a>

<h1>Postulados: <?= htmlspecialchars($casting['titulo']) ?></h1>

<?php if($result->num_rows == 0): ?>

<p>No hay postulados todavía.</p>

<?php else: ?>

<table>

<tr>
<th>Nombre</th>
<th>Email</th>
<th>Fecha</th>
<th>Estado</th>
<th>Perfil</th>
</tr>

<?php while($u = $result->fetch_assoc()): ?>

<tr>

<td><?= htmlspecialchars($u['nombre']) ?></td>

<td><?= htmlspecialchars($u['email']) ?></td>

<td><?= $u['fecha_postulacion'] ?></td>

<td>

<form method="POST" action="cambiar_estado.php">

<input type="hidden" name="casting_id" value="<?= $casting_id ?>">
<input type="hidden" name="usuario_id" value="<?= $u['usuario_id'] ?>">

<select name="estado" onchange="this.form.submit()">

<option value="Pendiente" <?= $u['estado']=='pendiente'?'selected':'' ?>>Pendiente</option>

<option value="Aprobado" <?= $u['estado']=='aprobado'?'selected':'' ?>>Aprobado</option>

<option value="Rechazado" <?= $u['estado']=='rechazado'?'selected':'' ?>>Rechazado</option>

</select>

</form>

</td>

<td>
<a class="btn" href="ver_talento.php?usuario_id=<?= $u['usuario_id'] ?>">
Ver talento
</a>
</td>

</tr>

<?php endwhile; ?>

</table>

<?php endif; ?>

</body>
</html>
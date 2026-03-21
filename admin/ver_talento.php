<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../includes/db.php';
include 'header_admin.php';

/* ===== PROTECCION ADMIN ===== */
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /castingApp/index.php");
    exit;
}

/* ===== OBTENER ID DESDE URL ===== */
if (!isset($_GET['usuario_id']) || !is_numeric($_GET['usuario_id'])) {
    die("Usuario inválido");
}

$usuario_id = intval($_GET['usuario_id']);


/* ===== TRAER DATOS DEL TALENTO ===== */
$stmt = $conn->prepare("
SELECT talentos.*, usuarios.nombre, usuarios.email
FROM talentos
JOIN usuarios ON usuarios.id = talentos.usuario_id
WHERE talentos.usuario_id = ?
");

$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$result = $stmt->get_result();
$talento = $result->fetch_assoc();

if (!$talento) {
    die("Talento no encontrado");
}


/* ===== TRAER FOTO DEL TALENTO ===== */
$stmt_media = $conn->prepare("
SELECT archivo
FROM talento_media
WHERE usuario_id = ?
LIMIT 1
");

$stmt_media->bind_param("i", $usuario_id);
$stmt_media->execute();

$result_media = $stmt_media->get_result();
$media = $result_media->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/castingApp/admin/css/ver_talento.css">
    <title>Ver Talentos</title>
</head>
<body>
    

<div class="perfil-wrapper">

<div class="perfil-card">

<h1 class="titulo">Perfil del talento</h1>

<div class="foto-talento">

<?php if($media && !empty($media['archivo'])): ?>

<img src="../uploads/fotos/<?= htmlspecialchars($media['archivo']) ?>" alt="Foto del talento">

<?php else: ?>

<img src="../assets/no-user.png" alt="Sin foto">

<?php endif; ?>

</div>

<div class="perfil-header">
<h2><?= htmlspecialchars($talento['nombre']) ?></h2>
<p><?= htmlspecialchars($talento['email']) ?></p>
</div>

<div class="perfil-section">

<h3>Datos físicos</h3>

<div class="datos-grid">

<div class="dato">
<span>Color de ojos</span>
<strong><?= $talento['color_ojos'] ?? '-' ?></strong>
</div>

<div class="dato">
<span>Color de pelo</span>
<strong><?= $talento['color_pelo'] ?? '-' ?></strong>
</div>

<div class="dato">
<span>Tez</span>
<strong><?= $talento['tez'] ?? '-' ?></strong>
</div>

<div class="dato">
<span>Altura</span>
<strong><?= $talento['altura'] ?? '-' ?> cm</strong>
</div>

<div class="dato">
<span>Peso</span>
<strong><?= $talento['peso'] ?? '-' ?> kg</strong>
</div>

<div class="dato">
<span>Talle ropa</span>
<strong><?= $talento['talle_ropa'] ?? '-' ?></strong>
</div>

<div class="dato">
<span>Talle calzado</span>
<strong><?= $talento['talle_calzado'] ?? '-' ?></strong>
</div>

</div>

</div>

<a class="btn-volver" href="javascript:history.back()">← Volver</a>

</div>

</div>

</body>
</html>
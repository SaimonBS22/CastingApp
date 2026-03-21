<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: /castingApp/login.php");
  exit;
}

$usuario_id = $_SESSION['user_id'];

/* ===== CHEQUEAR SI EXISTE PERFIL ===== */
$sql = "SELECT * FROM talentos WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$perfilExiste = $result->num_rows > 0;

/* ===== SI EXISTE PERFIL, CARGAR DATOS ===== */
if ($perfilExiste) {

$stmt = $conn->prepare("
  SELECT 
    u.nombre,
    u.email,
    t.apellido,
    t.fecha_nacimiento,
    t.telefono,
    t.ubicacion,
    t.genero,
    t.altura,
    t.peso,
    t.color_pelo,
    t.color_ojos,
    t.tez,
    t.talle_ropa,
    t.talle_calzado,
    t.experiencia,
    t.observaciones
  FROM usuarios u
  LEFT JOIN talentos t ON u.id = t.usuario_id
  WHERE u.id = ?
");

$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$perfil = $stmt->get_result()->fetch_assoc();

$talento = $perfil;

/* ===== HABILIDADES ===== */
$hab = $conn->query("
  SELECT h.nombre
  FROM habilidades h
  JOIN usuario_habilidad uh ON h.id = uh.habilidad_id
  WHERE uh.usuario_id = $usuario_id
");

/* ===== MEDIA ===== */
$mediaStmt = $conn->prepare("
  SELECT * FROM talento_media
  WHERE usuario_id = ?
  ORDER BY created_at DESC
");

$mediaStmt->bind_param("i", $usuario_id);
$mediaStmt->execute();
$media = $mediaStmt->get_result();

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mi perfil</title>

<link rel="stylesheet" href="/castingApp/styles/header.css">
<link rel="stylesheet" href="/castingApp/styles/miPerfil.css">

</head>
<body>

<?php include '../includes/header.php'; ?>

<main class="main-content page-wrapper">

<h2 class="perfil-title">Mi perfil</h2>

<?php if (!$perfilExiste): ?>

<!-- ================= CREAR PERFIL ================= -->

<div class="crear-perfil-box">

<p>Aún no creaste tu perfil de talento.</p>

<a href="postularse.php" class="btn-primary">
Crear mi perfil
</a>

</div>

<?php else: ?>

<!-- ================= MEDIA ================= -->

<h3 class="perfil-media-title">📸 Fotos y videos</h3>

<div class="media perfil-media">

<?php if ($media->num_rows > 0): ?>

<?php while ($m = $media->fetch_assoc()): ?>

<?php if ($m['tipo'] === 'foto'): ?>

<img
class="perfil-img"
src="/castingApp/uploads/fotos/<?= htmlspecialchars($m['archivo']) ?>"
alt="Foto del perfil"
>

<?php elseif ($m['tipo'] === 'video'): ?>

<video class="perfil-video" controls>
<source
src="/castingApp/uploads/videos/<?= htmlspecialchars($m['archivo']) ?>"
type="video/mp4"
>
</video>

<?php elseif ($m['tipo'] === 'link'): ?>

<p class="perfil-link">
🎬 <a href="<?= htmlspecialchars($m['url']) ?>" target="_blank">
Ver video
</a>
</p>

<?php endif; ?>

<?php endwhile; ?>

<?php else: ?>

<p class="perfil-empty">No subiste fotos ni videos todavía.</p>

<?php endif; ?>

</div>

<hr>

<!-- ================= DATOS PERSONALES ================= -->

<h3 class="perfil-subtitle">👤 Datos personales</h3>

<p class="perfil-item"><b>Nombre:</b> <?= htmlspecialchars($perfil['nombre']) ?></p>
<p class="perfil-item"><b>Apellido:</b> <?= htmlspecialchars($talento['apellido'] ?? '') ?></p>
<p class="perfil-item"><b>Email:</b> <?= htmlspecialchars($perfil['email']) ?></p>
<p class="perfil-item"><b>Fecha de nacimiento:</b> <?= htmlspecialchars($talento['fecha_nacimiento'] ?? '') ?></p>
<p class="perfil-item"><b>Teléfono:</b> <?= htmlspecialchars($talento['telefono'] ?? '') ?></p>
<p class="perfil-item"><b>Ubicación:</b> <?= htmlspecialchars($talento['ubicacion'] ?? '') ?></p>
<p class="perfil-item"><b>Género:</b> <?= !empty($talento['genero']) ? ucfirst($talento['genero']) : '' ?></p>

<hr>

<!-- ================= DATOS FÍSICOS ================= -->

<h3 class="perfil-subtitle">📏 Datos físicos</h3>

<p class="perfil-item"><b>Altura:</b> <?= htmlspecialchars($talento['altura'] ?? '') ?> cm</p>
<p class="perfil-item"><b>Peso:</b> <?= htmlspecialchars($talento['peso'] ?? '') ?> kg</p>
<p class="perfil-item"><b>Color de pelo:</b> <?= htmlspecialchars($talento['color_pelo'] ?? '') ?></p>
<p class="perfil-item"><b>Color de ojos:</b> <?= htmlspecialchars($talento['color_ojos'] ?? '') ?></p>
<p class="perfil-item"><b>Tez:</b> <?= htmlspecialchars($talento['tez'] ?? '') ?></p>
<p class="perfil-item"><b>Talle de ropa:</b> <?= htmlspecialchars($talento['talle_ropa'] ?? '') ?></p>
<p class="perfil-item"><b>Talle de calzado:</b> <?= htmlspecialchars($talento['talle_calzado'] ?? '') ?></p>

<hr>

<!-- ================= EXPERIENCIA ================= -->

<h3 class="perfil-subtitle">🎭 Experiencia</h3>

<p class="perfil-item"><b>Nivel:</b> <?= htmlspecialchars($talento['experiencia'] ?? '') ?></p>

<?php if (!empty($talento['observaciones'])): ?>

<p class="perfil-item">
<b>Observaciones:</b><br>
<?= nl2br(htmlspecialchars($talento['observaciones'])) ?>
</p>

<?php endif; ?>

<hr>

<!-- ================= HABILIDADES ================= -->

<h3 class="perfil-subtitle">⭐ Habilidades</h3>

<?php if ($hab->num_rows > 0): ?>

<ul class="perfil-list">

<?php while ($h = $hab->fetch_assoc()): ?>

<li class="perfil-list-item"><?= htmlspecialchars($h['nombre']) ?></li>

<?php endwhile; ?>

</ul>

<?php else: ?>

<p class="perfil-empty">No cargaste habilidades todavía.</p>

<?php endif; ?>

<br>

<div class="contenedor-botones">

<a href="editar_perfil.php" class="btn-primary">
Editar perfil
</a>

<a href="/castingApp/logout.php" class="btn-logout">
Cerrar sesión
</a>

</div>

<?php endif; ?>

</main>

<?php include '../includes/footer.php'; ?>

</body>
</html>
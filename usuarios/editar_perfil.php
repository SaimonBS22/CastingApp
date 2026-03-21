<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

/* ===== Validar sesión ===== */
if (!isset($_SESSION['user_id'])) {
  header("Location: /castingApp/login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

/* ===== Usuario ===== */
$stmtUser = $conn->prepare("SELECT nombre FROM usuarios WHERE id = ?");
$stmtUser->bind_param("i", $user_id);
$stmtUser->execute();
$usuario = $stmtUser->get_result()->fetch_assoc();

/* ===== Talento ===== */
$stmt = $conn->prepare("SELECT * FROM talentos WHERE usuario_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$talento = $stmt->get_result()->fetch_assoc();

if (!$talento) {
  die("Perfil no encontrado");
}

/* ===== Habilidades del usuario ===== */
$habStmt = $conn->prepare("
  SELECT habilidad_id 
  FROM usuario_habilidad 
  WHERE usuario_id = ?
");
$habStmt->bind_param("i", $user_id);
$habStmt->execute();
$habilidades_usuario = array_column(
  $habStmt->get_result()->fetch_all(MYSQLI_ASSOC),
  'habilidad_id'
);

/* ===== Todas las habilidades ===== */
$allHab = $conn->query("SELECT id, nombre FROM habilidades")
               ->fetch_all(MYSQLI_ASSOC);

/* ===== Media ===== */
$mediaStmt = $conn->prepare("
  SELECT * FROM talento_media
  WHERE usuario_id = ?
  ORDER BY created_at DESC
");
$mediaStmt->bind_param("i", $user_id);
$mediaStmt->execute();
$media = $mediaStmt->get_result();

/* ===== Opciones ===== */
$colores_pelo = ['Negro','Castaño','Rubio','Pelirrojo','Canoso'];
$colores_ojos = ['Marrones','Negros','Verdes','Azules','Celestes'];
$teces = ['Muy clara','Clara','Trigueña','Morena','Oscura'];
$talles_ropa = ['XS','S','M','L','XL','XXL'];
$talles_calzado = range(34, 46);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar perfil</title>
<link rel="stylesheet" href="/castingApp/styles/editarPerfil.css">
</head>
<body>

<h2 class="edit-title">Editar perfil</h2>
<h3 class="edit-greeting">Hola <?= htmlspecialchars($usuario['nombre']) ?> 👋</h3>

<!-- ================= MEDIA ================= -->

<h3 class="form-section-title">Fotos, videos y links</h3>

<div class="media-grid">
<?php while ($m = $media->fetch_assoc()): ?>
  <div class="media-box">

    <?php if ($m['tipo'] === 'foto'): ?>
      <img class="imagen" src="../uploads/fotos/<?= htmlspecialchars($m['archivo']) ?>">

    <?php elseif ($m['tipo'] === 'video'): ?>
      <video class="video" controls>
        <source src="../uploads/videos/<?= htmlspecialchars($m['archivo']) ?>">
      </video>

    <?php elseif ($m['tipo'] === 'link'): ?>
      <iframe
        src="<?= str_replace("watch?v=", "embed/", htmlspecialchars($m['url'])) ?>"
        allowfullscreen></iframe>
    <?php endif; ?>

    <form method="POST" action="eliminar_media.php">
      <input type="hidden" name="media_id" value="<?= $m['id'] ?>">
      <button class="btn-danger" type="submit">Eliminar</button>
    </form>

  </div>
<?php endwhile; ?>
</div>

<form class="edit-form" method="POST" action="guardar_media.php" enctype="multipart/form-data">

  <div class="form-group">
    <label>Subir fotos</label>
    <input type="file" name="fotos[]" multiple accept="image/*">
  </div>

  <div class="form-group">
    <label>Subir videos</label>
    <input type="file" name="videos[]" multiple accept="video/*">
  </div>

  <div class="form-group">
    <label>Links</label>
    <input type="url" name="links[]" placeholder="https://youtube.com/...">
    <input type="url" name="links[]" placeholder="https://youtube.com/...">
  </div>

  <button class="btn-primary" type="submit">Guardar contenido</button>
</form>

<hr>

<!-- ================= DATOS PERFIL ================= -->

<form class="edit-form" method="POST" action="/castingApp/usuarios/actualizar_perfil.php">

<h3 class="form-section-title">Datos personales</h3>

<div class="form-group">
  <label>Apellido</label>
  <input type="text" name="apellido" value="<?= htmlspecialchars($talento['apellido']) ?>">
</div>

<div class="form-group">
  <label>Fecha de nacimiento</label>
  <input type="date" name="fecha_nacimiento" value="<?= $talento['fecha_nacimiento'] ?>">
</div>

<div class="form-group">
  <label>Teléfono</label>
  <input type="text" name="telefono" value="<?= htmlspecialchars($talento['telefono']) ?>">
</div>

<div class="form-group">
  <label>Ubicación</label>
  <input type="text" name="ubicacion" value="<?= htmlspecialchars($talento['ubicacion']) ?>">
</div>

<div class="form-group">
  <label>Género</label>
  <select name="genero">
    <option value="">Seleccionar</option>
    <?php foreach (['masculino','femenino','otro'] as $g): ?>
      <option value="<?= $g ?>" <?= $talento['genero']===$g?'selected':'' ?>>
        <?= ucfirst($g) ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>

<h3 class="form-section-title">Datos físicos</h3>

<div class="form-group">
  <label>Altura</label>
  <input type="number" name="altura" value="<?= $talento['altura'] ?>">
</div>

<div class="form-group">
  <label>Peso</label>
  <input type="number" name="peso" value="<?= $talento['peso'] ?>">
</div>

<div class="form-group">
  <label>Color de pelo</label>
  <select name="color_pelo">
    <option value="">Seleccionar</option>
    <?php foreach ($colores_pelo as $c): ?>
      <option value="<?= $c ?>" <?= $talento['color_pelo']===$c?'selected':'' ?>>
        <?= $c ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>

<div class="form-group">
  <label>Color de ojos</label>
  <select name="color_ojos">
    <option value="">Seleccionar</option>
    <?php foreach ($colores_ojos as $c): ?>
      <option value="<?= $c ?>" <?= $talento['color_ojos']===$c?'selected':'' ?>>
        <?= $c ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>

<div class="form-group">
  <label>Tez</label>
  <select name="tez">
    <option value="">Seleccionar</option>
    <?php foreach ($teces as $t): ?>
      <option value="<?= $t ?>" <?= $talento['tez']===$t?'selected':'' ?>>
        <?= $t ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>

<div class="form-group">
  <label>Talle ropa</label>
  <select name="talle_ropa">
    <option value="">Seleccionar</option>
    <?php foreach ($talles_ropa as $t): ?>
      <option value="<?= $t ?>" <?= $talento['talle_ropa']===$t?'selected':'' ?>>
        <?= $t ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>

<div class="form-group">
  <label>Talle calzado</label>
  <select name="talle_calzado">
    <option value="">Seleccionar</option>
    <?php foreach ($talles_calzado as $t): ?>
      <option value="<?= $t ?>" <?= $talento['talle_calzado']==$t?'selected':'' ?>>
        <?= $t ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>

<h3 class="form-section-title">Experiencia</h3>

<select name="experiencia">
  <option value="">Seleccionar</option>
  <?php foreach (['Ninguna','Amateur','Profesional'] as $e): ?>
    <option value="<?= $e ?>" <?= $talento['experiencia']===$e?'selected':'' ?>>
      <?= $e ?>
    </option>
  <?php endforeach; ?>
</select>

<div class="form-group">
  <label>Observaciones</label>
  <textarea name="observaciones"><?= htmlspecialchars($talento['observaciones']) ?></textarea>
</div>

<h3 class="form-section-title">Habilidades</h3>

<div class="checks">
<?php foreach ($allHab as $h): ?>
  <label class="check-item">
    <input type="checkbox" name="habilidades[]" value="<?= $h['id'] ?>"
      <?= in_array($h['id'], $habilidades_usuario) ? 'checked' : '' ?>>
    <?= htmlspecialchars($h['nombre']) ?>
  </label>
<?php endforeach; ?>
</div>

<button class="btn-primary" type="submit">Guardar cambios</button>
</form>

<?php include '../includes/footer.php'; ?>

</body>
</html>

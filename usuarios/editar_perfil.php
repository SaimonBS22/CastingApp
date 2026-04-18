<?php
session_start();
include '../includes/db.php';

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

$talento_id = $talento['id'];

/* ===== HABILIDADES ===== */
$habStmt = $conn->prepare("SELECT habilidad_id FROM talento_habilidad WHERE talento_id = ?");
$habStmt->bind_param("i", $talento_id);
$habStmt->execute();
$habilidades_usuario = array_column(
  $habStmt->get_result()->fetch_all(MYSQLI_ASSOC),
  'habilidad_id'
);

$allHab = $conn->query("SELECT id, nombre FROM habilidades")
               ->fetch_all(MYSQLI_ASSOC);

/* ===== IDIOMAS ===== */
$idiomaStmt = $conn->prepare("SELECT idioma_id FROM idiomas_talento WHERE talento_id = ?");
$idiomaStmt->bind_param("i", $talento_id);
$idiomaStmt->execute();
$idiomas_usuario = array_column(
  $idiomaStmt->get_result()->fetch_all(MYSQLI_ASSOC),
  'idioma_id'
);

$allIdiomas = $conn->query("SELECT id, nombre FROM idiomas")
                  ->fetch_all(MYSQLI_ASSOC);

/* ===== MEDIA ===== */
$mediaStmt = $conn->prepare("
  SELECT * FROM talento_media
  WHERE usuario_id = ?
  ORDER BY created_at DESC
");
$mediaStmt->bind_param("i", $user_id);
$mediaStmt->execute();
$media = $mediaStmt->get_result();

/* ===== LISTAS DE UBICACION ===== */
$provincias = ["Buenos Aires", "CABA"];

$localidades = [
  "Buenos Aires" => ["San Isidro", "Vicente López", "Tigre"],
  "CABA" => ["Palermo", "Recoleta", "Saavedra"]
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar perfil</title>
<link rel="stylesheet" href="/castingApp/styles/editarPerfil.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<h2>Editar perfil</h2>
<h3>Hola <?= htmlspecialchars($usuario['nombre']) ?> 👋</h3>

<!-- ================= MEDIA ================= -->

<h3>Fotos, videos y links</h3>

<div>
<?php while ($m = $media->fetch_assoc()): ?>

  <?php if ($m['tipo'] === 'foto'): ?>
    <img src="../uploads/fotos/<?= $m['archivo'] ?>" width="120">
  <?php elseif ($m['tipo'] === 'video'): ?>
    <video width="150" controls>
      <source src="../uploads/videos/<?= $m['archivo'] ?>">
    </video>
  <?php elseif ($m['tipo'] === 'link'): ?>
    <a href="<?= $m['url'] ?>" target="_blank">Ver video</a>
  <?php endif; ?>

  <form method="POST" action="eliminar_media.php">
    <input type="hidden" name="media_id" value="<?= $m['id'] ?>">
    <button type="submit">Eliminar</button>
  </form>

<?php endwhile; ?>
</div>

<form method="POST" action="guardar_media.php" enctype="multipart/form-data">
  <input type="file" name="fotos[]" multiple><br><br>
  <input type="file" name="videos[]" multiple><br><br>

  <input type="url" name="links[]" placeholder="Link video"><br>
  <input type="url" name="links[]" placeholder="Link video"><br><br>

  <button type="submit">Guardar contenido</button>
</form>

<hr>

<!-- ================= FORM PERFIL ================= -->

<form method="POST" action="/castingApp/usuarios/actualizar_perfil.php">

<h3>Datos personales</h3>

<input type="text" name="apellido" value="<?= $talento['apellido'] ?>"><br>
<input type="date" name="fecha_nacimiento" value="<?= $talento['fecha_nacimiento'] ?>"><br>
<input type="text" name="telefono" value="<?= $talento['telefono'] ?>"><br>

<!-- 🔥 NUEVA UBICACION -->

<select name="provincia" id="provincia" onchange="cargarLocalidades()">
  <option value="">Provincia</option>
  <?php foreach ($provincias as $p): ?>
    <option value="<?= $p ?>" <?= $talento['provincia']==$p?'selected':'' ?>>
      <?= $p ?>
    </option>
  <?php endforeach; ?>
</select><br><br>

<select name="localidad" id="localidad">
  <option value="">Localidad</option>
</select><br><br>

<select name="genero">
  <option value="">Género</option>
  <option value="masculino" <?= $talento['genero']=='masculino'?'selected':'' ?>>Masculino</option>
  <option value="femenino" <?= $talento['genero']=='femenino'?'selected':'' ?>>Femenino</option>
  <option value="otro" <?= $talento['genero']=='otro'?'selected':'' ?>>Otro</option>
</select>

<hr>

<h3>Datos físicos</h3>

<input type="number" name="altura" value="<?= $talento['altura'] ?>"><br>
<input type="number" name="peso" value="<?= $talento['peso'] ?>"><br>

<input type="text" name="color_pelo" value="<?= $talento['color_pelo'] ?>"><br>
<input type="text" name="color_ojos" value="<?= $talento['color_ojos'] ?>"><br>
<input type="text" name="tez" value="<?= $talento['tez'] ?>"><br>

<input type="text" name="talle_ropa" value="<?= $talento['talle_ropa'] ?>"><br>
<input type="text" name="talle_calzado" value="<?= $talento['talle_calzado'] ?>"><br>

<hr>

<h3>Experiencia</h3>

<select name="experiencia">
  <option value="">Seleccionar</option>
  <option value="Ninguna" <?= $talento['experiencia']=='Ninguna'?'selected':'' ?>>Ninguna</option>
  <option value="Amateur" <?= $talento['experiencia']=='Amateur'?'selected':'' ?>>Amateur</option>
  <option value="Profesional" <?= $talento['experiencia']=='Profesional'?'selected':'' ?>>Profesional</option>
</select>

<br><br>

<textarea name="observaciones"><?= $talento['observaciones'] ?></textarea>

<hr>

<h3>Habilidades</h3>
<div id="contenedor-habilidades">
<?php foreach ($habilidades_usuario as $habId): ?>
<select name="habilidades[]">
  <?php foreach ($allHab as $h): ?>
    <option value="<?= $h['id'] ?>" <?= $h['id']==$habId?'selected':'' ?>>
      <?= $h['nombre'] ?>
    </option>
  <?php endforeach; ?>
</select>
<?php endforeach; ?>
</div>
<button type="button" onclick="agregarHabilidad()">➕</button>

<hr>

<h3>Idiomas</h3>
<div id="contenedor-idiomas">
<?php foreach ($idiomas_usuario as $idiomaId): ?>
<select name="idiomas[]">
  <?php foreach ($allIdiomas as $i): ?>
    <option value="<?= $i['id'] ?>" <?= $i['id']==$idiomaId?'selected':'' ?>>
      <?= $i['nombre'] ?>
    </option>
  <?php endforeach; ?>
</select>
<?php endforeach; ?>
</div>
<button type="button" onclick="agregarIdioma()">➕</button>

<br><br>

<button type="submit">Guardar cambios</button>
</form>

<script>
const habilidades = <?= json_encode($allHab) ?>;
const idiomas = <?= json_encode($allIdiomas) ?>;
const localidades = <?= json_encode($localidades) ?>;

function cargarLocalidades() {
  const provincia = document.getElementById("provincia").value;
  const select = document.getElementById("localidad");

  select.innerHTML = '<option value="">Localidad</option>';

  if (localidades[provincia]) {
    localidades[provincia].forEach(loc => {
      let option = document.createElement("option");
      option.value = loc;
      option.text = loc;
      select.appendChild(option);
    });
  }
}

// cargar al iniciar (para edición)
window.onload = function() {
  cargarLocalidades();
  document.getElementById("localidad").value = "<?= $talento['localidad'] ?>";
};
</script>

<?php include '../includes/footer.php'; ?>

</body>
</html>
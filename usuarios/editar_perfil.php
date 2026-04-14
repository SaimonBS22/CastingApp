<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

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

/* ===== HABILIDADES DEL USUARIO ===== */
$habStmt = $conn->prepare("
  SELECT habilidad_id 
  FROM talento_habilidad 
  WHERE talento_id = ?
");
$habStmt->bind_param("i", $talento_id);
$habStmt->execute();
$habilidades_usuario = array_column(
  $habStmt->get_result()->fetch_all(MYSQLI_ASSOC),
  'habilidad_id'
);

/* ===== TODAS LAS HABILIDADES ===== */
$allHab = $conn->query("SELECT id, nombre FROM habilidades")
               ->fetch_all(MYSQLI_ASSOC);

/* ===== IDIOMAS DEL USUARIO ===== */
$idiomaStmt = $conn->prepare("
  SELECT idioma_id 
  FROM idiomas_talento 
  WHERE talento_id = ?
");
$idiomaStmt->bind_param("i", $talento_id);
$idiomaStmt->execute();
$idiomas_usuario = array_column(
  $idiomaStmt->get_result()->fetch_all(MYSQLI_ASSOC),
  'idioma_id'
);

/* ===== TODOS LOS IDIOMAS ===== */
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

<hr>

<form class="edit-form" method="POST" action="/castingApp/usuarios/actualizar_perfil.php">

<!-- ================= HABILIDADES ================= -->

<h3 class="form-section-title">Habilidades</h3>

<div id="contenedor-habilidades">
<?php if (!empty($habilidades_usuario)): ?>
  <?php foreach ($habilidades_usuario as $habId): ?>
    <select name="habilidades[]">
      <option value="">Seleccionar habilidad</option>
      <?php foreach ($allHab as $h): ?>
        <option value="<?= $h['id'] ?>"
          <?= $h['id'] == $habId ? 'selected' : '' ?>>
          <?= htmlspecialchars($h['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  <?php endforeach; ?>
<?php else: ?>
  <select name="habilidades[]">
    <option value="">Seleccionar habilidad</option>
    <?php foreach ($allHab as $h): ?>
      <option value="<?= $h['id'] ?>">
        <?= htmlspecialchars($h['nombre']) ?>
      </option>
    <?php endforeach; ?>
  </select>
<?php endif; ?>
</div>

<button type="button" onclick="agregarHabilidad()">➕ Agregar habilidad</button>

<hr>

<!-- ================= IDIOMAS ================= -->

<h3 class="form-section-title">Idiomas</h3>

<div id="contenedor-idiomas">
<?php if (!empty($idiomas_usuario)): ?>
  <?php foreach ($idiomas_usuario as $idiomaId): ?>
    <select name="idiomas[]">
      <option value="">Seleccionar idioma</option>
      <?php foreach ($allIdiomas as $i): ?>
        <option value="<?= $i['id'] ?>"
          <?= $i['id'] == $idiomaId ? 'selected' : '' ?>>
          <?= htmlspecialchars($i['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  <?php endforeach; ?>
<?php else: ?>
  <select name="idiomas[]">
    <option value="">Seleccionar idioma</option>
    <?php foreach ($allIdiomas as $i): ?>
      <option value="<?= $i['id'] ?>">
        <?= htmlspecialchars($i['nombre']) ?>
      </option>
    <?php endforeach; ?>
  </select>
<?php endif; ?>
</div>

<button type="button" onclick="agregarIdioma()">➕ Agregar idioma</button>

<br><br>

<button class="btn-primary" type="submit">Guardar cambios</button>
</form>

<script>
const habilidades = <?php echo json_encode($allHab); ?>;
const idiomas = <?php echo json_encode($allIdiomas); ?>;

function crearSelect(data, name) {
    let select = document.createElement("select");
    select.name = name;

    let optionDefault = document.createElement("option");
    optionDefault.value = "";
    optionDefault.text = "Seleccionar";
    select.appendChild(optionDefault);

    data.forEach(item => {
        let option = document.createElement("option");
        option.value = item.id;
        option.text = item.nombre;
        select.appendChild(option);
    });

    return select;
}

function agregarHabilidad() {
    const contenedor = document.getElementById("contenedor-habilidades");
    contenedor.appendChild(crearSelect(habilidades, "habilidades[]"));
}

function agregarIdioma() {
    const contenedor = document.getElementById("contenedor-idiomas");
    contenedor.appendChild(crearSelect(idiomas, "idiomas[]"));
}
</script>

<?php include '../includes/footer.php'; ?>

</body>
</html>
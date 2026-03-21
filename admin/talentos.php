<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../includes/db.php';
include 'header_admin.php';

/* ===== PROTECCION ADMIN ===== */
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /castingApp/index.php");
    exit;
}

/* ===== DETECTAR FILTROS ===== */
$hayFiltros = !empty($_GET);
$result = null;

if ($hayFiltros) {

    $where = [];
    $params = [];
    $types = "";

    if (!empty($_GET['nombre'])) {
        $where[] = "u.nombre LIKE ?";
        $params[] = "%".$_GET['nombre']."%";
        $types .= "s";
    }

    if (!empty($_GET['ubicacion'])) {
        $where[] = "t.ubicacion LIKE ?";
        $params[] = "%".$_GET['ubicacion']."%";
        $types .= "s";
    }

    if (!empty($_GET['genero'])) {
        $where[] = "t.genero = ?";
        $params[] = $_GET['genero'];
        $types .= "s";
    }

    if (!empty($_GET['experiencia'])) {
        $where[] = "t.experiencia = ?";
        $params[] = $_GET['experiencia'];
        $types .= "s";
    }

    if (!empty($_GET['altura_min'])) {
        $where[] = "t.altura >= ?";
        $params[] = $_GET['altura_min'];
        $types .= "i";
    }

    if (!empty($_GET['altura_max'])) {
        $where[] = "t.altura <= ?";
        $params[] = $_GET['altura_max'];
        $types .= "i";
    }

    if (!empty($_GET['peso_min'])) {
        $where[] = "t.peso >= ?";
        $params[] = $_GET['peso_min'];
        $types .= "i";
    }

    if (!empty($_GET['peso_max'])) {
        $where[] = "t.peso <= ?";
        $params[] = $_GET['peso_max'];
        $types .= "i";
    }

    if (!empty($_GET['color_ojos'])) {
        $where[] = "t.color_ojos LIKE ?";
        $params[] = "%".$_GET['color_ojos']."%";
        $types .= "s";
    }

    if (!empty($_GET['color_pelo'])) {
        $where[] = "t.color_pelo LIKE ?";
        $params[] = "%".$_GET['color_pelo']."%";
        $types .= "s";
    }

    if (!empty($_GET['tez'])) {
        $where[] = "t.tez LIKE ?";
        $params[] = "%".$_GET['tez']."%";
        $types .= "s";
    }

    if (!empty($_GET['edad_min'])) {
        $where[] = "TIMESTAMPDIFF(YEAR, t.fecha_nacimiento, CURDATE()) >= ?";
        $params[] = $_GET['edad_min'];
        $types .= "i";
    }

    if (!empty($_GET['edad_max'])) {
        $where[] = "TIMESTAMPDIFF(YEAR, t.fecha_nacimiento, CURDATE()) <= ?";
        $params[] = $_GET['edad_max'];
        $types .= "i";
    }

    $sql = "
    SELECT 
    u.id,
    u.nombre,
    t.apellido,
    t.ubicacion,
    t.experiencia,
    t.genero,
    TIMESTAMPDIFF(YEAR, t.fecha_nacimiento, CURDATE()) AS edad,
    (
        SELECT archivo 
        FROM talento_media 
        WHERE talento_media.usuario_id = u.id 
        AND talento_media.tipo = 'foto'
        LIMIT 1
    ) AS foto
    FROM usuarios u
    JOIN talentos t ON u.id = t.usuario_id";

    if ($where) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $sql .= " ORDER BY u.id DESC";

    $stmt = $conn->prepare($sql);

    if ($params) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="/castingApp/admin/css/talentos.css">
<title>Talentos - Admin</title>
</head>
<body>

<div class="admin-container">

<h1 class="titulo-seccion">🎭 Buscar talentos</h1>

<form method="GET" class="filtro-card">
<input type="text" name="nombre" placeholder="Nombre">
<input type="text" name="ubicacion" placeholder="Ubicación">

<select name="genero">
<option value="">Género</option>
<option value="masculino">Masculino</option>
<option value="femenino">Femenino</option>
<option value="otro">Otro</option>
</select>

<select name="experiencia">
<option value="">Experiencia</option>
<option value="Ninguna">Ninguna</option>
<option value="Amateur">Amateur</option>
<option value="Profesional">Profesional</option>
</select>

<br><br>

Edad:
<input type="number" name="edad_min" placeholder="min">
<input type="number" name="edad_max" placeholder="max">

Altura:
<input type="number" name="altura_min" placeholder="min">
<input type="number" name="altura_max" placeholder="max">

Peso:
<input type="number" name="peso_min" placeholder="min">
<input type="number" name="peso_max" placeholder="max">

<br><br>

<select name="color_pelo">
<option value="">Color de pelo</option>
<option value="Rubio">Rubio</option>
<option value="Morocho">Morocho</option>
<option value="Castaño">Castaño</option>
<option value="Pelirrojo">Pelirrojo</option>
</select>

<select name="color_ojos">
<option value="">Color de ojos</option>
<option value="Azul">Azul</option>
<option value="Negro">Negro</option>
<option value="Marron">Marron</option>
<option value="Verde">Verde</option>
</select>

<select name="tez">
<option value="">Tez</option>
<option value="clara">Clara</option>
<option value="media">Media</option>
<option value="oscura">Oscura</option>
</select>

<br><br>

<button type="submit">Buscar</button>
</form>

<hr>

<?php if (!$hayFiltros): ?>
<p>🔎 Usá los filtros para buscar talentos</p>

<?php elseif ($result && $result->num_rows > 0): ?>

<div class="talentos-grid">

<?php while($row = $result->fetch_assoc()): ?>

<div class="talento-card">

    <div onclick="abrirModal(<?= $row['id'] ?>)" style="cursor:pointer">

        <?php if (!empty($row['foto'])): ?>
            <img class="talento-img"
                 src="/castingApp/uploads/fotos/<?= htmlspecialchars($row['foto']) ?>">
        <?php else: ?>
            <img class="talento-img"
                 src="/castingApp/assets/img/sin-foto.jpg">
        <?php endif; ?>

        <div class="talento-info">
            <h3><?= htmlspecialchars($row['nombre']) ?> <?= htmlspecialchars($row['apellido']) ?></h3>
            <p><strong>Edad:</strong> <?= $row['edad'] ?></p>
            <p><strong>Ubicación:</strong> <?= htmlspecialchars($row['ubicacion']) ?></p>
            <p><strong>Experiencia:</strong> <?= htmlspecialchars($row['experiencia']) ?></p>
            <p><strong>Género:</strong> <?= htmlspecialchars($row['genero']) ?></p>
        </div>

    </div>

    <!-- BOTON ELIMINAR -->
    <form action="eliminar_talento.php" method="POST" 
          onsubmit="return confirm('¿Seguro que querés eliminar este talento?');">

        <input type="hidden" name="id" value="<?= $row['id'] ?>">

        <button type="submit" class="btn-eliminar">
            Eliminar
        </button>

    </form>

</div>

<?php endwhile; ?>

</div>

<?php else: ?>
<p>No se encontraron talentos</p>
<?php endif; ?>

</div>

<!-- MODAL -->
<div id="modalTalento" class="modal">
<div class="modal-content">
<span class="cerrar" onclick="cerrarModal()">&times;</span>
<div id="contenidoModal"></div>
</div>
</div>

<script>
function abrirModal(id) {
    fetch("get_talento.php?id=" + id)
        .then(res => res.text())
        .then(data => {
            document.getElementById("contenidoModal").innerHTML = data;
            document.getElementById("modalTalento").style.display = "flex";
        });
}

function cerrarModal() {
    document.getElementById("modalTalento").style.display = "none";
}

window.onclick = function(e) {
    const modal = document.getElementById("modalTalento");
    if (e.target === modal) {
        modal.style.display = "none";
    }
}
</script>

</body>
</html>
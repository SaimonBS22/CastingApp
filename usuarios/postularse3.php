<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /castingApp/login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "casting");

// traer idiomas
$idiomas = $conn->query("SELECT * FROM idiomas");

// traer habilidades
$habilidades = $conn->query("SELECT * FROM habilidades");

// guardamos resultados en arrays (IMPORTANTE para reutilizar en JS)
$idiomasArray = $idiomas->fetch_all(MYSQLI_ASSOC);
$habilidadesArray = $habilidades->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear talento</title>
  <link rel="stylesheet" href="/castingApp/styles/postularse.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<h2>Informacion Artisitica</h2><br>
<a href="/castingApp/index.php"><button>Volver</button></a>

<form method="POST" action="/castingApp/guardar_postularse3.php" enctype="multipart/form-data">

    <select name="experiencia" required>
        <option value="">Experiencia</option>
        <option value="Ninguna">Ninguna</option>
        <option value="Amateur">Amateur</option>
        <option value="Profesional">Profesional</option>
    </select><br><br>

    <!-- ================= IDIOMAS ================= -->

    <h3>Idiomas</h3>

    <div id="contenedor-idiomas">
        <select name="idiomas[]">
            <option value="">Seleccionar idioma</option>
            <?php foreach($idiomasArray as $row): ?>
                <option value="<?= $row['id']; ?>">
                    <?= $row['nombre']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="button" onclick="agregarIdioma()">➕ Agregar idioma</button>

    <br><br>

    <!-- ================= HABILIDADES ================= -->

    <h3>Habilidades</h3>

    <div id="contenedor-habilidades">
        <select name="habilidades[]">
            <option value="">Seleccionar habilidad</option>
            <?php foreach($habilidadesArray as $row): ?>
                <option value="<?= $row['id']; ?>">
                    <?= $row['nombre']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="button" onclick="agregarHabilidad()">➕ Agregar habilidad</button>

    <br><br>

    <!-- ================= MEDIA ================= -->

    <h3>Fotos</h3>
    <input type="file" name="fotos[]" multiple accept="image/*"><br><br>

    <h3>Videos (mp4)</h3>
    <input type="file" name="videos[]" multiple accept="video/mp4">

    <h3>Links de video (YouTube, Vimeo, etc)</h3>
    <input type="url" name="links[]" placeholder="https://youtube.com/..."><br>
    <input type="url" name="links[]" placeholder="https://youtube.com/..."><br>
    <input type="url" name="links[]" placeholder="https://youtube.com/..."><br><br>

    <label>Observaciones</label><br>
    <textarea name="observaciones" placeholder='"Disponibilidad para viajar..."'></textarea>

    <button type="submit">Guardar</button><br><br>
</form>

<?php include '../includes/footer.php'; ?>

<!-- ================= JS ================= -->

<script>
const idiomas = <?php echo json_encode($idiomasArray); ?>;
const habilidades = <?php echo json_encode($habilidadesArray); ?>;

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

function agregarIdioma() {
    const contenedor = document.getElementById("contenedor-idiomas");
    const nuevoSelect = crearSelect(idiomas, "idiomas[]");
    contenedor.appendChild(nuevoSelect);
}

function agregarHabilidad() {
    const contenedor = document.getElementById("contenedor-habilidades");
    const nuevoSelect = crearSelect(habilidades, "habilidades[]");
    contenedor.appendChild(nuevoSelect);
}
</script>

</body>
</html>
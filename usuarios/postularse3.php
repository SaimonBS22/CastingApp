<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /castingApp/login.php");
    exit;
}

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

   <h3>Fotos</h3>
  <input type="file" name="fotos[]" multiple accept="image/*"><br><br>

  <h3>Videos (mp4)</h3>
<input type="file" name="videos[]" multiple accept="video/mp4">

  <h3>Links de video (YouTube, Vimeo, etc)</h3>
  <input type="url" name="links[]" placeholder="https://youtube.com/..."><br>
  <input type="url" name="links[]" placeholder="https://youtube.com/..."><br>
  <input type="url" name="links[]" placeholder="https://youtube.com/..."><br><br>

    <label>
    <input type="checkbox" name="habilidades[]" value="1">
    Actuación
    </label>

    <label>
    <input type="checkbox" name="habilidades[]" value="2">
    Baile
    </label>

     <label>
    <input type="checkbox" name="habilidades[]" value="3">
    Deportes
    </label>

     <label>
    <input type="checkbox" name="habilidades[]" value="4">
    Idiomas
    </label>

    <label>
    <input type="checkbox" name="habilidades[]" value="5">
    Canto
    </label><br><br>

    <label for="Observaciones">Observaciones</label><br>
    <textarea name="observaciones" id="" placeholder='"Disponibilidad para viajar..."'></textarea>


  <button type="submit">Guardar</button><br><br>
</form>

<?php include '../includes/footer.php'; ?>

</body>
</html>
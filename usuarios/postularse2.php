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

<h2>Caracteristicas fisicas</h2><br>
<a href="/castingApp/index.php"><button>Volver</button></a>

<form method="POST" action="/castingApp/guardar_postularse2.php">

  <input type="number" name="altura" placeholder="Altura (cm)" required><br><br>

  <input type="number" name="peso" placeholder="Peso (Kg)" required><br><br>

  <select name="color_pelo" required>
    <option value="">Color de pelo</option>
    <option value="Rubio">Rubio</option>
    <option value="Morocho">Morocho</option>
    <option value="Castaño">Castaño</option>
    <option value="Pelirrojo">Pelirrojo</option>
  </select><br><br>

   <select name="color_ojos" required>
    <option value="">Color de ojos</option>
    <option value="Azul">Azul</option>
    <option value="Negro">Negro</option>
    <option value="Marron">Marron</option>
    <option value="Verde">Verde</option>
  </select><br><br>

  <select name="tez" required>
    <option value="">Tez</option>
    <option value="clara">Clara</option>
    <option value="media">Media</option>
    <option value="oscura">Oscura</option>
  </select><br><br>

  <select name="talle_ropa" required>
    <option value="">Talle de ropa</option>
    <option value="XXS">XXS</option>
    <option value="XS">XS</option>
    <option value="S">S</option>
    <option value="M">M</option>
    <option value="L">L</option>
    <option value="XL">XL</option>
    <option value="XXL">XXL</option>
  </select><br><br>

   <input type="number" name="talle_calzado" placeholder="Talle de calzado" required><br><br>


  <button type="submit">Guardar y continuar</button><br><br>
</form>

<?php include '../includes/footer.php'; ?>

</body>
</html>

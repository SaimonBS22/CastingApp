<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: /castingApp/login.php");
  exit;
}

$usuario_id = $_SESSION['user_id'];

/* 🔍 Verificar si ya existe talento */
$check = $conn->prepare("SELECT id FROM talentos WHERE usuario_id = ?");
$check->bind_param("i", $usuario_id);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
  // 🚫 Ya tiene talento creado
  header("Location: miPerfil.php?error=ya_creado");
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

<h2>Datos personales</h2><br>
<a href="/castingApp/index.php"><button>Volver</button></a>

<form method="POST" action="/castingApp/guardar.php">
  <input type="text" name="nombre" placeholder="Nombre" required><br><br>

   <input type="text" name="apellido" placeholder="Apellido"><br><br>

  <input type="date" name="fecha_nacimiento" placeholder="Fecha de nacimiento"><br><br>

   <input type="number" name="telefono" placeholder="Telefono"><br><br>

  <select name="genero">
    <option value="">Género</option>
    <option value="masculino">Masculino</option>
    <option value="femenino">Femenino</option>
    <option value="otro">Otro</option>
  </select><br><br>

  <input type="text" name="ubicacion" placeholder="Ubicacion" required><br><br>
  <input type="text" name="email" placeholder="Ejemplo@mail.com" required><br><br>

  <button type="submit">Guardar y continuar</button><br><br>
</form>

<?php include '../includes/footer.php'; ?>

</body>
</html>

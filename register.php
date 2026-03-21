<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro</title>
  <link rel="stylesheet" href="./styles/register.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>
<body>


<form class="form-container" method="POST" action="register_procesar.php">

  <h2>Crear cuenta</h2>

  <div class="input-group">
    <i class="bi bi-person-fill"></i>
    <input type="text" name="nombre" placeholder="Nombre completo" required>
  </div>

  <div class="input-group">
    <i class="bi bi-envelope"></i>
    <input type="email" name="email" placeholder="Email" required>
  </div>

  <div class="input-group">
    <i class="bi bi-lock"></i>
    <input type="password" name="password" placeholder="Contraseña" required>
  </div>

  <div class="buttons">
    <button class="btn-primary" type="submit">Registrarse</button>
    <button
      class="btn-secondary"
      type="button"
      onclick="window.location.href='login.php'">
      Login
    </button>
  </div>

</form>



</body>
</html>

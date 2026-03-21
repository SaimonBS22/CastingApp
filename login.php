<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="es">
  <link rel="stylesheet" href="./styles/login.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<body>

<form class="login-container" method="POST" action="login_procesar.php">

  <h1>Login</h1>

  <div class="input-group">
    <i class="bi bi-envelope"></i>
    <input type="email" name="email" placeholder="Email" required>
  </div>

  <div class="input-group">
    <i class="bi bi-lock"></i>
    <input type="password" name="password" placeholder="Contraseña" required>
  </div>

  <p>
    ¿Olvidaste tu contraseña?
    <a href="#">Recuperar</a>
  </p>

  <button class="btn-primary" type="submit">
    Ingresar
  </button>

  <div class="register-link">
    ¿No tenés cuenta?
    <a href="register.php">Crear cuenta</a>
  </div>

</form>


</body>
</html>

<?php
session_start();
include 'includes/db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare(
  "SELECT id, nombre, password, rol FROM usuarios WHERE email = ?"
);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
  if (password_verify($password, $user['password'])) {

    // 🔥 CLAVE UNIFICADA
  $_SESSION['user_id'] = $user['id'];
$_SESSION['nombre']  = $user['nombre'];
$_SESSION['rol']     = $user['rol'];

// ✅ REDIRECCIÓN SEGÚN ROL
if ($user['rol'] === 'admin') {
    header("Location: /castingApp/admin/index.php");
} else {
    header("Location: /castingApp/index.php");
}
exit;
  }
}

echo "❌ Email o contraseña incorrectos";




//CONTRASENA ADMIN: 123456
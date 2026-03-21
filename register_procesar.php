<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'includes/db.php';

// Tomar datos del formulario
$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validaciones mínimas
if ($nombre === '' || $email === '' || $password === '') {
    die("❌ Todos los campos son obligatorios");
}

// Verificar si el email ya existe
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    die("❌ Este email ya está registrado");
}

// Encriptar contraseña
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Insertar usuario (rol SIEMPRE user)
$stmt = $conn->prepare(
  "INSERT INTO usuarios (nombre, email, password, rol)
   VALUES (?, ?, ?, 'user')"
);

$stmt->bind_param("sss", $nombre, $email, $passwordHash);

if ($stmt->execute()) {
    // Registro exitoso → login
    header("Location: login.php");
    exit;
}

die("❌ Error al registrar usuario");

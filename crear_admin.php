<?php
include 'includes/db.php';

$nombre = "Admin";
$email = "admin@casting.com";
$passwordPlano = "admin123";
$passwordHash = password_hash($passwordPlano, PASSWORD_DEFAULT);
$rol = "admin";

$stmt = $conn->prepare(
  "INSERT INTO usuarios (nombre, email, password, rol)
   VALUES (?, ?, ?, ?)"
);

$stmt->bind_param("ssss", $nombre, $email, $passwordHash, $rol);

if ($stmt->execute()) {
    echo "✅ Admin creado correctamente<br>";
    echo "Email: admin@casting.com<br>";
    echo "Password: admin123";
} else {
    echo "❌ Error al crear admin";
}

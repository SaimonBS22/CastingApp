<!-- 
===========================================================================================================================
EL CODIGO QUE ESTA ABAJO HAY QUE PONERLO EN TODOS LOS ARCHIVOS PARA QUE NINGUN USER PUEDA METERSE EN LA SECCION ADMIN 
===========================================================================================================================
-->
<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /castingApp/login.php");
    exit;
}

include '../includes/db.php';
include 'header_admin.php';
?>

<h1>Panel Administrador</h1>

<p>Bienvenido <?= $_SESSION['nombre'] ?></p>



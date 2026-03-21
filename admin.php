<?php
include 'auth.php';

if ($_SESSION['rol'] !== 'admin') {
    die("⛔ Acceso denegado. Solo administradores.");
}

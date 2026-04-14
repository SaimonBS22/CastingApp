<?php
$conn = new mysqli("localhost", "root", "", "casting");
/*$conn = new mysqli("sql202.infinityfree.com", "if0_41446212", "nH4QaWUWSUtT", "if0_41446212_castingpro");*/

$conn->set_charset("utf8");


if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

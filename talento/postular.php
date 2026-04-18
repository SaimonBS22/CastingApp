<?php
require_once("../includes/db.php");
require_once("../includes/auth.php");

$user_id = $_SESSION['user_id'];
$casting_id = $_POST['casting_id'];
$personaje_id = $_POST['personaje_id'] ?? null;

/* =========================
   VERIFICAR SI YA EXISTE
========================= */

if ($personaje_id) {

    $stmt = $conn->prepare("
        SELECT id FROM postulaciones
        WHERE casting_id=? AND usuario_id=? AND personaje_id=?
    ");
    $stmt->bind_param("iii", $casting_id, $user_id, $personaje_id);

} else {

    $stmt = $conn->prepare("
        SELECT id FROM postulaciones
        WHERE casting_id=? AND usuario_id=?
    ");
    $stmt->bind_param("ii", $casting_id, $user_id);
}

$stmt->execute();
$existe = $stmt->get_result()->fetch_assoc();


/* =========================
   INSERTAR SI NO EXISTE
========================= */

if (!$existe) {

    if ($personaje_id) {

        $stmt = $conn->prepare("
            INSERT INTO postulaciones (casting_id, usuario_id, personaje_id)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("iii", $casting_id, $user_id, $personaje_id);

    } else {

        $stmt = $conn->prepare("
            INSERT INTO postulaciones (casting_id, usuario_id)
            VALUES (?, ?)
        ");
        $stmt->bind_param("ii", $casting_id, $user_id);
    }

    $stmt->execute();
}

/* =========================
   REDIRECCION
========================= */

header("Location: castings.php");
exit;
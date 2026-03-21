<?php
require_once("../includes/db.php");
require_once("../includes/auth.php");

$user_id = $_SESSION['user_id'];
$casting_id = $_POST['casting_id'];

/* verificar si ya existe */
$stmt = $conn->prepare("
SELECT id FROM postulaciones
WHERE casting_id=? AND usuario_id=?
");
$stmt->bind_param("ii",$casting_id,$user_id);
$stmt->execute();
$existe = $stmt->get_result()->fetch_assoc();

if(!$existe){

    $stmt = $conn->prepare("
    INSERT INTO postulaciones (casting_id,usuario_id)
    VALUES (?,?)
    ");

    $stmt->bind_param("ii",$casting_id,$user_id);
    $stmt->execute();
}

header("Location: castings.php");
exit;
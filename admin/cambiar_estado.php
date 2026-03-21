<?php
require_once("../includes/db.php");
require_once("../includes/auth.php");

$stmt = $conn->prepare("
UPDATE postulaciones
SET estado=?
WHERE casting_id=? AND usuario_id=?
");

$stmt->bind_param(
"sii",
$_POST['estado'],
$_POST['casting_id'],
$_POST['usuario_id']
);

$stmt->execute();

header("Location: postulados.php?casting_id=".$_POST['casting_id']);
exit;
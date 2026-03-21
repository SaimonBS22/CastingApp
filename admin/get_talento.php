<?php
include '../includes/db.php';

$id = intval($_GET['id']);

$sql = "
SELECT 
u.nombre,
u.email,
t.*,
TIMESTAMPDIFF(YEAR, t.fecha_nacimiento, CURDATE()) AS edad
FROM usuarios u
JOIN talentos t ON u.id = t.usuario_id
WHERE u.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo "<p>Talento no encontrado</p>";
    exit;
}
?>

<h2><?= htmlspecialchars($row['nombre']) ?> <?= htmlspecialchars($row['apellido']) ?></h2>

<p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
<p><strong>Teléfono:</strong> <?= htmlspecialchars($row['telefono']) ?></p>
<p><strong>Edad:</strong> <?= htmlspecialchars($row['edad']) ?></p>
<p><strong>Altura:</strong> <?= htmlspecialchars($row['altura']) ?> cm</p>
<p><strong>Peso:</strong> <?= htmlspecialchars($row['peso']) ?> kg</p>
<p><strong>Color ojos:</strong> <?= htmlspecialchars($row['color_ojos']) ?></p>
<p><strong>Color pelo:</strong> <?= htmlspecialchars($row['color_pelo']) ?></p>
<p><strong>Tez:</strong> <?= htmlspecialchars($row['tez']) ?></p>
<p><strong>Talle calzado:</strong> <?= htmlspecialchars($row['talle_calzado']) ?></p>
<p><strong>Talle ropa:</strong> <?= htmlspecialchars($row['talle_ropa']) ?></p>
<p><strong>Experiencia:</strong> <?= htmlspecialchars($row['experiencia']) ?></p>
<p><strong>Observaciones:</strong><br><?= nl2br(htmlspecialchars($row['observaciones'] ?? '')) ?></p>
<?php

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /castingApp/login.php");
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<link rel="stylesheet" href="/castingApp/admin/css/header_admin.css">
<link rel="stylesheet" href="/castingApp/admin/css/castings.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">



<section class="contenedorHeader">
    <div class="page-wrapper header-inner">

     <div class="logo">
            <a href="/castingApp/admin/index.php">CastingPro</a>
     </div>

    <nav class="menu">
        <a href="/castingApp/admin/index.php">Dashboard</a>
        <a href="/castingApp/admin/talentos.php">Talentos</a>
        <a href="/castingApp/admin/castings.php">Proyectos</a>
        <a href="/castingApp/index.php">Ver sitio</a>
        <a href="/castingApp/logout.php">Salir</a>
    </nav>

    </div>
</section>
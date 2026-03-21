
    <link rel="stylesheet" href="/castingApp/styles/header.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">


 <section class="contenedorHeader">
    <div class="page-wrapper header-inner">
        
        <div class="logo">
            <a href="/castingApp/index.php">CastingPro</a>
        </div>

        <nav class="menu">
            <a href="/castingApp/index.php">Inicio</a>
            <a href="/castingApp/talento/castings.php">Proyectos</a>
            <a href="">Quienes Somos</a>
            <!-- <a href="/castingApp/usuarios/postularse.php">Crear Perfil</a> -->
            <a href="/castingApp/usuarios/miPerfil.php">Mi Perfil</a>
            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                <a href="/castingApp/admin/index.php">
                Panel Admin
                </a>
            <?php endif; ?>
        </nav>

        <div class="redes">
            <i class="bi bi-instagram"></i>
            <i class="bi bi-linkedin"></i>
            <i class="bi bi-facebook"></i>
        </div>

    </div>
</section>

<aside class="sidebar">
    <div class="contenedor-sidebar">
        <h2>Uptask</h2>

        <div class="cerrar-menu">
            <img id="cerrar-menu" src="build/img/cerrar.svg" alt="imagen cerrar">
        </div>
    </div>


    <nav class="sidebar-nav">

        <a class="<?php echo ($titulo === 'Proyectos') ? 'activo' : ''; ?>" href="/dashboard">Proyectos</a>
        <a class="<?php echo ($titulo === 'Crear Proyectos') ? 'activo' : ''; ?>" href="/crear-proyecto">Crear
            Proyectos</a>
        <a class="<?php echo ($titulo === 'Perfil') ? 'activo' : ''; ?>" href="/perfil">perfil</a>

    </nav>

    <div class="cerrar-session-mobile">
        <a class="cerrar-sesion" href="/logout">Cerrar Sesion</a>
    </div>

    <div class="autor">
        <h3>developed by RonalCl</h3>
    </div>
</aside>
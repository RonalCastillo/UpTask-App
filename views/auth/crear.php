<div class="contenedor crear">
    <?php
    include_once  __DIR__ . '/../templates/nombre-sitio.php';
    ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta en UpTask</p>
        <?php
        include_once  __DIR__ . '/../templates/alertas.php';
        ?>
        <form action="/crear" method="POST" class="formulario">

            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="nombre" id="nombre" placeholder="Tu nombre" name="nombre"
                    value="<?php echo $usuario->nombre; ?>">
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Tu email" name="email"
                    value="<?php echo $usuario->email; ?>">
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" placeholder="Tu password" name="password">
            </div>
            <div class="campo">
                <label for="password2">Repetir tu Password</label>
                <input type="password" id="password2" placeholder="Repite Tu password" name="password2">
            </div>

            <input type="submit" class="boton" value="Crear cuenta">
        </form>
        <div class="acciones">
            <a href="/">Iniciar Session</a>
            <a href="/olvide">Olvidaste tu password?</a>
        </div>
    </div>
    <!--contenedor sm-->


</div>
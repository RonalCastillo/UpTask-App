<div class="contenedor restablecer">
    <?php
    include_once  __DIR__ . '/../templates/nombre-sitio.php';
    ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nuevo password</p>
        <?php
        include_once  __DIR__ . '/../templates/alertas.php';
        ?>

        <?php
        if ($mostrar) {  ?>
        <form method="POST" class="formulario">


            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" placeholder="Tu password" name="password">
            </div>

            <input type="submit" class="boton" value="Restablecer password">
        </form>

        <?php } ?>
        <div class="acciones">
            <a href="/crear">Aun no tienes una cuenrta?</a>
            <a href="/olvide">Olvidaste tu password?</a>
        </div>
    </div>
    <!--contenedor sm-->


</div>
<?php include_once __DIR__ . '/header-dashboard.php';

?>

<?php if (count($proyectos) === 0) { ?>
    <p class="no-proyectos">No hay Proyectos aun
        <a href="/crear-proyecto">Comienza creando un proyecto</a>
    </p>
<?php } else { ?>

    <ul class="listado-proyectos">
        <?php foreach ($proyectos as $proyecto) { ?>
            <div class="proyecto">
                <form action="/proyecto/eliminar" method="post">
                    <input type="hidden" name="id" value="<?php echo $proyecto->id; ?>">
                    <input type="button" class="btn btn-eliminar" value="Eliminar" />
                </form>
                <a href="/proyecto?id=<?php echo $proyecto->url; ?>">
                    <li class="caja">
                        <?php echo $proyecto->proyecto; ?>

                    </li>
                </a>
            </div>
        <?php } ?>
    </ul>

<?php } ?>

<?php include_once __DIR__ . '/footer-dashboard.php';

?>

<?php
$script .= '
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="build/js/proyecto.js"></script>
   
';

?>
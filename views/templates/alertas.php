<?php
foreach ($alertas as $key => $alerta) :
    foreach ($alerta as $mensaje) :
?>



        <div class="alerta <?php echo $key; ?>">
            <?php echo $mensaje; ?>
        </div>
<?php
    endforeach;
endforeach;
?>


<script>
    // Esperar 4 segundos (4000 milisegundos)
    setTimeout(function() {
        // Seleccionar todos los elementos con la clase 'alerta'
        var alertas = document.querySelectorAll('.alerta');

        // Iterar sobre cada elemento y eliminar la clase 'alerta' o aplicar el efecto deseado
        alertas.forEach(function(alerta) {
            alerta.style = 'display:none';

        });
    }, 4000);
</script>
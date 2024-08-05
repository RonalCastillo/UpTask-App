(function () {

    // Reviso si la clase .btn-eliminar existe, así me evito errores en la consola.
    // Si la clase existe, significa que hay por lo menos 1 proyecto.

    if (document.querySelector('.btn-eliminar')) {
        const btnEliminar = document.querySelectorAll('.btn-eliminar');

        // Itero sobre todos los resultados
        btnEliminar.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                // Selecciono el formulario del boton
                const formulario = btn.parentNode;

                // Lanzo mi alerta sweetalert
                Swal.fire({
                    title: '¿Estas seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '¡Sí, eliminar!'
                }).then((result) => {
                    // Valido el resultado, si es true, hago el submit al formulario.
                    if (result.value) {
                        formulario.submit();
                    }
                })
            });
        });

    }

})();
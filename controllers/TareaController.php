<?php

namespace Controllers;

use Model\Tarea;
use Model\Proyecto;


class TareaController
{
    public static function index()
    {

        $proyectoId = $_GET['id'];

        if (!$proyectoId) header('Location: /dashboard');

        $proyecto = Proyecto::where('url', $proyectoId);


        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);


        echo json_encode(['tareas' => $tareas]); //taread
        //debuguear($tareas);
    }
    public static function crear()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION)) {
                session_start();
            };

            $proyectoId = $_POST['proyectoId'];

            $proyecto = Proyecto::where('url', $proyectoId);

            if (!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al agregar la tarea '
                ];

                echo json_encode($respuesta);

                return;
            }

            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;

            $resultado = $tarea->guardar();

            $respuesta = [
                'tipo' => 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'Tarea creada correctamente',
                'proyectoId' => $proyecto->id

            ];

            echo json_encode($respuesta);
        }
    }
    public static function actualizar()
    {


        //validar que el proyecto exista

        $proyecto = Proyecto::where('url', $_POST['proyectoId']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!isset($_SESSION)) {
                session_start();
            };
            if (!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al actualizar la tarea '
                ];
                echo json_encode($respuesta);

                return;
            }

            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;

            $resultado = $tarea->guardar();

            if ($resultado) {

                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $tarea->id,
                    'proyectoId' => $proyecto->id,
                    'mensaje' => 'Actualizado correctamente'

                ];

                echo json_encode(['respuesta' => $respuesta]);
            }
        }
    }
    public static function eliminar()
    {


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);

            if (!isset($_SESSION)) {
                session_start();
            };
            if (!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al actualizar la tarea '
                ];
                echo json_encode($respuesta);

                return;
            }
            $tarea = new Tarea($_POST);
            $resultado = $tarea->eliminar();

            $resultado = [
                'resultado' => $resultado,
                'mensaje' => 'Eliminado Correctamente',
                'tipo' => 'exito'

            ];

            echo json_encode($resultado);
        }
    }
}

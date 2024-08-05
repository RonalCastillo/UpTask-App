<?php

namespace  Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;


class DashboardController
{


    public static function index(Router $router)
    {

        if (!isset($_SESSION)) {
            session_start();
        };

        isAuth();
        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioId', $id);
        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos

        ]);
    }

    public static function eliminar_proyecto()
    {
        session_start();
        isAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if ($id) {
                $proyecto = Proyecto::find($id);
                if ($proyecto->propietarioId === $_SESSION['id']) {
                    $id = $_POST['id'];
                    $proyecto = Proyecto::find($id);
                    $proyecto->eliminar();

                    // Redireccionar
                    header('Location: /dashboard');
                }
            }
        }
    }
    public static function crear_proyecto(Router $router)
    {

        if (!isset($_SESSION)) {
            session_start();
        };

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $proyecto = new Proyecto($_POST);
            //VALIDACION

            $alertas = $proyecto->validarProyecto();
            if (empty($alertas)) {
                //genearar una url unica

                $hash = md5(uniqid());
                $proyecto->url = $hash;
                //almacenar el creador del proyecto

                $proyecto->propietarioId = $_SESSION['id'];
                //guardar el proyecto

                $proyecto->guardar();

                //redireccionar al usuario

                header('Location:/proyecto?id=' . $proyecto->url);
            }
        }


        isAuth();
        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyectos',
            'alertas' => $alertas

        ]);
    }
    public static function proyecto(Router $router)
    {

        if (!isset($_SESSION)) {
            session_start();
        };
        isAuth();

        $token = $_GET['id'];

        if (!$token) header('Location: /dashboard');

        //revisar que la persona que visita el proyecto, es quien la creo
        $proyecto = Proyecto::where('url', $token);

        if ($proyecto->propietarioId !== $_SESSION['id']) {

            header('Location: /dashboard');
        }


        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto

        ]);
    }
    public static function perfil(Router $router)
    {

        if (!isset($_SESSION)) {
            session_start();
        };
        isAuth();
        $alertas = [];
        $usuario = Usuario::find($_SESSION['id']);


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarPerfil();

            if (empty($alertas)) {

                $existeUsuario = Usuario::where('email',  $usuario->email);

                if ($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    //mensaje de error


                    Usuario::setAlerta('error', 'cuenta ya registrada');
                    $alertas = $usuario->getAlertas();
                } else {
                    //guardar el registro


                    $usuario->guardar();

                    Usuario::setAlerta('exito', 'Guardado Correctamente');
                    $alertas = $usuario->getAlertas();

                    //reescribir la sesion
                    $_SESSION['nombre'] = $usuario->nombre;
                }
            }
        }

        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas

        ]);
    }

    public static function cambiar_password(Router $router)
    {
        if (!isset($_SESSION)) {
            session_start();
        };
        isAuth();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);

            //sincronizar con los datos del usuario
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevoPassword();

            if (empty($alertas)) {
                $resultado = $usuario->comprobar_password();

                if ($resultado) {


                    $usuario->password = $usuario->password_nuevo;
                    //elminar propiedades no necesarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    //hashear e nuevo password

                    $usuario->hashPassword();


                    //ACTUALIZAR EL NUEVO PASSWORD EN LA DB

                    $resultado =   $usuario->guardar();

                    if ($resultado) {
                        Usuario::setAlerta('exito', 'Password guardado correctamente');
                        $alertas = $usuario->getAlertas();
                    }
                    //asignar y almacenar el nuevo password


                } else {
                    Usuario::setAlerta('error', 'Password incorrecto');
                    $alertas = $usuario->getAlertas();
                }
            }
        }



        $router->render('dashboard/cambiar-password', [

            'titulo' => 'Cambiar Password',
            //'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
}
<?php

namespace  Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{

    public static function login(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $usuario = new Usuario($_POST);

            $alertas = $usuario->validarLogin();

            if (empty($alertas)) {

                //verificar que el usuario exista
                $usuario = Usuario::where('email', $usuario->email);

                if (!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                } else {
                    //el usuario existe

                    if (password_verify($_POST['password'], $usuario->password)) {

                        //iniciar sesion de usuarios

                        if (!isset($_SESSION)) {
                            session_start();
                        };

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        //  debuguear($_SESSION);
                        //redireccionar al usuario

                        header('Location:/dashboard');
                    } else {
                        Usuario::setAlerta('error', 'password incorrecto');
                    }
                }
            }
        }
        $alertas = Usuario::getAlertas();

        //render a la vista
        $router->render('auth/login', [
            'titulo' => 'iniciar session',
            'alertas' => $alertas
        ]);
    }
    public static function logout()
    {


        if (!isset($_SESSION)) {
            session_start();
        };
        $_SESSION = [];
        header('Location:/');
    }
    public static function crear(Router $router)
    {
        $alertas = [];

        $usuario = new Usuario;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();


            if (empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);


                if ($existeUsuario) {
                    Usuario::setAlerta('error', 'El usuario ya existe');
                    $alertas = Usuario::getAlertas();
                } else {
                    //hashear el password
                    $usuario->hashPassword();

                    //eliminar password 2
                    unset($usuario->password2);

                    //generar token

                    $usuario->crearToken();

                    //crear un nuevo usuario
                    $resultado =  $usuario->guardar();

                    //enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarConfirmacion();
                    if ($resultado) {

                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear', [
            'titulo' => 'crear cuenta',
            'usuario' => $usuario,
            //varianle alertas viene desde el model
            'alertas' => $alertas
        ]);
    }
    public static function olvide(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if (empty($alertas)) {
                $usuario =  Usuario::where('email', $usuario->email);
                //buscar el usuario

                if ($usuario) {
                    //generar un nuevo token
                    $usuario->crearToken();

                    unset($usuario->password2);

                    //actualizar al usuario

                    $usuario->guardar();

                    //enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                    //imprimir la alerta

                    Usuario::setAlerta('exito', 'Hemos enviado las intrucciones a tu email');
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        //muestra la vista

        $router->render('auth/olvide', [
            'titulo' => 'olvide mi password',
            'alertas' => $alertas
        ]);
    }
    public static function restablecer(Router $router)
    {
        $token = s($_GET['token']);
        $mostrar = true;

        if (!$token) header('Location: /');

        //identificar al usuario con este token
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            Usuario::setAlerta('error', 'token no valido');

            $mostrar = false;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //añadir el nuevo password

            $usuario->sincronizar($_POST);
            //validar el password

            $alertas =   $usuario->validarPassword();

            if (empty($alertas)) {
                //hashear el password
                $usuario->hashPassword();

                // unset($usuario->password2);
                //eliminar el token
                $usuario->token = null;

                //guardar el usuario a la DB
                $resultado =  $usuario->guardar();

                //redireccionar al usuario

                if ($resultado) {

                    header('Location: /');
                }

                debuguear($usuario);
            }
        }

        //muestra la vista
        $alertas = Usuario::getAlertas();



        $router->render('auth/restablecer', [
            'titulo' => 'restablecer password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }
    public static function mensaje(Router $router)
    {


        //muestra la vista

        $router->render('auth/mensaje', [
            'titulo' => 'cuenta creada con exito '
        ]);
    }
    public static function confirmar(Router $router)
    {
        $token = s($_GET['token']);

        if (!$token) header('Location: /');
        //encontrar al usuario con este token

        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {

            //no se encontro un usuario con ese tokem
            Usuario::setAlerta('error', 'Token no valido');
        } else {
            ///confrimar la cuenta
            $usuario->confirmado = 1;
            $usuario->token = '';

            //eliminar password 2
            unset($usuario->password2);
            $usuario->guardar();

            Usuario::setAlerta('exito', 'cuenta comprobada correctamente');
            $alertas = Usuario::getAlertas();
        }

        $alertas = Usuario::getAlertas();
        //muestra la vista

        $router->render('auth/confirmar', [
            'titulo' => 'confirma tu cuenta UpTask',
            'alertas' => $alertas
        ]);
    }
}

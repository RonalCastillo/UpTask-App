<?php

namespace Model;

#[\AllowDynamicProperties]
class Usuario extends ActiveRecord
{

    protected static $tabla = 'usuarios';

    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];


    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    //validar login

    public function validarLogin()
    {

        if (!$this->email) {
            self::$alertas['error'][] = 'el email del usuario es obligatorio';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'el password del usuario es obligatorio';
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El email no es valido';
        }

        return self::$alertas;
    }

    //validar cuentas nuevas

    public function validarNuevaCuenta()
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'el nombre de usuario es obligatorio';
        }
        if (!$this->email) {
            self::$alertas['error'][] = 'el email del usuario es obligatorio';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'el password del usuario es obligatorio';
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'el password debe contener mas de 6 caracteres';
        }
        if ($this->password !== $this->password2) {
            self::$alertas['error'][] = 'los password deben ser iguales';
        }

        return self::$alertas;
    }


    //valida un email

    public function validarEmail()
    {

        if (!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El email no es valido';
        }
        return self::$alertas;
    }
    //comprobar el password
    public function comprobar_password(): bool
    {
        return password_verify($this->password_actual, $this->password);
    }

    //hash password

    public function validarPerfil()
    {

        if (!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }

        if (!$this->email) {
            self::$alertas['error'][] = 'El emial es obligatorio';
        }
        return self::$alertas;
    }

    public function hashPassword(): void
    {

        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public  function nuevoPassword(): array
    {

        if (!$this->password_actual) {
            self::$alertas['error'][] = 'El password actual no puede ir vacio';
        }
        if (!$this->password_nuevo) {
            self::$alertas['error'][] = 'El password nuevo no puede ir vacio';
        }

        if (strlen($this->password_nuevo) < 6) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    //generar un token

    public function crearToken(): void
    {
        $this->token = uniqid();

        // debuguear($this);
    }

    //validar password

    public function validarPassword()
    {
        if (!$this->password) {
            self::$alertas['error'][] = 'el password del usuario es obligatorio';
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'el password debe contener mas de 6 caracteres';
        }

        return self::$alertas;
    }
}

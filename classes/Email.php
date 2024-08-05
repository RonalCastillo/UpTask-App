<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {


        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@uptask.com', 'uptask.com');
        $mail->addAddress($this->email);
        $mail->Subject = 'Confirma tu cuenta';

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p>Hola <strong>" . $this->nombre . "</strong> Haz creado tu cuenta en UpTask, solo debes confirmarla presionando el siguiente enlace </p>";
        $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['APP_URL'] . "/confirmar?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje </p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviar el email
        $mail->send();
    }

    public function enviarInstrucciones()
    {

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@uptask.com', 'uptask.com');
        $mail->addAddress($this->email);
        $mail->Subject = 'Restablece tu password';

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p>Hola <strong>" . $this->nombre . "</strong> Parece que has olvidado tu password, sigue el siguiente enlace para restablecer </p>";
        $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['APP_URL'] . "/restablecer?token=" . $this->token . "'>Restablecer tu password</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje </p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviar el email
        $mail->send();
    }
}
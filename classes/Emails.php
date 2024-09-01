<?php
namespace Classes;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class Emails {
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){
        // Crear el transporte para Gmail
        // Asegúrate de codificar la contraseña y otros valores si es necesario
        $emailUser = $_ENV['EMAIL_USER'];
        $emailPass = $_ENV['EMAIL_PASS']; // Codificación de la contraseña
        $emailSmtp = $_ENV['EMAIL_SMTP'];
        $emailPort = $_ENV['EMAIL_PORT'];

        // Construcción del DSN
        $dsn = sprintf('smtp://%s:%s@%s:%s', $emailUser, $emailPass, $emailSmtp, $emailPort);

        // Crear el transporte de correo
        $transport = Transport::fromDsn($dsn);

        // Crear el Mailer usando el transporte
        $mailer = new Mailer($transport);

        //Definir contenido del mensaje
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre ."</strong> Has creado tu 
        cuenta en UpTask, solo debes confirmarla en el siguiente enlace.</p>";
        $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['APP_URL'] . "/confirmar?token=" .$this->token. "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no creaste esta cuenta, puedes ignorar este mensaje</p>";
        $contenido .= "</html>";


        // Crear el mensaje
        $email = (new Email())
        ->from($_ENV['EMAIL_USER']) // Cambia esto a tu dirección de correo
        ->to($this->email)
        ->subject('Confirma tu cuenta')
        ->html($contenido);

        // Enviar el mensaje
        try {
            $mailer->send($email);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
        
    }

    public function enviarInstrucciones(){
         // Crear el transporte para Gmail
        // Asegúrate de codificar la contraseña y otros valores si es necesario
        $emailUser = $_ENV['EMAIL_USER'];
        $emailPass = $_ENV['EMAIL_PASS']; // Codificación de la contraseña
        $emailSmtp = $_ENV['EMAIL_SMTP'];
        $emailPort = $_ENV['EMAIL_PORT'];

        // Construcción del DSN
        $dsn = sprintf('smtp://%s:%s@%s:%s', $emailUser, $emailPass, $emailSmtp, $emailPort);

        // Crear el transporte de correo
        $transport = Transport::fromDsn($dsn);
        
        // Crear el Mailer usando el transporte
        $mailer = new Mailer($transport);

        //Definir contenido del mensaje
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre ."</strong> Parece que has olvidado tu password, sigue el siguiente enlace para reestablecerlo.</p>";
        $contenido .= "<p>Presiona aquí: <a href='". $_ENV['APP_URL'] ."/reestablecer?token=".$this->token."'>Reestablecer Password</a></p>";
        $contenido .= "<p>Si tu no solicitaste este correo, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        // Crear el mensaje
        $email = (new Email())
        ->from($_ENV['EMAIL_USER']) // Cambia esto a tu dirección de correo
        ->to($this->email)
        ->subject('Reestablece tu contraseña')
        ->html($contenido);

        // Enviar el mensaje
        $mailer->send($email);
    }
}



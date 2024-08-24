<?php
namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;

class Email{
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){
        $email = new PHPMailer();
        $email->isSMTP();
        $email->Host = 'sandbox.smtp.mailtrap.io';
        $email->SMTPAuth = true;
        $email->Port = 2525;
        $email->Username = 'daa35bf9657225';
        $email->Password = 'ac731d339055ae';

        $email->setFrom('cuentas@uptask.com');
        $email->addAddress('cuentas@uptask.com', 'uptask.com');
        $email->Subject = 'Confirma tu Cuenta';

        $email->isHTML(TRUE);
        $email->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre ."</strong> Has creado tu 
        cuenta en UpTask, solo debes confirmarla en el siguiente enlace.</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/confirmar?token=".$this->token."'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no creaste esta cuenta, puedes ignorar este mensaje</p>";
        $contenido .= "</html>";

        $email->Body = $contenido;
        if($email->send()){
            return true;
        }else{
            return false;
        }
    }

    public function enviarInstrucciones(){
        $email = new PHPMailer();
        $email->isSMTP();
        $email->Host = 'sandbox.smtp.mailtrap.io';
        $email->SMTPAuth = true;
        $email->Port = 2525;
        $email->Username = 'daa35bf9657225';
        $email->Password = 'ac731d339055ae';

        $email->setFrom('cuentas@uptask.com');
        $email->addAddress('cuentas@uptask.com', 'uptask.com');
        $email->Subject = 'Confirma tu Cuenta';

        $email->isHTML(TRUE);
        $email->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre ."</strong> Parece que has olvidado tu password, sigue el siguiente enlace para reestablecerlo.</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/reestablecer?token=".$this->token."'>Reestablecer Password</a></p>";
        $contenido .= "<p>Si tu no solicitaste este correo, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";
        $email->Body = $contenido;
        if($email->send()){
            return true;
        }else{
            return false;
        }
    }
}
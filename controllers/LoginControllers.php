<?php
namespace Controllers;

use MVC\Router;
use Classes\Emails;
use Model\Usuario;

class LoginControllers{
    public static function login(Router $router){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin(); 
            if(empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);
                if(!$usuario){
                    Usuario::setAlerta('error', 'El usuario no existe');
                }else{
                    if(!$usuario->confirmado === 0){
                        Usuario::setAlerta('error', 'El usuario no esta confirmado');
                    }else{
                        if(password_verify($_POST['password'], $usuario->password)){
                            session_start();
                            $_SESSION['id'] = $usuario->id;
                            $_SESSION['nombre'] = $usuario->nombre;
                            $_SESSION['email'] = $usuario->email;
                            $_SESSION['login'] = true;

                            header('Location: /dashboard');
                        }else{
                            Usuario::setAlerta('error', 'Contraseña incorrecta');
                        }
                    }
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'titulo'=>' Iniciar Sesión',
            'alertas'=>$alertas
        ]);
    }

    public static function logout(){
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function crear(Router $router){
        $usuario = new Usuario;
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarCuenta();
            $existeUsuario = Usuario::where('email', $usuario->email);
            if(empty($alertas)){     
                if($existeUsuario){
                    Usuario::setAlerta('error', 'El usuario ya esta registrado');
                }else{
                    $usuario->hashPassword();
                    unset($usuario->password2);
                    $usuario->generarToken();
                    $resultado = $usuario->guardar();
                    $email = new Emails($usuario->email, $usuario->nombre, $usuario->token);
                    $resultado2 = $email->enviarConfirmacion();
                    if($resultado && $resultado2){
                        header('Location: /mensaje');
                    }else{
                        Usuario::setAlerta('error', 'Hubo un error al enviar la notificacion');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/crear', [
            'titulo'=>' Crear Cuenta',
            'usuario'=>$usuario,
            'alertas'=>$alertas
        ]);
    }

    public static function olvide(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();
            if(empty($alertas)){
                $usuario = Usuario::where('email', $usuario->email);
                if($usuario && $usuario->confirmado == "1"){
                    $usuario->generarToken();
                    $resultado = $usuario->guardar();
                    $email = new Emails($usuario->email, $usuario->nombre, $usuario->token);
                    $resultado2 = $email->enviarInstrucciones();
                    if($resultado && $resultado2){
                        Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');
                    }        
                }else{
                    if(!$usuario){
                        Usuario::setAlerta('error', 'No existe una cuenta asociada a ese correo');
                    }else{                 
                        Usuario::setAlerta('error', 'La cuenta no esta confirmada');
                    }
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide', [
            'titulo'=>' Reestablecer Contraseña',
            'alertas'=>$alertas
        ]);
    }
    public static function reestablecer(Router $router){
        $token = s($_GET['token']);
        $mostrar = true;
        if(!$token)header('Location: /');
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no Válido');    
            $mostrar = false;
        } 
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPassword();
            if(empty($alertas)){
                $usuario->hashPassword();
                $usuario->token = null;
                $resultado = $usuario->guardar();
                if($resultado){
                    header('Location: /');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/reestablecer', [
            'titulo'=>' Reestablecer Contraseña',
            'mostrar'=>$mostrar,
            'alertas'=>$alertas
        ]);
    }
    public static function mensaje(Router $router){
        $router->render('auth/mensaje', [
            'titulo'=>' Cuenta Creada'
        ]);
    }
    public static function confirmar(Router $router){
        $token = s($_GET['token']);
        if(!$token)header('Location: /');
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no Válido');    
        }else{
            $usuario->confirmado = 1;
            $usuario->token = null;
            unset($usuario->password2);
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }
        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar', [
            'titulo'=>' Confirmar Cuenta',
            'alertas'=> $alertas
        ]);
    }
}
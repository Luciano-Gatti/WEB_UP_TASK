<?php
namespace Controllers;
use MVC\Router;
use Model\Usuario;
use Model\Proyecto;

class DashboardControllers{
    public static function index(Router $router){
        session_start();
        isAuth();
        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioID', $id);

        $router->render('dashboard/index', [
            'titulo'=>'Proyectos',
            'proyectos'=>$proyectos
        ]);
    }
    
    public static function crear_proyecto(Router $router){
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = new Proyecto($_POST);
            $alertas = $proyecto->validarProyecto();
            if(empty($alertas)){
                $proyecto->url = md5(uniqid());
                $proyecto->propietarioID = $_SESSION['id'];
                $proyecto->guardar();
                header('Location: /proyecto?url=' .$proyecto->url);
            }
        }

        $router->render('dashboard/crear-proyecto', [
            'titulo'=>'Crear Proyecto',
            'alertas'=>$alertas
        ]);
    }

    public static function proyecto(Router $router){
        session_start();
        isAuth();
        
        //Verifica si hay una URL 
        $token = $_GET['url'];
        if(!$token){
            header('Location: /dashboard');
        } 

        //Identifica el proyecto al que pertenece esa URL
        $proyecto = Proyecto::where('url', $token);

        debuguear([
            'propietarioID'=>$proyecto->propietarioID,
            'sessionID'=>$_SESSION['id']
        ]);
        //Verifica si el usuario tiene acceso a ese proyecto
        if($proyecto->propietarioID != $_SESSION['id']){
            header('Location: /dashboard');
        } 

        $titulo = $proyecto->nombre ?? '';//Guarda el nombre del proyecto

        $router->render('dashboard/proyecto', [
            'titulo'=>$titulo
        ]);
    }

    public static function perfil(Router $router){
        session_start();
        isAuth();
        $usuario = Usuario::find($_SESSION['id']);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validar_perfil();
            if(empty($alertas)){
                $existeUsuario = Usuario::where('email', $usuario->email);
                if($existeUsuario && $existeUsuario->id !== $usuario->id){
                    Usuario::setAlerta('error', 'El email seleccionado ya pertenece a una cuenta');
                }else{
                    $resultado = $usuario->guardar();
                    if($resultado){
                        $_SESSION['nombre'] = $usuario->nombre;
                        Usuario::setAlerta('exito', 'Guardado Correctamente');
                    }
                }
                
            }
        }
        $alertas = $usuario->getAlertas();

        $router->render('dashboard/perfil', [
            'titulo'=>'Perfil',
            'usuario'=>$usuario,
            'alertas'=>$alertas
        ]);
    }

    public static function cambiar_password(Router $router){
        session_start();
        isAuth();      
        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->nuevo_password();
            if(empty($alertas)){
                $resultado = $usuario->comprobarPassword();
                if($resultado){
                    $usuario->password = $usuario->password_nuevo;
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);
                    $usuario->hashPassword();
                    $actualizado = $usuario->guardar();
                    if($actualizado){
                        Usuario::setAlerta('exito', 'Contraseña actualizada correctamente');
                    }
                }else{
                    Usuario::setAlerta('error', 'Contraseña incorrecta');
                }
            }
        }
        $alertas = Usuario::getAlertas();

        $router->render('dashboard/cambiar-password', [
            'titulo'=>'Cambiar Contraseña',
            'alertas'=>$alertas,
            'usuario'=>$usuario
        ]);
    }
}
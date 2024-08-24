<?php
namespace Controllers;

use Model\Tarea;
use Model\Proyecto;

class TareaControllers{
    public static function index(){
        session_start();
        $proyectoURL = $_GET['url'];
        if(!$proyectoURL) header('Location: /dashboard'); 
        $proyecto = Proyecto::where('url', $proyectoURL);
        if(!$proyecto || $proyecto->usuarioID !== $_SESSION['id']) header('Location: /dashboard'); 
        $tareas = Tarea::belongsTo('proyectoID', $proyecto->id);
        echo json_encode(['tareas'=>$tareas]);
    }   

    public static function crear(){
        session_start();
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $url = $_POST['url'];
            $proyecto = Proyecto::where('url', $url);
            if(!$proyecto || $proyecto->usuarioID !== $_SESSION['id']){
                $respuesta = [
                    'tipo'=>'error',
                    'mensaje'=>'Hubo un error al agregar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }
            $tarea = new Tarea($_POST);
            $tarea->proyectoID = $proyecto->id;
            $resultado = $tarea->guardar();
            $respuesta = [
                'tipo'=>'exito',
                'id'=> $resultado['id'],
                'mensaje'=>'La terea se ha creado correctamente',
                'proyectoID'=>$proyecto->id
            ];
            echo json_encode($respuesta);
        }
    }

    public static function actualizar(){
        session_start();
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = Proyecto::where('url', $_POST['url']);
            if(!$proyecto || $proyecto->usuarioID !== $_SESSION['id']){
                $respuesta = [
                    'tipo'=>'error',
                    'mensaje'=>'Hubo un error al actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }
            $tarea = new Tarea($_POST);
            $resultado = $tarea->guardar();
            if($resultado){
                $respuesta = [
                    'tipo'=>'exito',
                    'id'=> $tarea->id,
                    'proyectoID'=>$proyecto->id,
                    'mensaje'=>'Actualizado correctamente'
                ];
            }
            echo json_encode(['respuesta'=>$respuesta]);
        }
    } 

    public static function eliminar(){
        session_start();
        if($_SERVER['REQUEST_METHOD'] === 'POST'){     
            $proyecto = Proyecto::where('url', $_POST['url']);
            if(!$proyecto || $proyecto->usuarioID !== $_SESSION['id']){
                $respuesta = [
                    'tipo'=>'error',
                    'mensaje'=>'Hubo un error al actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }
            $tarea = new Tarea($_POST);
            $resultado = $tarea->eliminar();
            
            if($resultado){
                $resultado = [
                    'resultado' => $resultado,
                    'mensaje' => 'Elimando correctamente',
                    'tipo' => 'exito'
                ];
            }
            echo json_encode($resultado);
        }
    }
}
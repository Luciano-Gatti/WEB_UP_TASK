<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\LoginControllers;
use Controllers\TareaControllers;
use Controllers\DashboardControllers;

$router = new Router();

//Login
$router->get('/', [LoginControllers::class, 'login']);
$router->post('/', [LoginControllers::class, 'login']);
$router->get('/logout', [LoginControllers::class, 'logout']);

//Crear cuenta
$router->get('/crear', [LoginControllers::class, 'crear']);
$router->post('/crear', [LoginControllers::class, 'crear']);

//Olvide mi password
$router->get('/olvide', [LoginControllers::class, 'olvide']);
$router->post('/olvide', [LoginControllers::class, 'olvide']);

//Resetear password
$router->get('/reestablecer', [LoginControllers::class, 'reestablecer']);
$router->post('/reestablecer', [LoginControllers::class, 'reestablecer']);

//Confirmacion de cuenta
$router->get('/mensaje', [LoginControllers::class, 'mensaje']);
$router->get('/confirmar', [LoginControllers::class, 'confirmar']);

//Zona de proyectos
$router->get('/dashboard', [DashboardControllers::class, 'index']);
$router->get('/crear-proyecto', [DashboardControllers::class, 'crear_proyecto']);
$router->post('/crear-proyecto', [DashboardControllers::class, 'crear_proyecto']);
$router->get('/proyecto', [DashboardControllers::class, 'proyecto']);
$router->get('/perfil', [DashboardControllers::class, 'perfil']);
$router->post('/perfil', [DashboardControllers::class, 'perfil']);
$router->get('/cambiar-password', [DashboardControllers::class, 'cambiar_password']);
$router->post('/cambiar-password', [DashboardControllers::class, 'cambiar_password']);

//API para las tareas
$router->get('/api/tarea', [TareaControllers::class, 'index']);
$router->post('/api/tarea', [TareaControllers::class, 'crear']);
$router->post('/api/tarea/actualizar', [TareaControllers::class, 'actualizar']);
$router->post('/api/tarea/eliminar', [TareaControllers::class, 'eliminar']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
<?php
namespace Model;

class Tarea extends ActiveRecord{
    protected static $tabla = 'tareas';
    protected static $columnasDB = ['id', 'nombre', 'estado', 'proyectoID'];

    public $id;
    public $nombre;
    public $estado;
    public $proyectoID;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->estado = $args['estado'] ?? 0;
        $this->proyectoID = $args['proyectoID'] ?? '';
    }
}
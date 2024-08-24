<?php
namespace Model;

class Usuario extends ActiveRecord{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public $id;
    public $nombre;
    public $email;
    public $password_actual;
    public $password_nuevo;
    public $password;
    public $password2;
    public $token;
    public $confirmado;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->password2 = $args['password2'] ?? '';
    }

    public function comprobarPassword(){
        return password_verify($this->password_actual, $this->password);
    }

    public function nuevo_password(){
        if(!$this->password_actual || !$this->password_nuevo || !$this->password2){
            self::$alertas['error'][] = 'Todos los campos son obligatorios';
        }else{
            if(strlen($this->password_nuevo) < 6){
                self::$alertas['error'][] = 'La contraseña nueva debe contener al menos 6 caracteres';
            }else{
                if($this->password_nuevo !== $this->password2){
                    self::$alertas['error'][] = 'Las contraseñas no coinciden';
                }
            }
        }
        return self::$alertas;
    }

    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }else{
            if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
                self::$alertas['error'][] = 'Email no valido';
            }
        }
        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }
        return self::$alertas;
    }

    public function validar_perfil(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        return  self::$alertas;
    }

    public function validarCuenta(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }else{
            if(strlen($this->password) < 6){
                self::$alertas['error'][] = 'La contraseña debe contener al menos 6 caracteres';
            }else{
                if($this->password !== $this->password2){
                    self::$alertas['error'][] = 'Las contraseñas no coinciden';
                }
            }
        }
        return  self::$alertas;
    }

    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }else{
            if(strlen($this->password) < 6){
                self::$alertas['error'][] = 'La contraseña debe contener al menos 6 caracteres';
            }else{
                if($this->password !== $this->password2){
                    self::$alertas['error'][] = 'Las contraseñas no coinciden';
                }
            }
        }
        return  self::$alertas;
    }

    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }else{
            if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
                self::$alertas['error'][] = 'Email no valido';
            }
        }
        
        return self::$alertas;
    }

    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function generarToken(){
        $this->token = uniqid();
    }
}
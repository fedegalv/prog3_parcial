<?php
class Usuario{
    public $email;
    public $tipo;
    public $clave;

    function __construct($email, $clave, $tipoUsuario)
    {
        $this->email = $email;
        $this->clave = $clave;
        $this->tipo = $tipoUsuario;
    }

    
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __toString(){
        return $this->email.'*'.$this->clave.'*'.$this->tipo;
     }
     public static function isValidType($tipoUsuario)
     {
        if($tipoUsuario == 'admin' || $tipoUsuario == 'user'){
            return true;
        }
        return false;
     }
     
}
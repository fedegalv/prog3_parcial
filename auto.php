<?php
class Auto{
    public $patente;
    public $tipo;
    public $fecha_ingreso;
    public $email;

    function __construct($patente, $tipo, $fechaIngreso, $emailUsuario)
    {
        $this->patente = $patente;
        $this->tipo = $tipo;
        $this->fecha_ingreso = $fechaIngreso;
        $this->email = $emailUsuario;
    }

    
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }
     
}
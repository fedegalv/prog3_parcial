<?php
class Precio{
    public $precio_hora;
    public $precio_estadia;
    public $precio_mensual;

    function __construct($precioHora, $precioEstadia, $precioMensual)
    {
        $this->precio_hora = $precioHora;
        $this->precio_estadia = $precioEstadia;
        $this->precio_mensual = $precioMensual;
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
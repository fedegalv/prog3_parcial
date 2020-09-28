<?php
require_once 'usuario.php';
require_once 'fileHandler.php';
require_once 'precio.php';
require_once 'auto.php';


require __DIR__ . '/vendor/autoload.php';

use \Firebase\JWT\JWT;

/*TOKEN*/

$key = "primerparcial.";

/*global*/
$jwt = null;

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? 0;
//echo 'Inicia programa';
switch ($path) {
    case '/registro':
        if($method == 'POST')
            {
                $emailRepetido = false;

                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                $tipo = $_POST['tipo'] ?? '';

                if(Usuario::isValidType($tipo))
                {
                    $usuario = new Usuario($email, $password, $tipo);

                    $listaUsuarios = fileHandler::ReadJson("users.json");
                    //var_dump($listaUsuarios);
                    if($listaUsuarios != null)
                    {
                        foreach($listaUsuarios as $item)
                        {
                            if($email == $item->email)
                            {
                                $emailRepetido = true;
                                echo "EMAIL REPETIDO, NO SE PUDO GUARDAR";
                                break;
                            }
                        }
                    }
                   
                    if($emailRepetido == false)
                    {
                        fileHandler::SaveAsJson("users.json", $usuario);
                        echo 'usuario guardado';
                    }
                }
                else{
                    echo 'ERROR: Tipo de usuario incorrecto';
                }
            }

        break;
        case '/login':
            {
                if($method == 'POST')
                {
                    $email = $_POST['email'] ?? '';
                    $password = $_POST['password'] ?? '';
    
                    $found = false;
                $listaUsuarios = fileHandler::ReadJson("users.json");
                //echo "<pre>";
                //var_dump($listaUsuarios);
                if($listaUsuarios != null)
                    {
                        foreach ($listaUsuarios as $usuario) {
                        if ($email == $usuario->email && $password == $usuario->clave ) {
                            $found = true;
                            $tipo = $usuario->tipo;
                         }
                        }
                    }
                if ($found) {
                    echo "Usuario encontrado<br>Tu JWT es:<br>";
                    $payload = array(
                        "email" => $email,
                        "tipo" => $tipo
                    );
                    $jwt = JWT::encode($payload, $key);
                    
                    
                    print_r($jwt);
                } else {
                    echo "Usuario no encontrado<br>";
                    $jwt = null;
                }
                //echo "</pre>";
                }
            }
        break;

        case'/precio':
            {
                
                if ($method == 'POST') {
                    $token = $_SERVER['HTTP_TOKEN'];
                    try {
                        $decoded = JWT::decode($token, $key, array('HS256'));

                        $precioHora = $_POST['precio_hora'] ?? '';
                        $precioEstadia = $_POST['precio_estadia'] ?? '';
                        $precioMensual =  $_POST['precio_mensual'] ?? '';
                        $precio = new Precio($precioHora, $precioEstadia, $precioMensual);

                        if($decoded->tipo == 'admin')
                        {
                            fileHandler::SaveSingleObjectAsJson("precios.json", $precio);
                        }
                        echo "TOKEN VALIDO <br> PRECIO GUARDADO";
                        
                    } catch (Exception $e) {
                        echo "ERROR AL AUNTENTIFICAR: TOKEN INCORRECTO O INVALIDO";
                    }
                }
            }
        break;
        case'/ingreso':
            {
                if ($method == 'POST') {
                    $token = $_SERVER['HTTP_TOKEN'];
                    try {
                        $decoded = JWT::decode($token, $key, array('HS256'));
                        if($decoded->tipo == 'user')
                        {
                            $patente = $_POST['patente'] ?? '';
                            $tipo = $_POST['tipo'] ?? '';
                            $fecha = $date = date('Y-m-d H:i');
                            $email = $decoded->email;
                            $auto = new Auto($patente, $tipo, $fecha, $email);
    
                            fileHandler::SaveAsJson("autos.json", $auto);
                            echo "TOKEN VALIDO <br> AUTO GUARDADO";
                        }
                        else{
                            echo 'INGRESO AUTOS SOLO TIPO USER';
                        }
                        
                    } catch (Exception $e) {
                        echo "ERROR AL AUNTENTIFICAR: TOKEN INCORRECTO O INVALIDO";
                    }
                }

            }
        break;
        case '/retiro':
        {
            if ($method == 'GET') {
                $token = $_SERVER['HTTP_TOKEN'];
                try {
                    $decoded = JWT::decode($token, $key, array('HS256'));
                    if($decoded->tipo == 'user')
                        {
                            
                            $patente = $_GET["patente"] ??"";
                            $fechaEgreso = $date = date('Y-m-d H:i');

                            $precioObjeto= fileHandler::ReadJson("precio.json");
                            foreach($listaAutos as $item)
                            {
                                if($patente == $item->patente)
                                {
                                    $tipo = $item->tipo;
                                    $fechaIngreso = $item->fecha_ingreso;
                                    break;
                                }
                            }
                            switch($tipo)
                            {
                                case 'hora':
                                    {
                                        $precio = $precioObjeto->precio_hora;
                                    }
                                break;
                                case 'estadia':
                                    {
                                        $precio = $precioObjeto->precio_estadia;
                                    }
                                break;
                                case 'mensual':
                                {
                                    $precio = $precioObjeto->precio_mensual;
                                }
                            }
                            echo 'IMPORTE:'.$precio.'<br>'.'PATENTE: '.$patente.'<br>'.'FECHA INGRESO: '.$fechaIngreso.'<br>'.'FECHA EGRESO: '.$fechaEgreso.'<br>';

                        }
                        else{
                            echo "RETIRO SOLO PARA TIPO USER";
                        }
                    
                } catch (Exception $e) {
                    echo "ERROR AL AUNTENTIFICAR: TOKEN INCORRECTO O INVALIDO";
                }
            }
        }
    
    default:
        echo 'RUTA INVALIDA';
        break;
}

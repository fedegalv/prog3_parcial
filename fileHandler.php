<?php

class fileHandler {


    public static function SaveAsJson($nombreArchivo, $objetoRegistrar)
    {
       
        //JSON
        /*
        $archivoJson = fopen("users.json", "a+");
        $fwrite = fwrite($archivoJson, json_encode($objetoRegistrar));
        fclose($archivoJson);
        */
        $result = fileHandler::ReadJson($nombreArchivo);
        $result[] = $objetoRegistrar;
        return file_put_contents(__DIR__."/".$nombreArchivo, json_encode($result, JSON_PRETTY_PRINT) );

        //return file_put_contents(__DIR__. '/users.json', json_encode($objetoRegistrar, JSON_PRETTY_PRINT),FILE_APPEND );
    }

    public static function ReadJson($nombreArchivo)
    {
        
        $users = json_decode(file_get_contents(__DIR__."/".$nombreArchivo, true));
        //var_dump($users);
        return $users;
    }

    public static function SaveSingleObjectAsJson($nombreArchivo, $objetoRegistrar)
    {
        $archivoJson = fopen($nombreArchivo, "w");
        $fwrite = fwrite($archivoJson, json_encode($objetoRegistrar));
        fclose($archivoJson);
    }
}
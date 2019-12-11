<?php

namespace Parzibyte\Servicios;

use PDO;

class BD
{
    static $bd;

    public static function obtener()
    {
        if (self::$bd) {
            return self::$bd;
        }
        $bd = new PDO(
            "mysql:host=" . Comun::env("HOST_MYSQL") . ";dbname=" . Comun::env("NOMBRE_BD_MYSQL"),
            Comun::env("USUARIO_MYSQL"),
            Comun::env("PASS_MYSQL")
        );
        $bd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $bd->query("SET NAMES 'utf8'");
        self::$bd = $bd;
        return $bd;
    }

    public static function obtenerParaSesion()
    {
        return self::obtener();
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: parzibyte
 * Date: 12/15/2019
 * Time: 7:48 PM
 */

namespace Parzibyte\Modelos;

use Parzibyte\Servicios\BD;

class ModeloAjustes
{
    public static function obtener($clave)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("SELECT valor FROM ajustes WHERE clave = ?");
        $sentencia->execute([$clave,
        ]);
        $objeto = $sentencia->fetchObject();
        if (!$objeto) return "";
        return $objeto->valor;
    }

    public static function guardar($clave, $valor)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("UPDATE ajustes SET valor = ? WHERE clave = ?");
        return $sentencia->execute([$valor, $clave,
        ]);
    }
}
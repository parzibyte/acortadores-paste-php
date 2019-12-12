<?php
/**
 * Created by PhpStorm.
 * User: parzibyte
 * Date: 12/4/2019
 * Time: 10:27 AM
 */

namespace Parzibyte\Controladores;


use Parzibyte\Modelos\ModeloSubidas;
use Parzibyte\Clases\Subida;

class ControladorSubidas
{


    public static function formularioAgregar()
    {
        return view("subidas/agregar_subida");
    }

    public static function agregarSubida()
    {
        $datos = getJsonRequest();
        $idSubida = ModeloSubidas::nuevaSubida(new Subida($datos->titulo, "", $datos->enlaces, $datos->acortadores));
        return json($idSubida);
    }

    public static function obtenerAcortadoresDisponibles()
    {
        return json(ModeloSubidas::obtenerAcortadoresDisponibles());
    }

    public static function editarSubida($idSubida)
    {
        return view("subidas/editar_subida", ["idSubida" => $idSubida]);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: parzibyte
 * Date: 12/15/2019
 * Time: 7:34 PM
 */

namespace Parzibyte\Controladores;

use Parzibyte\Modelos\ModeloAjustes;
use Parzibyte\Redirect;
use Parzibyte\Validator;

class ControladorAjustes
{
    public static function index()
    {
        return view("ajustes/ajustes", [
            "enlace" => ModeloAjustes::obtener("ENLACE_MEMBRESIA")
        ]);
    }

    public static function guardar()
    {
        Validator::validateOrRedirect($_POST, [
            "required" => ["enlace"]
        ]);
        $resultado = ModeloAjustes::guardar("ENLACE_MEMBRESIA", $_POST["enlace"]);
        Redirect::back()
            ->with([
                "mensaje" => $resultado ? "Guardado" : "Error guardando",
                "tipo" => $resultado ? "success" : "danger"
            ])
            ->do();
    }
}
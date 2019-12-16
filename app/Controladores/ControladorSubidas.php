<?php
/**
 * Created by PhpStorm.
 * User: parzibyte
 * Date: 12/4/2019
 * Time: 10:27 AM
 */

namespace Parzibyte\Controladores;


use Parzibyte\Modelos\ModeloAjustes;
use Parzibyte\Modelos\ModeloSubidas;
use Parzibyte\Clases\Subida;
use Parzibyte\Modelos\ModeloUsuarios;
use Parzibyte\Servicios\Comun;
use Parzibyte\Servicios\Sesion;
use Parzibyte\Servicios\SesionService;

class ControladorSubidas
{

    public static function verSubidaPublicamente($token)
    {
        $subida = Subida::porToken($token);
        if (!$subida) {
            header("HTTP/1.0 404 Not Found");
            return view("404");
        }
        $conEnlacesOriginales = false;
        $diasRestantes = -1;
        $idUsuario = SesionService::leer("idUsuario");
        if ($idUsuario != null) {
            $usuario = ModeloUsuarios::uno($idUsuario);
            $diasRestantes = Comun::diasRestantesHastaHoy($usuario->fecha_vencimiento_pago);
            $conEnlacesOriginales = $diasRestantes >= 0 || $usuario->administrador;
        }
        return view("subidas/ver_subida_publica", [
            "diasRestantes" => $diasRestantes,
            "enlace" => ModeloAjustes::obtener("ENLACE_MEMBRESIA"),
            "conOriginales" => $conEnlacesOriginales,
            "subida" => $subida->conEnlaces($conEnlacesOriginales),
        ]);
    }

    public static function eliminarSubida($idSubida)
    {
        return json(ModeloSubidas::eliminarPorId($idSubida));
    }


    public static function formularioAgregar()
    {
        return view("subidas/agregar_subida");
    }

    public static function verSubidas()
    {
        return view("subidas/subidas");
    }

    public static function listadoDeSubidas()
    {
        return json(ModeloSubidas::obtenerListado());
    }

    public static function agregarSubida()
    {
        $datos = getJsonRequest();
        $idSubida = ModeloSubidas::nuevaSubida(new Subida($datos->titulo, $datos->descripcion, $datos->enlaces, $datos->acortadores));
        return json($idSubida);
    }

    public static function actualizarSubida()
    {
        $datos = getJsonRequest();
        $idSubida = ModeloSubidas::actualizarSubida(new Subida($datos->titulo, $datos->descripcion, $datos->enlaces, $datos->acortadores, "", "", $datos->id));
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

    public static function detallesDeSubida($idSubida)
    {
        return json(ModeloSubidas::obtenerSubidaPorId($idSubida));
    }
}
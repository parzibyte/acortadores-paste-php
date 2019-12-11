<?php
/**
 * Created by PhpStorm.
 * User: parzibyte
 * Date: 12/11/2019
 * Time: 11:21 AM
 */

namespace Parzibyte\Modelos;

use Exception;
use Parzibyte\Clases\Subida;
use Parzibyte\Servicios\Acortador;
use Parzibyte\Servicios\BD;
use Parzibyte\Servicios\Comun;
use PDO;

class ModeloSubidas
{
    public static function obtenerAcortadoresDisponibles()
    {
        $acortadores = [
            [
                "id" => ID_ACORTADOR_ADFLY,
                "nombre" => "adf.ly"
            ],
            [
                "id" => ID_ACORTADOR_OUO,
                "nombre" => "ouo.io"
            ],
            [
                "id" => ID_ACORTADOR_SHINK_ME,
                "nombre" => "shink.me"
            ],
            [
                "id" => ID_ACORTADOR_SHORTEST,
                "nombre" => "shorte.st"
            ],
            // shortzon.com no sirve actualmente
            // error:1409442E:SSL routines:ssl3_read_bytes:tlsv1 alert protocol version
            /*[
                "id" => ID_ACORTADOR_SHORTZON,
                "nombre" => "shortzon.com"
            ],*/
            [
                "id" => ID_ACORTADOR_SHRINKMEIO,
                "nombre" => "shrinkme.io"
            ],
        ];
        return $acortadores;
    }

    /**
     * @param Subida $subida
     * @return bool|string
     * @throws Exception
     */
    public static function nuevaSubida(Subida $subida)
    {
        // Primero preparamos los enlaces y reportamos error si hay
        foreach ($subida->getEnlaces() as $enlace) {
            $enlace->acortado = Acortador::acortarSegunDefinido($subida->getAcortadores(), $enlace->enlace);
        }
        $bd = BD::obtener();
        $bd->beginTransaction();
        $consulta = "INSERT INTO subidas(titulo, descripcion, fecha) VALUES (?, ? ,?)";
        $sentencia = $bd->prepare($consulta);
        $parametros = [$subida->getTitulo(), $subida->getDescripcion(), Comun::fechaYHoraActualParaMySQL()];
        $resultado = $sentencia->execute($parametros);
        if (!$resultado) {
            $bd->rollBack();
            return false;
        }
        $idSubida = $bd->lastInsertId();
        $consulta = "INSERT INTO enlaces_subidas(id_subida, leyenda, enlace_original, enlace_acortado) VALUES (?,?,?,?)";
        $sentencia = $bd->prepare($consulta);
        foreach ($subida->getEnlaces() as $enlace) {
            $resultado = $sentencia->execute([$idSubida, $enlace->leyenda, $enlace->enlace, $enlace->acortado]);
            if (!$resultado) {
                $bd->rollBack();
                return false;
            }
        }
        $sentencia = $bd->prepare("INSERT INTO acortadores_subidas(id_subida, id_acortador) VALUES (?, ?)");
        foreach ($subida->getAcortadores() as $acortador) {
            if (!$sentencia->execute([$idSubida, $acortador])) {
                $bd->rollBack();
                return false;
            };
        }
        $bd->commit();
        return $idSubida;
    }
}
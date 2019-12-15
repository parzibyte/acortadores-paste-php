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

    public static function eliminarPorId($id)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("DELETE FROM subidas WHERE id = ?");
        return $sentencia->execute([$id,
        ]);
    }

    public static function obtenerSubidaPorId($id)
    {
        return Subida::porId($id)->conEnlaces(true)->conAcortadores();
    }

    public static function obtenerSubidaPublicaPorToken($token)
    {
        return Subida::porToken($token)->conEnlaces();
    }

    public static function obtenerListado()
    {
        return BD::obtener()
            ->query("SELECT id, titulo, token, descripcion, fecha FROM subidas ORDER BY fecha DESC")
            ->fetchAll(PDO::FETCH_OBJ);
    }


    public static function obtenerSubidaPorToken($token)
    {
        $sentencia = BD::obtener()
            ->prepare("SELECT id, titulo, token, descripcion, fecha FROM subidas WHERE token = ? limit 1");
        $sentencia->execute([$token,]);
        return $sentencia->fetchObject();
    }

    public static function obtenerTokenUnico()
    {
        do {
            $token = Comun::obtenerCadenaAleatoria(5);
        } while (self::obtenerSubidaPorToken($token) != FALSE);
        return $token;
    }

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
        $consulta = "INSERT INTO subidas(titulo, descripcion, fecha, token) VALUES (?, ? ,?, ?)";
        $sentencia = $bd->prepare($consulta);
        $parametros = [$subida->getTitulo(), $subida->getDescripcion(), Comun::fechaYHoraActualParaMySQL(), self::obtenerTokenUnico()];
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


    /**
     * @param Subida $subida
     * @return bool|string
     * @throws Exception
     */
    public static function actualizarSubida(Subida $subida)
    {
        // Primero preparamos los enlaces y reportamos error si hay
        foreach ($subida->getEnlaces() as $enlace) {
            $enlace->acortado = Acortador::acortarSegunDefinido($subida->getAcortadores(), $enlace->enlace);
        }
        $bd = BD::obtener();
        $bd->beginTransaction();
        // Limpiar
        $sentencia = $bd->prepare("DELETE FROM acortadores_subidas WHERE id_subida = ?");
        if (!$sentencia->execute([$subida->getId()])) {
            $bd->rollBack();
            return false;
        }
        $sentencia = $bd->prepare("DELETE FROM enlaces_subidas WHERE id_subida = ?");
        if (!$sentencia->execute([$subida->getId()])) {
            $bd->rollBack();
            return false;
        }

        $consulta = "UPDATE subidas SET titulo = ?, descripcion = ?, fecha = ? WHERE id = ?";
        $sentencia = $bd->prepare($consulta);
        $parametros = [$subida->getTitulo(), $subida->getDescripcion(), Comun::fechaYHoraActualParaMySQL(), $subida->getId()];
        $resultado = $sentencia->execute($parametros);
        if (!$resultado) {
            $bd->rollBack();
            return false;
        }
        $consulta = "INSERT INTO enlaces_subidas(id_subida, leyenda, enlace_original, enlace_acortado) VALUES (?,?,?,?)";
        $sentencia = $bd->prepare($consulta);
        foreach ($subida->getEnlaces() as $enlace) {
            $resultado = $sentencia->execute([$subida->getId(), $enlace->leyenda, $enlace->enlace, $enlace->acortado]);
            if (!$resultado) {
                $bd->rollBack();
                return false;
            }
        }
        $sentencia = $bd->prepare("INSERT INTO acortadores_subidas(id_subida, id_acortador) VALUES (?, ?)");
        foreach ($subida->getAcortadores() as $acortador) {
            if (!$sentencia->execute([$subida->getId(), $acortador])) {
                $bd->rollBack();
                return false;
            };
        }
        $bd->commit();
        return $subida->getId();
    }
}
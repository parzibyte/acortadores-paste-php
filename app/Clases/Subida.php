<?php
/**
 * Created by PhpStorm.
 * User: parzibyte
 * Date: 12/11/2019
 * Time: 11:27 AM
 */

namespace Parzibyte\Clases;


use Parzibyte\Servicios\BD;
use PDO;

class Subida
{

    /**
     * @var $titulo string
     * @var $descripcion string
     * @var $enlaces array
     * @var $acortadores array
     */
    public $titulo, $descripcion, $token, $enlaces, $acortadores, $id, $fecha;

    /**
     * Subida constructor.
     * @param $titulo
     * @param $descripcion
     * @param $enlaces
     * @param $acortadores
     * @param string $token
     * @param string $fecha
     * @param null $id
     */
    public function __construct($titulo, $descripcion, $enlaces, $acortadores, $token = "", $fecha = "", $id = null)
    {
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->enlaces = $enlaces;
        $this->acortadores = $acortadores;
        $this->token = $token;
        $this->fecha = $fecha;
        $this->id = $id;
    }

    public static function porId($id)
    {
        $sentencia = BD::obtener()
            ->prepare("SELECT id, titulo, token, descripcion, fecha FROM subidas WHERE id = ? limit 1");
        $sentencia->execute([$id,]);
        $objeto = $sentencia->fetch(PDO::FETCH_OBJ);
        return new Subida($objeto->titulo, $objeto->descripcion, [], [], $objeto->token, $objeto->fecha, $objeto->id);
    }
    public static function porToken($token)
    {
        $sentencia = BD::obtener()
            ->prepare("SELECT id, titulo, token, descripcion, fecha FROM subidas WHERE token = ? limit 1");
        $sentencia->execute([$token,]);
        $objeto = $sentencia->fetch(PDO::FETCH_OBJ);
        return new Subida($objeto->titulo, $objeto->descripcion, [], [], $objeto->token, $objeto->fecha, $objeto->id);
    }

    public function conEnlaces($incluirOriginales = false)
    {
        $consulta = "SELECT id_subida, leyenda, enlace_acortado " . ($incluirOriginales ? ", enlace_original" : "") . " from enlaces_subidas WHERE id_subida = ?";
        $bd = BD::obtener();
        $sentencia = $bd->prepare($consulta);
        $sentencia->execute([$this->getId()]);
        $this->setEnlaces($sentencia->fetchAll(PDO::FETCH_OBJ));
        return $this;
    }

    public function conAcortadores($incluirOriginales = false)
    {
        $consulta = "SELECT id_acortador AS id FROM acortadores_subidas WHERE id_subida = ?";
        $bd = BD::obtener();
        $sentencia = $bd->prepare($consulta);
        $sentencia->execute([$this->getId()]);
        $this->setAcortadores($sentencia->fetchAll(PDO::FETCH_OBJ));
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Subida
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * @param mixed $titulo
     * @return Subida
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     * @return Subida
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnlaces()
    {
        return $this->enlaces;
    }

    /**
     * @param mixed $enlaces
     * @return Subida
     */
    public function setEnlaces($enlaces)
    {
        $this->enlaces = $enlaces;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAcortadores()
    {
        return $this->acortadores;
    }

    /**
     * @param mixed $acortadores
     * @return Subida
     */
    public function setAcortadores($acortadores)
    {
        $this->acortadores = $acortadores;
        return $this;
    }


}
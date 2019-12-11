<?php
/**
 * Created by PhpStorm.
 * User: parzibyte
 * Date: 12/11/2019
 * Time: 11:27 AM
 */

namespace Parzibyte\Clases;


class Subida
{

    /**
     * @var $titulo string
     * @var $descripcion string
     * @var $enlaces array
     * @var $acortadores array
     */
    private $titulo, $descripcion, $enlaces, $acortadores;

    /**
     * Subida constructor.
     * @param $titulo
     * @param $descripcion
     * @param $enlaces
     * @param $acortadores
     */
    public function __construct($titulo, $descripcion, $enlaces, $acortadores)
    {
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->enlaces = $enlaces;
        $this->acortadores = $acortadores;
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
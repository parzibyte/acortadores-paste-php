<?php

namespace Parzibyte\Controladores;


class ControladorEnlaces
{

    public static function index()
    {
        return view("enlaces/enlaces");
    }
    public static function agregar()
    {
        return view("enlaces/agregar_enlace");
    }
}

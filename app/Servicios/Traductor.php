<?php

namespace Parzibyte\Servicios;

use Exception;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\JsonFileLoader;

class Traductor
{
    /**
     * Devuelve el mensaje traducido para el idioma seleccionado
     * @param string $id
     * @param array $parametros
     * @return string
     * @throws Exception
     */
    public static function traducir($id, $parametros = [])
    {
        $traductor = new Translator(Comun::env("IDIOMA", "es"));
        $traductor->addLoader('json', new JsonFileLoader());
        $traductor->addResource('json', DIRECTORIO_APLICACION . "/resources/traducciones_en.json", "en");
        $traductor->addResource('json', DIRECTORIO_APLICACION . "/resources/traducciones_es.json", "es");
        $traductor->setFallbackLocales(["en"]);// Si no se encuentra el idioma, utilizamos es por defecto
        return $traductor->trans($id, $parametros);
    }
}

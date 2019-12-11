<?php
/**
 * Created by PhpStorm.
 * User: parzibyte
 * Date: 12/11/2019
 * Time: 12:49 PM
 */

namespace Parzibyte\Servicios;


use Exception;

class Acortador
{
    /**
     * @param $idsAcortadores
     * @param $enlace
     * @return string|null
     * @throws Exception
     */
    public static function acortarSegunDefinido($idsAcortadores, $enlace)
    {
        $enlaceAcortado = $enlace;
        foreach ($idsAcortadores as $idAcortador) {
            switch ($idAcortador) {
                case ID_ACORTADOR_ADFLY:
                    $enlaceAcortado = self::adfly($enlaceAcortado);
                    break;
                case ID_ACORTADOR_OUO:
                    $enlaceAcortado = self::ouoio($enlaceAcortado);
                    break;
                case ID_ACORTADOR_SHINK_ME:
                    $enlaceAcortado = self::shinkme($enlaceAcortado);
                    break;
                case ID_ACORTADOR_SHORTEST:
                    $enlaceAcortado = self::shortest($enlaceAcortado);
                    break;
                case ID_ACORTADOR_SHORTZON:
                    $enlaceAcortado = self::shortzoncom($enlaceAcortado);
                    break;
                case ID_ACORTADOR_SHRINKMEIO:
                    $enlaceAcortado = self::shrinkmeio($enlaceAcortado);
                    break;
                default:
                    throw new Exception("acortador con id " . $idAcortador . " no soportado");
            }
        }
        return $enlaceAcortado;
    }

    /**
     * @param $enlace
     * @return string|null
     * @throws Exception
     */
    public static function shrinkmeio($enlace)
    {
        $apiToken = Comun::env("KEY_SHRINKMEIO"); # Tu api Token
        $url = sprintf("https://shrinkme.io/api?api=%s&url=%s", $apiToken, urlencode($enlace));
        $respuesta = @file_get_contents($url);
        if (!$respuesta) {
            throw new Exception("Error acortando $enlace al comunicar con la API");
        }
        $datos = json_decode($respuesta);
        if (!property_exists($datos, "shortenedUrl") || empty($datos->shortenedUrl)) {
            throw new Exception("No se devolvió un enlace válido: $respuesta");
        }
        $acortado = $datos->shortenedUrl;
        if (preg_match('/^http[s]:\/\/shrinkme\.io\/\w+$/', $acortado) !== 1) {
            throw new Exception("Enlace inesperado al acortar con shrinkme.io el enlace $enlace: " . $acortado);
        }
        return $acortado;
    }

    /**
     * @param $enlace
     * @return string|null
     * @throws Exception
     */
    public static function shortzoncom($enlace)
    {
        #Coloca aquí tu token, si no tienes uno, consíguelo en https://shortzon.com/ref/parzibyte
        $apiToken = Comun::env("KEY_SHORTZONCOM"); # Tu api Token
        $url = sprintf("https://shortzon.com/api?api=%s&url=%s", $apiToken, urlencode($enlace));
        $respuesta = file_get_contents($url);
        if (!$respuesta) {
            throw new Exception("Error acortando $enlace al comunicar con la API");
        }
        $datos = json_decode($respuesta);
        if (!property_exists($datos, "shortenedUrl") || empty($datos->shortenedUrl)) {
            throw new Exception("No se devolvió un enlace válido: $respuesta");
        }
        $acortado = $datos->shortenedUrl;
        if (preg_match('/^http[s]:\/\/shrtz\.me\/\w+$/', $acortado) !== 1) {
            throw new Exception("Enlace inesperado al acortar con shortzon.com el enlace $enlace: " . $acortado);
        }
        return $acortado;
    }

    /**
     * @param $enlace
     * @return string|null
     * @throws Exception
     */
    public static function shortest($enlace)
    {
        $claveApi = Comun::env("TOKEN_SHORTEST");
        $datos = [
            'urlToShorten' => $enlace,
        ];
        $opciones = array(
            'http' => array(
                'header' => [
                    "Content-type: application/x-www-form-urlencoded",
                    "public-api-token: " . $claveApi,
                ],
                'method' => 'PUT',
                'content' => http_build_query($datos),
            ),
        );
        $contexto = stream_context_create($opciones);
        $resultado = @file_get_contents(
            'https://api.shorte.st/v1/data/url',
            false,
            $contexto);
        if ($resultado === false) {
            throw new Exception("Error al realizar la conexión para acortar $enlace con shorte.st!");
        }
        $respuestaDecodificada = json_decode($resultado);
        if ($respuestaDecodificada->status === "ok") {
            $acortado = $respuestaDecodificada->shortenedUrl;
            if (preg_match('/^http:\/\/\w+\.com\/\w+$/', $acortado) !== 1) {
                throw new Exception("Enlace inesperado al acortar con shorte.st: " . $acortado);
            }
            return $acortado;
        }
        throw new Exception(
            "Error en la respuesta del servidor al acortar $enlace con shorte.st!"
        );
    }

    /**
     * @param $enlace
     * @return string|null
     * @throws Exception
     */
    public static function shinkme($enlace)
    {
        $clave = Comun::env("KEY_SHINKME");
        $id = Comun::env("ID_SHINKME");
        $raw = file_get_contents(
            "https://shon.xyz/api/0/id/"
            . urlencode($id)
            . "/auth_token/"
            . urlencode($clave)
            . "?s=" . urlencode($enlace));
        if (false === $raw) {
            throw new Exception("Error obteniendo JSON de shink.me");
        }
        $respuesta = json_decode($raw);
        # Comprobar si no hay errores
        if (isset($respuesta->error) && $respuesta->error === 0) {
            # Ahora este es el acortado, pero vamos a ver si coincide con una expresión regular
            $acortado = "http://shink.me/" . $respuesta->hash;
            if (preg_match('/^http:\/\/shink\.me\/\w+$/', $acortado) !== 1)# En caso de que no coincida
                throw new Exception("Enlace inesperado al acortar con shink.me: " . $acortado);
            return $acortado; #En caso de que sí regresamos el acortado
        } else {
            throw new Exception("Error al acortar $enlace con shink.me. #Error: " . $respuesta->error);
        }
    }

    /**
     * @param $enlace
     * @return string|null
     * @throws Exception
     */
    public static function ouoio($enlace)
    {
        $claveApi = Comun::env("KEY_OUOIO");
        //Petición GET
        $acortado = @file_get_contents(
            "http://ouo.io/api/"
            . urlencode($claveApi)
            . "?s="
            . urlencode($enlace));
        // Comprobar si lo que obtuvimo
        // es un enlace válido utilizando una
        // expresión regular
        if (preg_match('/^(http|https):\/\/ouo\.io\/\w+$/', $acortado) !== 1)
            throw new Exception("Enlace inesperado al acortar con ouo.io: " . $acortado);
        return $acortado;
    }

    /**
     * @param $enlace
     * @return null|string
     * @throws Exception
     */
    public static function adfly($enlace)
    {
        $key = Comun::env("KEY_ADFLY");
        $uid = Comun::env("UID_ADFLY");
        $datos = [
            'key' => $key,
            "uid" => $uid,
            'advert_type' => 'interstitial',
            'domain' => 'q.gs',
            "url" => $enlace,
        ];
        $resultado = @file_get_contents('http://api.adf.ly/api.php?' . http_build_query($datos));
        if ($resultado === false) {
            throw new Exception(
                "Error al realizar la conexión para acortar $enlace con adf.ly!"
            );
        }
        /*
         * Si no respondieron con un JSON entonces
         * respondieron con la URL, y todas las cosas
         * están bien
         * */
        $respuestaDecodificada = json_decode($resultado);
        if ($respuestaDecodificada === null) {
            if (Comun::comienzaCon($resultado, "ERROR:")) {
                throw new Exception("El enlace $enlace no es válido: $resultado");
            }
            $acortado = $resultado;
            if (preg_match('/^http:\/\/q\.gs\/\w+$/', $acortado) !== 1) {
                throw new Exception("Enlace inesperado al acortar con adf.ly: " . $acortado);
            }
            return $acortado;
        } else {
            throw new Exception(
                "Error en la respuesta del servidor al acortar con adf.ly"
                . json_encode($respuestaDecodificada->errors, true)
                . "!"
            );
        }
    }
}
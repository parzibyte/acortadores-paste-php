<?php

use Parzibyte\Modelos\ModeloUsuarios;
use Parzibyte\Redirect;
use Parzibyte\Servicios\Comun;
use Parzibyte\Servicios\Seguridad;
use Parzibyte\Servicios\SesionService;
use Phroute\Phroute\RouteCollector;

$enrutador = new RouteCollector();

$enrutador->filter("logueado", function () {
    if (empty(SesionService::leer("correoUsuario"))) {
        Redirect::to("/login")->do();
        return true;
    }
    return null; # Cualquier otra cosa que NO sea null va a prevenir la ejecución de la ruta
});

$enrutador->filter("administrador", function () {
    $idUsuario = SesionService::leer("idUsuario");
    if (!$idUsuario || !ModeloUsuarios::uno($idUsuario)->administrador) {
        Redirect::to("/perfil/cambiar-password")->do();
        return true;
    }
    return null; # Cualquier otra cosa que NO sea null va a prevenir la ejecución de la ruta
});

$enrutador->filter("token_csrf", function () {
    $tieneTokenGet = isset($_GET["token_csrf"]);
    $tieneTokenPost = isset($_POST["token_csrf"]);
    if (!$tieneTokenGet && !$tieneTokenPost) exit("Falta el token CSRF");
    $tokenUsuario = $tieneTokenGet ? $_GET["token_csrf"] : $_POST["token_csrf"];
    if (!Seguridad::coincideTokenCSRF($tokenUsuario)) {
        echo "No coincide el token CSRF proporcionado";
        return false;
    } else {
        return null;
    }
});

$enrutador
    ->group(["before" => "logueado"], function (RouteCollector $enrutadorVistasPrivadas) {
        $enrutadorVistasPrivadas
            ->get("/perfil/cambiar-password", ["Parzibyte\Controladores\ControladorUsuarios", "perfilCambiarPassword"])
            ->post("/perfil/cambiar-password", ["Parzibyte\Controladores\ControladorUsuarios", "perfilGuardarPassword"])
            ->get("/logout", ["Parzibyte\Controladores\ControladorLogin", "logout"]);
    });
/**
 * Estos son métodos o vistas que solo puede ver un administrador
 * que obviamente está logueado
 */
$enrutador
    ->group(["before" => ["administrador"]], function (RouteCollector $enrutadorVistasPrivadas) {
        $enrutadorVistasPrivadas->group(["before" => ["token_csrf"]], function (RouteCollector $enrutadorToken) {
            $enrutadorToken
                ->post("/usuarios/eliminar", ["Parzibyte\Controladores\ControladorUsuarios", "eliminar"])
                ->post("/usuarios/guardar", ["Parzibyte\Controladores\ControladorUsuarios", "guardar"])
                ->post("/usuarios/guardarCambios", ["Parzibyte\Controladores\ControladorUsuarios", "guardarCambios"]);
        });
        $enrutadorVistasPrivadas
            ->get("/ajustes", ["Parzibyte\Controladores\ControladorAjustes", "index"])
            ->get("/usuarios", ["Parzibyte\Controladores\ControladorUsuarios", "index"])
            ->get("/usuarios/agregar", ["Parzibyte\Controladores\ControladorUsuarios", "agregar"])
            ->get("/usuarios/editar/{idUsuario}", ["Parzibyte\Controladores\ControladorUsuarios", "formularioEditar"])
            ->get("/usuarios/eliminar/{idUsuario}", ["Parzibyte\Controladores\ControladorUsuarios", "confirmarEliminacion"]);

        # Rutas de enlaces
        $enrutadorVistasPrivadas->get("enlaces", ["Parzibyte\Controladores\ControladorEnlaces", "index"]);
        $enrutadorVistasPrivadas->get("acortadores_disponibles", ["Parzibyte\Controladores\ControladorSubidas", "obtenerAcortadoresDisponibles"]);
        $enrutadorVistasPrivadas->get("enlaces/agregar", ["Parzibyte\Controladores\ControladorEnlaces", "agregar"]);
        $enrutadorVistasPrivadas->get("subidas", ["Parzibyte\Controladores\ControladorSubidas", "verSubidas"]);
        $enrutadorVistasPrivadas->get("subidas/json", ["Parzibyte\Controladores\ControladorSubidas", "listadoDeSubidas"]);
        $enrutadorVistasPrivadas->get("subidas/agregar", ["Parzibyte\Controladores\ControladorSubidas", "formularioAgregar"]);
        $enrutadorVistasPrivadas->get("subida/{idSubida}/editar", ["Parzibyte\Controladores\ControladorSubidas", "editarSubida"]);
        $enrutadorVistasPrivadas->get("subida/{idSubida}", ["Parzibyte\Controladores\ControladorSubidas", "detallesDeSubida"]);
        $enrutadorVistasPrivadas->delete("subida/{idSubida}", ["Parzibyte\Controladores\ControladorSubidas", "eliminarSubida"]);
        $enrutadorVistasPrivadas->post("subida", ["Parzibyte\Controladores\ControladorSubidas", "agregarSubida"]);
        $enrutadorVistasPrivadas->put("subida", ["Parzibyte\Controladores\ControladorSubidas", "actualizarSubida"]);
    });

$enrutador->post("/login", ["Parzibyte\Controladores\ControladorLogin", "login"]);
$enrutador->get("/login", ["Parzibyte\Controladores\ControladorLogin", "index"]);
$enrutador->get("/", ["Parzibyte\Controladores\ControladorLogin", "index"]);
# Cuando quieren resetear
$enrutador->get("/usuarios/solicitar-nueva-password", ["Parzibyte\Controladores\ControladorUsuarios", "formularioSolicitarNuevaPassword"]);
$enrutador->post("/usuarios/solicitar-nueva-password", ["Parzibyte\Controladores\ControladorUsuarios", "solicitarNuevaPassword"]);
# Cuando ya les llegó el correo
$enrutador->get("/usuarios/restablecer-password/{token}", ["Parzibyte\Controladores\ControladorUsuarios", "formularioRestablecerPassword"]);
$enrutador->post("/usuarios/restablecer-password", ["Parzibyte\Controladores\ControladorUsuarios", "restablecerPassword"]);
if (PERMITIR_REGISTRO_USUARIOS) {
    $enrutador->get("/registro", ["Parzibyte\Controladores\ControladorUsuarios", "registrar"]);
    $enrutador->post("/usuarios/registro", ["Parzibyte\Controladores\ControladorUsuarios", "registro"]);
    $enrutador->get("/usuarios/verificar/{token}", ["Parzibyte\Controladores\ControladorUsuarios", "verificar"]);
    # Reenviar correo de registro
    $enrutador->get("/usuarios/reenviar-correo", ["Parzibyte\Controladores\ControladorUsuarios", "solicitarReenvioCorreo"]);
    $enrutador->post("/usuarios/reenviar-correo", ["Parzibyte\Controladores\ControladorUsuarios", "reenviarCorreo"]);
}
# Por último, cualquier ruta supone que quieren ver la subida con token
$enrutador->get("/{token}", ["Parzibyte\Controladores\ControladorSubidas", "verSubidaPublicamente"]);
return $enrutador;

<?php
// Servicio para obtener los datos de sucursal por id_estacion
class SucursalService {
    public static function getDatosSucursal($idEstacion) {
        if (!$idEstacion) return [];
        $url = 'http://localhost/glpi/front/mantenimiento/config/get_sucursales_detalle.php?id=' . urlencode($idEstacion);
        $json = @file_get_contents($url);
        $datos = json_decode($json, true);
        if (!is_array($datos) || count($datos) === 0) return [];
        return $datos[0];
    }
}

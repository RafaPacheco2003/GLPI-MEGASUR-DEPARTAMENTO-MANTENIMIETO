<?php

/**
 * Clase responsable únicamente de la conexión a la base de datos productos
 * Principio SRP: Una sola responsabilidad - manejar la conexión
 */
class MantenimientoConnection
{
    private static $connection = null;
    
    /**
     * Configuración de la base de datos externa
     */
    private static $config = [
        'host'     => 'localhost',
        'user'     => 'root',
        'password' => '',
        'database' => 'mantenimiento',
        'port'     => 3306
    ];
    
    /**
     * Obtener conexión singleton a la base de datos productos
     */
    public static function getConnection()
    {
        if (self::$connection === null) {
            self::$connection = new mysqli(
                self::$config['host'],
                self::$config['user'],
                self::$config['password'],
                self::$config['database'],
                self::$config['port']
            );
            
            if (self::$connection->connect_error) {
                throw new Exception("Error de conexión a productos: " . self::$connection->connect_error);
            }
            
            self::$connection->set_charset('utf8mb4');
        }
        
        return self::$connection;
    }
    
    /**
     * Cerrar conexión
     */
    public static function close()
    {
        if (self::$connection) {
            self::$connection->close();
            self::$connection = null;
        }
    }
    
    /**
     * Configurar parámetros de conexión
     */
    public static function setConfig($config)
    {
        self::$config = array_merge(self::$config, $config);
    }
}

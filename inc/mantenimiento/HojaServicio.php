<?php

require_once 'MantenimientoConnection.php';

class HojaServicio {
    private $db;

    public function __construct() {
        $this->db = MantenimientoConnection::getConnection();
        if (!$this->db) {
            throw new Exception("No se pudo obtener la conexión a la base de datos");
        }
    }

    public function crearHojaServicio($data) {
        $logfile = __DIR__ . '/../../ajax/mantenimiento/debug_create_hoja_servicio.log';
        file_put_contents($logfile, date('c') . " - [HojaServicio] INICIO crearHojaServicio\n", FILE_APPEND);
        $required = ['id_estacion','id_servicio','id_material','folio','serie','fecha_inicio','fecha_fin','descripcion','id_entregado','id_recibido','firma_entregado','firma_recibido','tipo_servicio'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                file_put_contents($logfile, date('c') . " - [HojaServicio] FALTA CAMPO: $field\n", FILE_APPEND);
                throw new Exception("Falta el campo requerido: $field");
            }
        }

        try {
            file_put_contents($logfile, date('c') . " - [HojaServicio] Iniciando transacción\n", FILE_APPEND);
            $this->db->begin_transaction();
            $query = "INSERT INTO hoja_servicio (id_estacion, id_servicio, id_material, folio, serie, fecha_inicio, fecha_fin, descripcion, id_entregado, id_recibido, firma_entregado, firma_recibido, tipo_servicio) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $this->db->error);
            }

            // Definir tipos para bind_param: id_material ahora es string
            $stmt->bind_param(
                "iisssssssisss",
                $data['id_estacion'],
                $data['id_servicio'],
                $data['id_material'],
                $data['folio'],
                $data['serie'],
                $data['fecha_inicio'],
                $data['fecha_fin'],
                $data['descripcion'],
                $data['id_entregado'],
                $data['id_recibido'],
                $data['firma_entregado'],
                $data['firma_recibido'],
                $data['tipo_servicio']
            );

            file_put_contents($logfile, date('c') . " - [HojaServicio] Ejecutando query\n", FILE_APPEND);
            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            $insertId = $stmt->insert_id;
            if (!$insertId) {
                throw new Exception("La inserción no generó un ID");
            }

            $this->db->commit();
            file_put_contents($logfile, date('c') . " - [HojaServicio] EXITO insert id: $insertId\n", FILE_APPEND);
            // Imprimir respuesta de éxito para el frontend
            echo json_encode([
                'success' => true,
                'message' => 'Servicio creado exitosamente',
                'id' => $insertId
            ]);
            exit;
        } catch (Exception $e) {
            $this->db->rollback();
            file_put_contents($logfile, date('c') . " - [HojaServicio] ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
            throw $e;
        }
    }


    /**
     * Busca todas las hojas de servicio por el id_servicio (FK)
     * @param int $id_servicio
     * @return array
     */
    public function getById_servicio($id_servicio){
        $query = "SELECT * FROM hoja_servicio WHERE id_servicio = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id_servicio);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Busca una hoja de servicio por su id único (PK)
     * @param int $id
     * @return array|null
     */
    public function getById($id){
        $query = "SELECT * FROM hoja_servicio WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        return $result->fetch_assoc();
    }


    
}

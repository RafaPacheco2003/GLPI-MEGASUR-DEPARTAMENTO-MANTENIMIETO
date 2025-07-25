<?php

if (!class_exists('MantenimientoConnection')) {
    require_once __DIR__ . '/MantenimientoConnection.php';
}

/**
 * Clase responsable de manejar las operaciones CRUD para la tabla programacion
 */
class ProgramacionManager
{
    private $db;
    private $itemsPerPage = 3; // Número de items por página

    /**
     * Constructor - obtiene la conexión a la base de datos
     */
    public function __construct()
    {
        try {
            $this->db = MantenimientoConnection::getConnection();
            if (!$this->db) {
                throw new Exception("No se pudo obtener la conexión a la base de datos");
            }
        } catch (Exception $e) {
            error_log("Error al inicializar ProgramacionManager: " . $e->getMessage());
            throw $e;
        }
    }



    /**
     * Crear una nueva programación
     */
    public function create($data)
    {



        // Iniciar transacción
        $this->db->begin_transaction();

        $query = "INSERT INTO programacion (
                nombre_empresa,
                nombre_programacion,
                fecha_emision,
                id_elaboro,
                firma_elaboro
            ) VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $this->db->error);
        }

        // Handle nullable firma_elaboro
        $firma = isset($data['firma_elaboro']) && !empty($data['firma_elaboro'])
            ? $data['firma_elaboro']
            : null;

        // Log data being inserted
        error_log("Intentando insertar programación con: " . json_encode([
            'nombre_empresa' => $data['nombre_empresa'],
            'nombre_programacion' => $data['nombre_programacion'],
            'fecha_emision' => $data['fecha_emision'],
            'id_elaboro' => $data['id_elaboro']
        ]));

        $bindResult = $stmt->bind_param(
            "sssis",
            $data['nombre_empresa'],
            $data['nombre_programacion'],
            $data['fecha_emision'],
            $data['id_elaboro'],
            $firma
        );

        if (!$bindResult) {
            throw new Exception("Error al vincular parámetros: " . $stmt->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }

        $insertId = $stmt->insert_id;
        if (!$insertId) {
            throw new Exception("La inserción no generó un ID");
        }

        // Confirmar la transacción
        $this->db->commit();

        error_log("Programación creada exitosamente con ID: " . $insertId);
        return $insertId;


    }

    /**
     * Obtener todas las programaciones
     * @return array Array de programaciones
     */
    public function getAll()
    {
        $query = "SELECT * FROM programacion ORDER BY fecha_emision DESC";
        $result = $this->db->query($query);

        if (!$result) {
            throw new Exception("Error al obtener programaciones: " . $this->db->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtener una programación por ID
     * @param int $id ID de la programación
     * @return array|null Datos de la programación o null si no existe
     */
    public function getById($id)
    {
        $query = "SELECT * FROM programacion WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error al obtener la programación: " . $this->db->error);
        }

        return $result->fetch_assoc();
    }

    /**
     * Buscar programaciones por nombre de empresa
     * @param string $empresa Nombre de la empresa a buscar
     * @return array Array de programaciones que coinciden
     */
    public function searchProgramacionesByEmpresa($empresa): array
    {
        $query = "SELECT * FROM programacion WHERE nombre_empresa LIKE ? ORDER BY fecha_emision DESC";

        $empresa = "%{$empresa}%";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $empresa);
        $stmt->execute();

        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error al buscar programaciones: " . $this->db->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }



    /**
     * Marcar como revisada una programación
     * 
     * @param int $id ID del registro en la tabla programacion
     * @param int $idRevisor ID del usuario que revisó
     * @param string $firmaRevisor Nombre o ruta de la firma del revisor
     * @return bool Devuelve true si la actualización fue exitosa, false si falló
     */
    public function markAsReviewed($id, $idRevisor)
    {
        // Consulta SQL con marcadores ? para los valores que se enviarán después
        $query = "UPDATE programacion
              SET estado = 1,               -- Se cambia el estado a 'revisado'
                  id_reviso = ?            -- Se asigna el ID del revisor
              WHERE id = ?";                // Solo se actualiza la fila con el ID indicado

        // Prepara la consulta para evitar inyecciones SQL
        $stmt = $this->db->prepare($query);

        // Verifica si la preparación de la consulta fue exitosa
        if (!$stmt) {
            // Si falla, lanza una excepción con el error del servidor MySQL
            throw new Exception("Error al preparar la consulta: " . $this->db->error);
        }

        // Enlaza los parámetros a la consulta:
        // "i" = entero, "s" = string → entonces: entero, string, entero
        $stmt->bind_param("ii", $idRevisor, $id);

        // Ejecuta la consulta y devuelve true si fue exitosa, false si falló
        return $stmt->execute();
    }


    /**
     * Buscar programaciones por nombre de programación
     * @param string $nombre Nombre de la programación a buscar
     * @return array Array de programaciones que coinciden
     */
    public function searchProgramacionesByNombre($nombre)
    {
        $query = "SELECT * FROM programacion WHERE nombre_programacion LIKE ? ORDER BY fecha_emision DESC";

        $nombre = "%{$nombre}%";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();

        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error al buscar programaciones: " . $this->db->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtener programaciones por estado
     * @param int $estado Estado de la programación (0: Pendiente, 1: Revisado, 2: Autorizado)
     * @return array Array de programaciones en ese estado
     */
    public function getProgramacionesByEstado($estado)
    {
        $query = "SELECT * FROM programacion WHERE estado = ? ORDER BY fecha_emision DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $estado);
        $stmt->execute();

        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error al obtener programaciones por estado: " . $this->db->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtener programaciones por rango de fechas
     * @param string $fechaInicio Fecha inicial (YYYY-MM-DD)
     * @param string $fechaFin Fecha final (YYYY-MM-DD)
     * @return array Array de programaciones en ese rango
     */
    public function getProgramacionesByDateRange($fechaInicio, $fechaFin)
    {
        $query = "SELECT * FROM programacion WHERE fecha_emision BETWEEN ? AND ? ORDER BY fecha_emision DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $fechaInicio, $fechaFin);
        $stmt->execute();

        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error al obtener programaciones por rango de fechas: " . $this->db->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtener conteo de programaciones por estado
     * @return array Array con conteo por estado
     */
    public function getProgramacionesCountByEstado()
    {
        $query = "SELECT estado, COUNT(*) as total 
                 FROM programacion 
                 GROUP BY estado";

        $result = $this->db->query($query);
        if (!$result) {
            throw new Exception("Error al obtener conteo de programaciones: " . $this->db->error);
        }

        $conteo = [];
        while ($row = $result->fetch_assoc()) {
            $conteo[$row['estado']] = $row['total'];
        }

        return $conteo;
    }

    /**
     * Actualizar una programación
     */
    public function update($id, $data)
    {
        $query = "UPDATE programacion SET 
                    nombre_empresa = ?,
                    nombre_programacion = ?,
                    fecha_emision = ?,
                    id_elaboro = ?,
                    firma_elaboro = ?
                 WHERE id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param(
            "sssssi",
            $data['nombre_empresa'],
            $data['nombre_programacion'],
            $data['fecha_emision'],
            $data['id_elaboro'],
            $data['firma_elaboro'],
            $id
        );

        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar programación: " . $this->db->error);
        }

        return $stmt->affected_rows > 0;
    }

    /**
     * Eliminar una programación
     */
    public function delete($id)
    {
        $query = "DELETE FROM programacion WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar programación: " . $this->db->error);
        }

        return $stmt->affected_rows > 0;
    }

    /**
     * Actualizar estado de una programación
     */
    public function updateEstado($id, $estado)
    {
        $query = "UPDATE programacion SET estado = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $estado, $id);

        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar estado: " . $this->db->error);
        }

        return $stmt->affected_rows > 0;
    }

    /**
     * Actualizar firma de revisión
     */
    public function setRevisor($id, $idRevisor, $firma)
    {
        $query = "UPDATE programacion 
                 SET id_reviso = ?, 
                     firma_reviso = ?,
                     estado = 1
                 WHERE id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("isi", $idRevisor, $firma, $id);

        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar revisor: " . $this->db->error);
        }

        return $stmt->affected_rows > 0;
    }

    /**
     * Actualizar firma de autorización
     */
    public function setAutorizador($id, $idAutoriza, $firma)
    {
        $query = "UPDATE programacion 
                 SET id_autorizo = ?, 
                     firma_autorizo = ?,
                     estado = 2
                 WHERE id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("isi", $idAutoriza, $firma, $id);

        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar autorizador: " . $this->db->error);
        }

        return $stmt->affected_rows > 0;
    }

    /**
     * Obtener programaciones con paginación
     * @param int $page Número de página actual
     * @return array Array con las programaciones y metadata de paginación
     */
    public function getAllPaginated($page = 1)
    {
        // Calcular el offset
        $offset = ($page - 1) * $this->itemsPerPage;

        // Obtener el total de registros
        $countQuery = "SELECT COUNT(*) as total FROM programacion";
        $countResult = $this->db->query($countQuery);
        $totalItems = $countResult->fetch_assoc()['total'];

        // Calcular el total de páginas
        $totalPages = ceil($totalItems / $this->itemsPerPage);

        // Obtener los registros de la página actual
        $query = "SELECT * FROM programacion ORDER BY fecha_emision DESC LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $this->itemsPerPage, $offset);
        $stmt->execute();

        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error al obtener programaciones: " . $this->db->error);
        }

        $programaciones = [];
        while ($row = $result->fetch_assoc()) {
            $programaciones[] = $row;
        }

        // Retornar datos y metadata de paginación
        return [
            'items' => $programaciones,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems
        ];
    }

    /**
     * Obtener los valores enum de nombre_programacion
     * @return array Array con los valores del enum
     */
    public function getNombreProgramacionEnum()
    {
        $query = "SHOW COLUMNS FROM programacion WHERE Field = 'nombre_programacion'";
        $result = $this->db->query($query);

        if (!$result) {
            throw new Exception("Error al obtener enum de nombre_programacion: " . $this->db->error);
        }

        $row = $result->fetch_assoc();
        if (!$row) {
            return [];
        }

        // Extraer valores del enum del formato ENUM('valor1','valor2',...)
        $type = $row['Type'];
        preg_match("/^enum\((.*)\)$/", $type, $matches);
        if (!isset($matches[1])) {
            return [];
        }

        // Convertir la cadena en array y limpiar las comillas
        $enumValues = array_map(function ($value) {
            return trim($value, "'");
        }, explode(',', $matches[1]));

        return $enumValues;
    }
}

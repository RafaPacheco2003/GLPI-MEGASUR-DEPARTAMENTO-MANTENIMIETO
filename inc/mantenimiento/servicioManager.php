<?php


require_once 'MantenimientoConnection.php';

class ServicioManager
{
    private $connection;


    private $itemsPerPage = 3; // Número de items por página
    /**
     * Constructor que inicializa la conexión a la base de datos
     */

    public function __construct()
    {
        $this->connection = MantenimientoConnection::getConnection();
    }
    /**
     * Crear un nuevo servicio de tipo servidor y red
     */
    public function crearServicioServidorRed($data)
    {
        try {
            // Validar que id_programacion sea válido
            if (!isset($data['id_programacion']) || !is_numeric($data['id_programacion'])) {
                throw new Exception("ID de programación no válido.");
            }

            // Iniciar transacción
            $this->connection->begin_transaction();


            // Permitir valores nulos para campos opcionales
            $serie_id = isset($data['serie_id']) && $data['serie_id'] !== '' ? $data['serie_id'] : null;
            $estatus = isset($data['estatus']) && $data['estatus'] !== '' ? $data['estatus'] : null;
            $serie_folio_hoja_servicio = isset($data['serie_folio_hoja_servicio']) && $data['serie_folio_hoja_servicio'] !== '' ? $data['serie_folio_hoja_servicio'] : null;
            $id_estacion = isset($data['id_estacion']) && $data['id_estacion'] !== '' ? $data['id_estacion'] : null;
            $quien = isset($data['quien']) && $data['quien'] !== '' ? $data['quien'] : null;
            $id_programacion = isset($data['id_programacion']) && $data['id_programacion'] !== '' ? $data['id_programacion'] : null;

            $query = "INSERT INTO servicio (
            fecha_inicio,
            fecha_final,
            servidor_site,
            serie_id,
            estatus,
            afectacion,
            serie_folio_hoja_servicio,
            id_estacion,
            quien,
            id_programacion,
            estado
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";

            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $this->connection->error);
            }

            // Log de los datos a insertar
            error_log("Insertando servicio con: " . json_encode([
                'fecha_inicio' => $data['fecha_inicio'],
                'fecha_final' => $data['fecha_final'],
                'servidor_site' => $data['servidor_site'],
                'serie_id' => $serie_id,
                'estatus' => $estatus,
                'afectacion' => $data['afectacion'],
                'serie_folio_hoja_servicio' => $serie_folio_hoja_servicio,
                'id_estacion' => $id_estacion,
                'quien' => $quien,
                'id_programacion' => $id_programacion
            ]));

            $bindResult = $stmt->bind_param(
                "sssssssssi",
                $data['fecha_inicio'],
                $data['fecha_final'],
                $data['servidor_site'],
                $serie_id,
                $estatus,
                $data['afectacion'],
                $serie_folio_hoja_servicio,
                $id_estacion,
                $quien,
                $id_programacion
            );

            if (!$bindResult) {
                throw new Exception("Error al vincular parámetros: " . $stmt->error);
            }

            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            $insertId = $stmt->insert_id;
            if (!$insertId) {
                throw new Exception("La inserción no generó un ID.");
            }

            // Confirmar transacción
            $this->connection->commit();

            error_log("Servicio creado exitosamente con ID: " . $insertId);
            return $insertId;

        } catch (Exception $e) {
            $this->connection->rollback();
            error_log("Error al crear servicio: " . $e->getMessage());
            throw $e;
        }
    }


    /**
     * Método para obtener los servicios de una programación específica
     * 
     * @param int $id_programacion ID de la programación
     * @return array Lista de servicios asociados a la programación
     */
    public function getServiciosByProgramacion($id_programacion, $pagina = 1)
    {
        $offset = ($pagina - 1) * $this->itemsPerPage;

        $query = "SELECT * FROM servicio WHERE id_programacion = ? ORDER BY fecha_inicio ASC LIMIT ? OFFSET ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("iii", $id_programacion, $this->itemsPerPage, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $servicios = [];
        while ($row = $result->fetch_assoc()) {
            $servicios[] = $row;
        }

        $stmt->close();
        return $servicios;
    }


    public function getServiciosByProgramacion2($id_programacion, )
    {
        $query = "SELECT * FROM servicio WHERE id_programacion = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id_programacion);
        $stmt->execute();
        $result = $stmt->get_result();

        $servicios = [];
        while ($row = $result->fetch_assoc()) {
            $servicios[] = $row;
        }

        $stmt->close();
        return $servicios;
    }


    /**
     * Método para obtener el número total de servicios
     * 
     * @return int Número total de servicios
     */
    public function getCountServiciosByProgramacion($id_programacion)
    {
        $query = "SELECT COUNT(*) as total FROM servicio WHERE id_programacion = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id_programacion);
        $stmt->execute();
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();
        $total = $row['total'];

        $stmt->close();
        return $total;
    }


    /**
     * Método para obtener los valores ENUM del campo 'afectacion' en la tabla 'servicio'
     *
     * @return array Lista de valores posibles del ENUM
     */
    public function getAfectacionEnum(): array
    {
        $query = "SHOW COLUMNS FROM servicio WHERE Field = 'afectacion'";
        $result = $this->connection->query($query);

        if (!$result) {
            throw new Exception("Error al obtener enum de afectacion: " . $this->connection->error);
        }

        $row = $result->fetch_assoc();
        $type = $row['Type'];

        // Extraer los valores del ENUM
        if (preg_match("/^enum\((.*)\)$/", $type, $matches)) {
            $enumValues = array_map(function ($value) {
                return trim($value, "'");
            }, explode(',', $matches[1]));

            return $enumValues;
        }

        return [];
    }



    /**
     * Buscar servicios por servidor_site
     * @param string $servidor_site Término de búsqueda
     * @return array Lista de servicios que coinciden con el término de búsqueda
     */

    public function searchServiciosByServidor_site($servidor_site): array
    {
        $query = "SELECT * FROM servicio WHERE servidor_site LIKE ? ORDER BY fecha_inicio DESC";
        $searchTerm = "%" . $servidor_site . "%";

        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        $servicios = [];
        while ($row = $result->fetch_assoc()) {
            $servicios[] = $row;
        }

        $stmt->close();
        return $servicios;
    }


    /**
     * Obtener un servicio por su ID
     * 
     * @param int $id ID del servicio
     * @return array|null Datos del servicio o null si no existe
     */
    public function getById($id)
    {
        $query = "SELECT * FROM servicio WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        return $result->fetch_assoc();
    }
}
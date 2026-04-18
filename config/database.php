<?php

/**
 * Configuración de Base de Datos
 * 
 * IMPORTANTE: Este archivo es un placeholder.
 * Tu compañero debe implementar la conexión real a la base de datos aquí.
 * 
 * Tabla de Publicaciones (estructura sugerida):
 * 
 * CREATE TABLE publicaciones (
 *     id INT PRIMARY KEY AUTO_INCREMENT,
 *     vendedor_id INT NOT NULL,
 *     titulo VARCHAR(200) NOT NULL,
 *     descripcion LONGTEXT NOT NULL,
 *     precio DECIMAL(10, 2) NOT NULL,
 *     disponibilidad INT NOT NULL DEFAULT 1,
 *     categoria VARCHAR(50) NOT NULL,
 *     condicion VARCHAR(50) NOT NULL,
 *     activa BOOLEAN DEFAULT TRUE,
 *     fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
 *     fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 *     FOREIGN KEY (vendedor_id) REFERENCES usuarios(id),
 *     INDEX idx_vendedor (vendedor_id),
 *     INDEX idx_categoria (categoria),
 *     INDEX idx_activa (activa)
 * );
 */

class Database
{
    private static $instance = null;
    private $connection;
    private $config;

    private function __construct()
    {
        // Cargar configuración del ambiente
        $this->config = [
            'host' => getenv('DB_HOST') ?: 'localhost',
            'port' => getenv('DB_PORT') ?: 3306,
            'database' => getenv('DB_NAME') ?: 'marketvesitario',
            'user' => getenv('DB_USER') ?: 'root',
            'password' => getenv('DB_PASSWORD') ?: '',
            'charset' => 'utf8mb4'
        ];

        $this->connect();
    }

    /**
     * Obtener instancia singleton de la base de datos
     * 
     * @return Database
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Conectar a la base de datos
     * 
     * @return void
     * @throws \PDOException
     */
    private function connect()
    {
        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $this->config['host'],
                $this->config['port'],
                $this->config['database'],
                $this->config['charset']
            );

            $this->connection = new \PDO(
                $dsn,
                $this->config['user'],
                $this->config['password'],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (\PDOException $e) {
            throw new \PDOException("Error de conexión a BD: " . $e->getMessage());
        }
    }

    /**
     * Obtener la conexión PDO
     * 
     * @return \PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Preparar una consulta
     * 
     * @param string $query Consulta SQL
     * @return \PDOStatement
     */
    public function prepare($query)
    {
        return $this->connection->prepare($query);
    }

    /**
     * Ejecutar una consulta
     * 
     * @param string $query Consulta SQL
     * @param array $params Parámetros
     * @return \PDOStatement
     */
    public function query($query, $params = [])
    {
        $stmt = $this->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Obtener una fila
     * 
     * @param string $query Consulta SQL
     * @param array $params Parámetros
     * @return array|null
     */
    public function fetchOne($query, $params = [])
    {
        return $this->query($query, $params)->fetch();
    }

    /**
     * Obtener todas las filas
     * 
     * @param string $query Consulta SQL
     * @param array $params Parámetros
     * @return array
     */
    public function fetchAll($query, $params = [])
    {
        return $this->query($query, $params)->fetchAll();
    }

    /**
     * Insertar datos
     * 
     * @param string $query Consulta SQL
     * @param array $params Parámetros
     * @return int ID insertado
     */
    public function insert($query, $params = [])
    {
        $this->query($query, $params);
        return $this->connection->lastInsertId();
    }

    /**
     * Obtener el ID del último insert
     * 
     * @return string
     */
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Iniciar una transacción
     * 
     * @return void
     */
    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    /**
     * Confirmar transacción
     * 
     * @return void
     */
    public function commit()
    {
        $this->connection->commit();
    }

    /**
     * Revertir transacción
     * 
     * @return void
     */
    public function rollBack()
    {
        $this->connection->rollBack();
    }
}

// Ejemplo de uso:
// $db = Database::getInstance();
// $result = $db->fetchOne("SELECT * FROM publicaciones WHERE id = ?", [1]);

<?php

namespace App\Models;

class Publication
{
    /**
     * Conectar a la base de datos
     * Esta será implementada por tu compañero
     * 
     * @return mixed Conexión PDO o similar
     */
    private function getConnection()
    {
        // Placeholder: tu compañero implementará la conexión real a BD
        // return new \PDO(DSN);
        // Por ahora retornamos null y lanzamos excepción
        throw new \Exception("Conexión a base de datos no implementada");
    }

    /**
     * Obtener una publicación por ID
     * 
     * @param int $id ID de la publicación
     * @return array|null Datos de la publicación o null
     */
    public function getById($id)
    {
        try {
            $connection = $this->getConnection();
            
            $query = "SELECT * FROM publicaciones WHERE id = ? AND activa = 1";
            $stmt = $connection->prepare($query);
            $stmt->execute([$id]);
            
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception("Error al obtener publicación: " . $e->getMessage());
        }
    }

    /**
     * Actualizar una publicación
     * 
     * @param int $id ID de la publicación
     * @param array $data Datos a actualizar
     * @return bool true si se actualizó correctamente
     */
    public function update($id, $data)
    {
        try {
            $connection = $this->getConnection();
            
            // Construir dinámicamente la consulta UPDATE
            $updateFields = [];
            $values = [];
            
            foreach ($data as $key => $value) {
                $updateFields[] = "{$key} = ?";
                $values[] = $value;
            }
            
            $values[] = $id; // Para la condición WHERE
            
            $query = "UPDATE publicaciones SET " . implode(", ", $updateFields) . " WHERE id = ?";
            
            $stmt = $connection->prepare($query);
            $result = $stmt->execute($values);
            
            return $result && $stmt->rowCount() > 0;
        } catch (\Exception $e) {
            throw new \Exception("Error al actualizar publicación: " . $e->getMessage());
        }
    }

    /**
     * Obtener todas las publicaciones de un vendedor
     * 
     * @param int $vendedorId ID del vendedor
     * @return array Array de publicaciones
     */
    public function getByVendedor($vendedorId)
    {
        try {
            $connection = $this->getConnection();
            
            $query = "SELECT * FROM publicaciones WHERE vendedor_id = ? AND activa = 1 ORDER BY fecha_actualizacion DESC";
            $stmt = $connection->prepare($query);
            $stmt->execute([$vendedorId]);
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception("Error al obtener publicaciones: " . $e->getMessage());
        }
    }

    /**
     * Eliminar una publicación (soft delete)
     * 
     * @param int $id ID de la publicación
     * @return bool true si se eliminó correctamente
     */
    public function delete($id)
    {
        try {
            $connection = $this->getConnection();
            
            $query = "UPDATE publicaciones SET activa = 0, fecha_actualizacion = ? WHERE id = ?";
            $stmt = $connection->prepare($query);
            $result = $stmt->execute([date('Y-m-d H:i:s'), $id]);
            
            return $result && $stmt->rowCount() > 0;
        } catch (\Exception $e) {
            throw new \Exception("Error al eliminar publicación: " . $e->getMessage());
        }
    }

    /**
     * Buscar publicaciones por categoría
     * 
     * @param string $categoria Categoría a buscar
     * @return array Array de publicaciones
     */
    public function getByCategoria($categoria)
    {
        try {
            $connection = $this->getConnection();
            
            $query = "SELECT * FROM publicaciones WHERE categoria = ? AND activa = 1 ORDER BY fecha_actualizacion DESC";
            $stmt = $connection->prepare($query);
            $stmt->execute([$categoria]);
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception("Error al buscar publicaciones: " . $e->getMessage());
        }
    }

    /**
     * Validar que el usuario tenga permisos para editar esta publicación
     * 
     * @param int $publicacionId ID de la publicación
     * @param int $usuarioId ID del usuario
     * @return bool true si tiene permisos
     */
    public function userCanEdit($publicacionId, $usuarioId)
    {
        try {
            $connection = $this->getConnection();
            
            $query = "SELECT COUNT(*) as count FROM publicaciones WHERE id = ? AND vendedor_id = ?";
            $stmt = $connection->prepare($query);
            $stmt->execute([$publicacionId, $usuarioId]);
            
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (\Exception $e) {
            throw new \Exception("Error al validar permisos: " . $e->getMessage());
        }
    }
}

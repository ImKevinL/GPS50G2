<?php

namespace App\Controllers;

use App\Models\Publication;

class PublicationController
{
    private $publication;

    public function __construct()
    {
        $this->publication = new Publication();
    }

    /**
     * Mostrar formulario para editar una publicación
     * 
     * @param int $publicationId ID de la publicación a editar
     * @return void
     */
    public function showEditForm($publicationId)
    {
        try {
            $publicationData = $this->publication->getById($publicationId);
            
            if (!$publicationData) {
                $this->handleError("Publicación no encontrada", 404);
                return;
            }

            // Verificar que el usuario sea el propietario (esto se validará con sesión)
            // Por ahora solo obtenemos los datos

            include __DIR__ . '/../Views/edit-publication.php';
        } catch (\Exception $e) {
            $this->handleError($e->getMessage(), 500);
        }
    }

    /**
     * Procesar la actualización de una publicación
     * 
     * @return void
     */
    public function updatePublication()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->handleError("Método no permitido", 405);
            return;
        }

        try {
            // Validar datos
            $errors = $this->validatePublicationData($_POST);
            
            if (!empty($errors)) {
                $this->handleError("Datos inválidos: " . implode(", ", $errors), 400);
                return;
            }

            $publicationId = (int)$_POST['publication_id'] ?? null;
            
            if (!$publicationId) {
                $this->handleError("ID de publicación inválido", 400);
                return;
            }

            // Preparar datos para actualizar
            $updateData = [
                'titulo' => trim($_POST['titulo'] ?? ''),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'precio' => (float)($_POST['precio'] ?? 0),
                'disponibilidad' => (int)($_POST['disponibilidad'] ?? 0),
                'categoria' => trim($_POST['categoria'] ?? ''),
                'condicion' => trim($_POST['condicion'] ?? ''),
                'fecha_actualizacion' => date('Y-m-d H:i:s')
            ];

            // Actualizar en la BD (tu compañero implementará esto)
            $result = $this->publication->update($publicationId, $updateData);

            if ($result) {
                $this->handleSuccess("Publicación actualizada correctamente");
                // Redirigir a la publicación actualizada
                header("Location: /publications/{$publicationId}");
                exit;
            } else {
                $this->handleError("No se pudo actualizar la publicación", 500);
            }
        } catch (\Exception $e) {
            $this->handleError($e->getMessage(), 500);
        }
    }

    /**
     * Validar los datos de la publicación
     * 
     * @param array $data Datos a validar
     * @return array Array de errores (vacío si no hay errores)
     */
    private function validatePublicationData($data)
    {
        $errors = [];

        // Validar título
        if (empty($data['titulo']) || strlen($data['titulo']) < 5) {
            $errors[] = "El título debe tener al menos 5 caracteres";
        }

        if (strlen($data['titulo']) > 200) {
            $errors[] = "El título no puede exceder 200 caracteres";
        }

        // Validar descripción
        if (empty($data['descripcion']) || strlen($data['descripcion']) < 10) {
            $errors[] = "La descripción debe tener al menos 10 caracteres";
        }

        if (strlen($data['descripcion']) > 2000) {
            $errors[] = "La descripción no puede exceder 2000 caracteres";
        }

        // Validar precio
        $precio = (float)($data['precio'] ?? 0);
        if ($precio < 0) {
            $errors[] = "El precio no puede ser negativo";
        }

        // Validar disponibilidad
        $disponibilidad = (int)($data['disponibilidad'] ?? 0);
        if ($disponibilidad < 0) {
            $errors[] = "La disponibilidad no puede ser negativa";
        }

        // Validar categoría
        if (empty($data['categoria'])) {
            $errors[] = "Debe seleccionar una categoría";
        }

        // Validar condición
        if (empty($data['condicion'])) {
            $errors[] = "Debe seleccionar una condición";
        }

        $condicionesValidas = ['nuevo', 'como_nuevo', 'usado_buen_estado', 'usado_regular', 'para_reparar'];
        if (!in_array($data['condicion'], $condicionesValidas)) {
            $errors[] = "Condición no válida";
        }

        return $errors;
    }

    /**
     * Manejar errores
     * 
     * @param string $message Mensaje de error
     * @param int $statusCode Código HTTP
     * @return void
     */
    private function handleError($message, $statusCode = 400)
    {
        http_response_code($statusCode);
        echo json_encode([
            'success' => false,
            'message' => $message,
            'statusCode' => $statusCode
        ]);
    }

    /**
     * Manejar respuesta exitosa
     * 
     * @param string $message Mensaje de éxito
     * @return void
     */
    private function handleSuccess($message)
    {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => $message
        ]);
    }
}

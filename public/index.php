<?php

/**
 * Punto de entrada de la aplicación
 * 
 * Este archivo maneja el enrutamiento básico para la edición de publicaciones.
 * Se espera que sea servido desde la carpeta /public con un servidor web configurado.
 */

// Autoloader simple (puede ser reemplazado por Composer si lo deseas)
require_once __DIR__ . '/../app/Controllers/PublicationController.php';
require_once __DIR__ . '/../app/Models/Publication.php';
require_once __DIR__ . '/../config/database.php';

// Procesar rutas
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Enrutador simple
try {
    // Ruta: GET /publications/{id}/edit - Mostrar formulario de edición
    if (preg_match('/^\/publications\/(\d+)\/edit$/', $requestUri, $matches)) {
        if ($requestMethod === 'GET') {
            $publicationId = (int)$matches[1];
            $controller = new \App\Controllers\PublicationController();
            $controller->showEditForm($publicationId);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
        }
    }
    // Ruta: POST /api/publications/update - Procesar actualización
    else if ($requestUri === '/api/publications/update' && $requestMethod === 'POST') {
        $controller = new \App\Controllers\PublicationController();
        $controller->updatePublication();
    }
    // Ruta: GET /api/publications/{id} - Obtener datos de publicación en JSON
    else if (preg_match('/^\/api\/publications\/(\d+)$/', $requestUri, $matches)) {
        if ($requestMethod === 'GET') {
            $publicationId = (int)$matches[1];
            $model = new \App\Models\Publication();
            
            try {
                $data = $model->getById($publicationId);
                if ($data) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'data' => $data]);
                } else {
                    http_response_code(404);
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Publicación no encontrada']);
                }
            } catch (\Exception $e) {
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
        }
    }
    // Ruta: GET /api/vendedor/{id}/publications - Obtener todas las publicaciones de un vendedor
    else if (preg_match('/^\/api\/vendedor\/(\d+)\/publications$/', $requestUri, $matches)) {
        if ($requestMethod === 'GET') {
            $vendedorId = (int)$matches[1];
            $model = new \App\Models\Publication();
            
            try {
                $data = $model->getByVendedor($vendedorId);
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'data' => $data]);
            } catch (\Exception $e) {
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
        }
    }
    // Ruta: DELETE /api/publications/{id} - Eliminar una publicación
    else if (preg_match('/^\/api\/publications\/(\d+)$/', $requestUri, $matches)) {
        if ($requestMethod === 'DELETE') {
            $publicationId = (int)$matches[1];
            $model = new \App\Models\Publication();
            
            try {
                $result = $model->delete($publicationId);
                if ($result) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Publicación eliminada']);
                } else {
                    http_response_code(404);
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Publicación no encontrada']);
                }
            } catch (\Exception $e) {
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
        }
    }
    // Ruta default - 404
    else {
        http_response_code(404);
        echo json_encode(['error' => 'Ruta no encontrada']);
    }
} catch (\Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor',
        'error' => $e->getMessage()
    ]);
}

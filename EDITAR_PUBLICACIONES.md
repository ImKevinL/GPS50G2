# Guía de Edición de Publicaciones - Marketvesitario

## 📋 Descripción

Este módulo implementa la funcionalidad de edición de publicaciones para vendedores en la plataforma Marketvesitario. Permite a los vendedores modificar:

- **Título** de la publicación
- **Descripción** detallada
- **Precio** en COP
- **Disponibilidad** o cantidad en stock
- **Categoría** del producto
- **Condición** del producto (nuevo, usado, etc.)

## 🏗️ Estructura del Proyecto

```
GPS50G2/
├── app/
│   ├── Controllers/
│   │   └── PublicationController.php    # Lógica de control
│   ├── Models/
│   │   └── Publication.php              # Modelo de datos
│   └── Views/
│       └── edit-publication.php         # Formulario de edición
├── config/
│   └── database.php                     # Configuración de BD
├── public/
│   └── index.php                        # Punto de entrada
├── .htaccess                            # Configuración Apache
├── .env.example                         # Variables de ambiente
└── EDITAR_PUBLICACIONES.md             # Esta documentación
```

## 🚀 Cómo Usar

### 1. Configuración Inicial

1. Copia el archivo `.env.example` a `.env`:
   ```bash
   cp .env.example .env
   ```

2. Actualiza las variables de ambiente en `.env` con tus datos de BD

3. Asegúrate de que Apache tenga `mod_rewrite` habilitado

### 2. Rutas Disponibles

#### Ver formulario de edición
```
GET /publications/{id}/edit
```
Ejemplo: `GET /publications/5/edit`

Muestra el formulario de edición con los datos actuales de la publicación.

#### Actualizar publicación
```
POST /api/publications/update
```
Envía los datos del formulario para actualizar la publicación.

#### Obtener datos en JSON
```
GET /api/publications/{id}
```
Retorna los datos de una publicación en formato JSON.

#### Obtener todas las publicaciones de un vendedor
```
GET /api/vendedor/{vendedorId}/publications
```

#### Eliminar una publicación (soft delete)
```
DELETE /api/publications/{id}
```

### 3. Integración con Base de Datos

Tu compañero debe completar la integración de la base de datos en:
- `config/database.php` - Implementar la conexión PDO real
- Crear la tabla `publicaciones` con la estructura sugerida en el archivo `database.php`

#### Estructura de tabla sugerida:
```sql
CREATE TABLE publicaciones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vendedor_id INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    descripcion LONGTEXT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    disponibilidad INT NOT NULL DEFAULT 1,
    categoria VARCHAR(50) NOT NULL,
    condicion VARCHAR(50) NOT NULL,
    activa BOOLEAN DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vendedor_id) REFERENCES usuarios(id),
    INDEX idx_vendedor (vendedor_id),
    INDEX idx_categoria (categoria),
    INDEX idx_activa (activa)
);
```

## 📝 Validaciones Implementadas

### Lado del Servidor (PHP)

1. **Título**
   - Mínimo 5 caracteres
   - Máximo 200 caracteres

2. **Descripción**
   - Mínimo 10 caracteres
   - Máximo 2000 caracteres

3. **Precio**
   - No negativo
   - Acepta decimales

4. **Disponibilidad**
   - No negativa
   - Entero positivo

5. **Categoría**
   - Obligatoria
   - Debe ser una categoría válida

6. **Condición**
   - Debe ser: nuevo, como_nuevo, usado_buen_estado, usado_regular, para_reparar

### Lado del Cliente (JavaScript)

- Validación en tiempo real del precio y disponibilidad
- Prevención de envío duplicado
- Retroalimentación visual de errores

## 🎨 Características del Formulario

- **Diseño Responsivo**: Funciona en móviles y desktop
- **Colores Modernos**: Degradado púrpura (#667eea a #764ba2)
- **Validación Dinámica**: Feedback inmediato al usuario
- **Accesibilidad**: Etiquetas semánticas y estructura clara

## 🔒 Consideraciones de Seguridad

> ⚠️ **Importante**: Antes de llevar a producción, implementa:

1. **Autenticación**: Verificar que el usuario que edita es el propietario de la publicación
2. **CSRF Protection**: Agregar tokens CSRF al formulario
3. **Sanitización**: Usar `htmlspecialchars()` y prepared statements (ya implementado)
4. **Rate Limiting**: Limitar solicitudes por usuario
5. **Logging**: Registrar cambios importantes
6. **HTTPS**: Usar conexiones seguras en producción

## 📦 Dependencias

- PHP >= 7.4
- MySQL/MariaDB
- Apache con mod_rewrite (o Nginx con configuración equivalente)

## 🔧 Personalización

### Agregar nuevas categorías

Edita `app/Views/edit-publication.php` y agrega opciones en el select de categoría:

```html
<option value="nueva_categoria">Nueva Categoría</option>
```

### Cambiar validaciones

En `app/Controllers/PublicationController.php`, modifica el método `validatePublicationData()`.

### Modificar estilos

Los estilos están en `app/Views/edit-publication.php` dentro de la etiqueta `<style>`.

## 📞 Contacto para Integración

Si necesitas ayuda con la integración de la base de datos, contacta al compañero responsable del backend.

## 📄 Licencia

Este proyecto es parte de Marketvesitario - Plataforma Universitaria.

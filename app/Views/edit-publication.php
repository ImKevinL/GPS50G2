<?php
/**
 * Vista para editar una publicación
 * 
 * Variables disponibles:
 * $publicationData - Array con los datos de la publicación
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Publicación - Marketvesitario</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-hint {
            font-size: 12px;
            color: #999;
            margin-top: 4px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        button {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-cancel {
            background: #f0f0f0;
            color: #333;
            border: 1px solid #ddd;
        }

        .btn-cancel:hover {
            background: #e0e0e0;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background: #fee;
            border-left: 4px solid #f44;
            color: #c33;
        }

        .alert-success {
            background: #efe;
            border-left: 4px solid #4f4;
            color: #3c3;
        }

        .alert-info {
            background: #eef;
            border-left: 4px solid #44f;
            color: #33c;
        }

        .form-section {
            margin-bottom: 30px;
        }

        .form-section-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        @media (max-width: 600px) {
            .two-column {
                grid-template-columns: 1fr;
            }

            .button-group {
                flex-direction: column;
            }

            .container {
                padding: 20px;
            }

            .header h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✏️ Editar Publicación</h1>
            <p>Actualiza la información de tu publicación para mantenerla relevante</p>
        </div>

        <div id="alerts"></div>

        <form id="editPublicationForm" method="POST" action="/api/publications/update">
            <input type="hidden" name="publication_id" value="<?php echo htmlspecialchars($publicationData['id'] ?? ''); ?>">

            <!-- Sección Información Básica -->
            <div class="form-section">
                <div class="form-section-title">Información Básica</div>

                <div class="form-group">
                    <label for="titulo">Título de la publicación *</label>
                    <input 
                        type="text" 
                        id="titulo" 
                        name="titulo" 
                        value="<?php echo htmlspecialchars($publicationData['titulo'] ?? ''); ?>"
                        placeholder="Ej: iPhone 12 en excelente estado"
                        required
                        minlength="5"
                        maxlength="200"
                    >
                    <div class="form-hint">Mínimo 5 caracteres, máximo 200</div>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción *</label>
                    <textarea 
                        id="descripcion" 
                        name="descripcion"
                        placeholder="Describe detalladamente tu producto..."
                        required
                        minlength="10"
                        maxlength="2000"
                    ><?php echo htmlspecialchars($publicationData['descripcion'] ?? ''); ?></textarea>
                    <div class="form-hint">Mínimo 10 caracteres, máximo 2000</div>
                </div>

                <div class="two-column">
                    <div class="form-group">
                        <label for="categoria">Categoría *</label>
                        <select id="categoria" name="categoria" required>
                            <option value="">-- Selecciona una categoría --</option>
                            <option value="electronica" <?php echo ($publicationData['categoria'] ?? '') === 'electronica' ? 'selected' : ''; ?>>Electrónica</option>
                            <option value="libros" <?php echo ($publicationData['categoria'] ?? '') === 'libros' ? 'selected' : ''; ?>>Libros y Apuntes</option>
                            <option value="ropa" <?php echo ($publicationData['categoria'] ?? '') === 'ropa' ? 'selected' : ''; ?>>Ropa y Accesorios</option>
                            <option value="muebles" <?php echo ($publicationData['categoria'] ?? '') === 'muebles' ? 'selected' : ''; ?>>Muebles</option>
                            <option value="deportes" <?php echo ($publicationData['categoria'] ?? '') === 'deportes' ? 'selected' : ''; ?>>Deportes y Fitness</option>
                            <option value="tutorias" <?php echo ($publicationData['categoria'] ?? '') === 'tutorias' ? 'selected' : ''; ?>>Tutorías Académicas</option>
                            <option value="otros" <?php echo ($publicationData['categoria'] ?? '') === 'otros' ? 'selected' : ''; ?>>Otros</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="condicion">Condición del producto *</label>
                        <select id="condicion" name="condicion" required>
                            <option value="">-- Selecciona una condición --</option>
                            <option value="nuevo" <?php echo ($publicationData['condicion'] ?? '') === 'nuevo' ? 'selected' : ''; ?>>Nuevo</option>
                            <option value="como_nuevo" <?php echo ($publicationData['condicion'] ?? '') === 'como_nuevo' ? 'selected' : ''; ?>>Como nuevo</option>
                            <option value="usado_buen_estado" <?php echo ($publicationData['condicion'] ?? '') === 'usado_buen_estado' ? 'selected' : ''; ?>>Usado (buen estado)</option>
                            <option value="usado_regular" <?php echo ($publicationData['condicion'] ?? '') === 'usado_regular' ? 'selected' : ''; ?>>Usado (regular)</option>
                            <option value="para_reparar" <?php echo ($publicationData['condicion'] ?? '') === 'para_reparar' ? 'selected' : ''; ?>>Para reparar</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Sección Precio y Disponibilidad -->
            <div class="form-section">
                <div class="form-section-title">Precio y Disponibilidad</div>

                <div class="two-column">
                    <div class="form-group">
                        <label for="precio">Precio (COP) *</label>
                        <input 
                            type="number" 
                            id="precio" 
                            name="precio" 
                            value="<?php echo htmlspecialchars($publicationData['precio'] ?? 0); ?>"
                            placeholder="Ej: 100000"
                            required
                            min="0"
                            step="0.01"
                        >
                        <div class="form-hint">Ingresa el precio sin puntos ni comas</div>
                    </div>

                    <div class="form-group">
                        <label for="disponibilidad">Cantidad disponible *</label>
                        <input 
                            type="number" 
                            id="disponibilidad" 
                            name="disponibilidad" 
                            value="<?php echo htmlspecialchars($publicationData['disponibilidad'] ?? 1); ?>"
                            placeholder="Ej: 5"
                            required
                            min="0"
                        >
                        <div class="form-hint">Unidades disponibles para vender</div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="button-group">
                <button type="button" class="btn-cancel" onclick="window.history.back()">Cancelar</button>
                <button type="submit" class="btn-submit">Guardar Cambios</button>
            </div>
        </form>
    </div>

    <script>
        // Manejar el envío del formulario
        document.getElementById('editPublicationForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const alertsDiv = document.getElementById('alerts');

            fetch('/api/publications/update', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alertsDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    setTimeout(() => {
                        window.location.href = `/publications/${formData.get('publication_id')}`;
                    }, 1500);
                } else {
                    alertsDiv.innerHTML = `<div class="alert alert-error">${data.message}</div>`;
                }
            })
            .catch(error => {
                alertsDiv.innerHTML = `<div class="alert alert-error">Error al enviar el formulario: ${error.message}</div>`;
            });
        });

        // Validación en tiempo real
        const camposPrecio = document.getElementById('precio');
        if (camposPrecio) {
            camposPrecio.addEventListener('input', function() {
                if (this.value < 0) this.value = 0;
            });
        }

        const camposDisponibilidad = document.getElementById('disponibilidad');
        if (camposDisponibilidad) {
            camposDisponibilidad.addEventListener('input', function() {
                if (this.value < 0) this.value = 0;
            });
        }
    </script>
</body>
</html>

<?php
 class QuoteModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // --- FUNCIÓN DE GUARDADO (La que ya tenías) ---
    public function guardarCotizacionCompleta($codigo, $usuario_id, $cliente, $resumen) {
        $sql = "INSERT INTO cotizacion (
                    cotizacion_id, usuario_id, nombre_cliente, empresa, 
                    email_contacto, telefono_contacto, cantidad_servicios, 
                    subtotal, descuento, iva, total
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $codigo,
            $usuario_id,
            $cliente['nombre'],
            $cliente['empresa'],
            $cliente['email'],
            $cliente['telefono'],
            $resumen['cantidad'],
            $resumen['subtotal'],
            $resumen['descuento'],
            $resumen['iva'],
            $resumen['total']
        ]);
    }

    // --- FUNCIÓN PARA EL HISTORIAL (Nueva lógica de Roles) ---
   public function obtenerListadoCotizaciones($user_id, $rol_id) {
    try {
        $rol_id = (int)$rol_id;

        if ($rol_id === 1) {
            // Seleccionamos c.* (que incluye la empresa) 
            // y mantenemos el join por si necesitas el nombre del usuario después
            $sql = "SELECT c.*, u.nombre_usuario 
                    FROM cotizacion c
                    LEFT JOIN usuario u ON c.usuario_id = u.id_usuario
                    ORDER BY c.fecha DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        } else {
            $sql = "SELECT * FROM cotizacion WHERE usuario_id = ? ORDER BY fecha DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$user_id]);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}
    // --- FUNCIÓN PARA VACIAR (Opcional) ---
    public function vaciarHistorial($user_id, $rol_id) {
        if ($rol_id == 1) {
            // El admin borra TODO
            $sql = "DELETE FROM cotizacion";
            return $this->db->exec($sql);
        } else {
            // El usuario solo borra lo SUYO
            $sql = "DELETE FROM cotizacion WHERE usuario_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$user_id]);
        }
    }
    public function actualizarEstado($cotizacion_id, $nuevo_estado) {
    try {
        $sql = "UPDATE cotizacion SET estado = ? WHERE cotizacion_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nuevo_estado, $cotizacion_id]);
    } catch (PDOException $e) {
        error_log("Error al actualizar estado: " . $e->getMessage());
        return false;
    }
}
     public function eliminarCotizacion($cotizacion_id) {
        try {
            $sql = "DELETE FROM cotizacion WHERE cotizacion_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$cotizacion_id]);
        } catch (PDOException $e) {
            error_log("Error al eliminar cotización: " . $e->getMessage());
            return false;
        }
    }
}


<?php
class QuoteModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

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
}
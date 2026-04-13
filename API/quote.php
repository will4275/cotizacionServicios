<?php
// API/quote.php

class Quote {
    private $items = [];

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->items = $_SESSION['quote_items'] ?? [];
    }

    public function agregarItem($service) {
        $id = $service['id'];

        if (isset($this->items[$id])) {
            if ($this->items[$id]['cantidad'] < 10) {
                $this->items[$id]['cantidad']++;
            } else {
                return false; 
            }
        } else {
            $this->items[$id] = [
                "id"       => $id,
                "nombre"   => $service['nombre'],
                "precio"   => (float)$service['precio'],
                "cantidad" => 1
            ];
        }

        $this->guardar();
        return true;
    }

    private function guardar() {
        $_SESSION['quote_items'] = $this->items;
    }

    public function vaciar() {
        $this->items = [];
        unset($_SESSION['quote_items']);
    }

    public function calcularSubtotal() {
        return array_reduce($this->items, function($sum, $item) {
            return $sum + ($item['precio'] * $item['cantidad']);
        }, 0);
    }

    public function calcularDescuento() {
        $sub = $this->calcularSubtotal();
        if ($sub >= 2500) return $sub * 0.15;
        if ($sub >= 1000) return $sub * 0.10;
        if ($sub >= 500)  return $sub * 0.05;
        return 0;
    }

    public function calcularIVA($neto) {
        return $neto * 0.13;
    }

    // --- AQUÍ ESTABA EL ERROR, ASÍ DEBE QUEDAR: ---
    public function calcularTotal() {
        $subtotal = $this->calcularSubtotal();
        $descuento = $this->calcularDescuento();
        $neto = $subtotal - $descuento;
        $iva = $this->calcularIVA($neto);
        
        return $neto + $iva;
    }
}
    

   

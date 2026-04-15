# Sistema de Cotización de Servicios (MVC + MySQL)

Sistema web desarrollado en **PHP (POO + MVC) + MySQL + JavaScript (AJAX)** que permite a los usuarios registrarse, iniciar sesión, gestionar un carrito dinámico de servicios y generar cotizaciones formales con almacenamiento en base de datos.

---

## 🚀 Características

- Registro e inicio de sesión de usuarios
- Manejo de roles:
  - Usuario
  - Administrador
- Catálogo de servicios dinámico desde base de datos
- Carrito de cotización con AJAX
- Modificación de cantidades en tiempo real
- Eliminación de servicios y vaciado del carrito
- Cálculo automático de:
  - Subtotal
  - Descuento
  - IVA (13%)
  - Total
- Generación de cotizaciones con:
  - Código único
  - Fecha de emisión
  - Fecha de vencimiento (7 días)
- Persistencia de datos en MySQL
- Validación dual (Frontend + Backend)
- Interfaz responsiva con Bootstrap

---

## 🛠️ Tecnologías utilizadas

- PHP 8+
- Arquitectura MVC
- MySQL
- JavaScript (Fetch API / AJAX)
- Bootstrap 5
- HTML5
- CSS3
- XAMPP (Apache + MySQL)

---

## 📂 Estructura del Proyecto

SistemaDeCotizacion/
│
├── app/
│   ├── controllers/
│   ├── models/
│   └── views/
│
├── config/
│   └── database.php
│
├── public/
│   ├── index.php
│   ├── assets/
│   │   ├── js/
│   │   └── css/
│
├── routes/
│   └── web.php
│
├── storage/
│   └── sessions/
│
└── README.md

---

## ⚙️ Instalación (XAMPP)

### 📌 Requisitos

- XAMPP instalado
- Apache y MySQL activos
- PHP 8 o superior

### 🧩 Pasos

1. Copiar el proyecto en:
   C:\xampp\htdocs\

2. Iniciar Apache y MySQL desde XAMPP

3. Crear la base de datos:
   CREATE DATABASE cotizacion_servicios;

4. Importar el script SQL

5. Configurar conexión en:
   config/database.php

6. Acceder desde:
   http://localhost/SistemaDeCotizacion/public

---

## 🧠 Funcionamiento del Sistema

### 🔐 Autenticación

- Registro de usuarios
- Contraseñas cifradas
- Manejo de sesiones
- Control por roles

### 🛍️ Catálogo

- Servicios desde MySQL
- Organizados por categorías
- Diseño responsive

### 🛒 Carrito

- Manejado con sesiones
- AJAX para operaciones
- Actualización en tiempo real

### 🧮 Cálculos

- Subtotal = Σ (precio × cantidad)
- Descuento
- IVA (13%)
- Total final

### 📄 Cotización

- Datos del cliente
- Código único (COT-YYYY-####)
- Vigencia de 7 días

---

## 📏 Reglas de Negocio

- $500 – $999 → 5%
- $1000 – $2499 → 10%
- $2500+ → 15%

---

## ✅ Validaciones

### Frontend
- Validación en tiempo real

### Backend
- Sanitización
- Validación de reglas
- Respuestas JSON

---

## ⚠️ Restricciones

- Carrito no vacío
- Subtotal mínimo: $100
- Cantidad: 1 - 10
- Campos obligatorios

---

## 🔐 Seguridad

- Sesiones PHP
- Hash de contraseñas
- Control de acceso
- Consultas preparadas

---

## 📦 Endpoints AJAX

- add-to-cart.php
- update-cart.php
- remove-from-cart.php
- process-quote.php

---

## 👨‍💻 Autores

Willian Yahir Aguilar Rodriguez  
Jeremias Vladimir Chavez Rodriguez

# NovaShop

Proyecto final de tienda online desarrollado con PHP nativo, arquitectura MVC, MySQL, PDO, Bootstrap 5 y JavaScript.

## Funcionalidades cubiertas

- Catalogo dinamico de productos
- Categorias y subcategorias
- Busqueda, filtros y ordenacion
- Detalle de producto
- Carrito en sesion
- Checkout como invitado o usuario registrado
- Generacion de pedidos y detalle de pedido
- Historial y detalle de pedidos del cliente
- Registro, login, logout y perfil
- Panel de empleado para categorias, productos y pedidos
- Panel de administrador para usuarios, roles e informes
- Seguridad basica: PDO, consultas preparadas, sesiones, CSRF, password hash y control por roles
- Confirmacion de pedido simulada en log

## Estructura

```text
app/
  controllers/
  core/
  models/
  services/
  views/
config/
database/
public/
  assets/
routes/
uploads/
storage/
```

## Requisitos

- PHP 8.1 o superior
- MySQL 8 o MariaDB compatible
- Apache o entorno XAMPP

## Instalacion

1. Copia el proyecto dentro de `htdocs`.
2. Crea una base de datos MySQL importando [database/schema.sql](/Applications/XAMPP/xamppfiles/htdocs/website ecommerce/database/schema.sql).
3. Revisa [config/database.php](/Applications/XAMPP/xamppfiles/htdocs/website ecommerce/config/database.php) y ajusta host, usuario o password si fuese necesario.
4. Abre en navegador:

```text
http://localhost/website%20ecommerce/
```

## Credenciales de prueba

- Administrador: `admin@novashop.local` / `Admin1234!`
- Empleado: `empleado@novashop.local` / `Empleado1234!`
- Cliente: `cliente@novashop.local` / `Cliente1234!`

## Integracion externa

La confirmacion de pedido se registra en:

```text
storage/logs/order_emails.log
```

Esto simula el envio de correo y permite verificar la funcionalidad sin instalar librerias externas.

## Mejoras opcionales

- Integrar PHPMailer real con Composer
- Recuperacion de contrasena
- Gestion de imagenes por upload validado
- Exportacion CSV o PDF de informes
- Paginacion avanzada en paneles internos

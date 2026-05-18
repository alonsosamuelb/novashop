# NovaShop

Proyecto final de tienda online desarrollado con PHP nativo, arquitectura MVC, MySQL, PDO, Bootstrap 5 y JavaScript.

Repositorio: [github.com/alonsosamuelb/novashop](https://github.com/alonsosamuelb/novashop)

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
2. Crea una base de datos MySQL llamada `ecommerce_mvc`.
3. Importa [database/schema.sql](/Applications/XAMPP/xamppfiles/htdocs/novashop/database/schema.sql).
4. Revisa [config/database.php](/Applications/XAMPP/xamppfiles/htdocs/novashop/config/database.php) si necesitas cambiar host, usuario o password.
5. Opcionalmente, define variables de entorno para no fijar esos valores en el codigo:

```bash
export DB_HOST=localhost
export DB_PORT=3306
export DB_DATABASE=ecommerce_mvc
export DB_USERNAME=root
export DB_PASSWORD=
export APP_URL=http://localhost/novashop
export ASSET_URL=http://localhost/novashop/public/assets
```

6.Apache puede leer la carpeta del proyecto:

```bash
chmod 755 /Applications/XAMPP/xamppfiles/htdocs/novashop
```

7. Abre en navegador:

```text
http://localhost/novashop
```

## Credenciales de prueba

- Administrador: `admin@novashop.com` / `12345678`
- Empleado: `empleado@novashop.com` / `12345678`
- Cliente: `cliente@novashop.com` / `12345678`

## Integracion externa

La confirmacion de pedido se registra en:

```text
storage/logs/order_emails.log
```

Esto simula el envio de correo y permite verificar la funcionalidad sin instalar librerias externas.

<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;
use App\Controllers\CustomerController;
use App\Controllers\EmployeeController;
use App\Controllers\HomeController;
use App\Controllers\PageController;
use App\Controllers\ProductController;

$router->get('', [HomeController::class, 'index']);
$router->get('catalogo', [ProductController::class, 'index']);
$router->get('producto', [ProductController::class, 'show']);
$router->get('carrito', [CartController::class, 'index']);
$router->post('carrito/anadir', [CartController::class, 'add']);
$router->post('carrito/actualizar', [CartController::class, 'update']);
$router->post('carrito/eliminar', [CartController::class, 'remove']);
$router->post('carrito/vaciar', [CartController::class, 'clear']);

$router->get('checkout', [CheckoutController::class, 'index']);
$router->post('checkout', [CheckoutController::class, 'store']);
$router->get('pedido-confirmado', [CheckoutController::class, 'success']);

$router->get('quienes-somos', [PageController::class, 'about']);
$router->get('contacto', [PageController::class, 'contact']);
$router->get('envio', [PageController::class, 'shipping']);
$router->get('condiciones-generales', [PageController::class, 'terms']);
$router->get('politica-devoluciones', [PageController::class, 'returns']);

$router->get('login', [AuthController::class, 'showLogin'], ['guest']);
$router->post('login', [AuthController::class, 'login'], ['guest']);

$router->get('registro', [AuthController::class, 'showRegister'], ['guest']);
$router->post('registro', [AuthController::class, 'register'], ['guest']);

$router->post('logout', [AuthController::class, 'logout'], ['auth']);

$router->get('mi-cuenta', [CustomerController::class, 'dashboard'], ['auth']);
$router->get('mi-perfil', [CustomerController::class, 'profile'], ['auth']);
$router->post('mi-perfil', [CustomerController::class, 'updateProfile'], ['auth']);
$router->get('mis-pedidos', [CustomerController::class, 'orders'], ['auth']);
$router->get('mis-pedidos/ver', [CustomerController::class, 'orderShow'], ['auth']);

$router->get('empleado', [EmployeeController::class, 'dashboard'], ['auth', 'role:empleado,administrador']);
$router->get('empleado/categorias', [EmployeeController::class, 'categories'], ['auth', 'role:empleado,administrador']);
$router->post('empleado/categorias/crear', [EmployeeController::class, 'storeCategory'], ['auth', 'role:empleado,administrador']);
$router->post('empleado/categorias/editar', [EmployeeController::class, 'updateCategory'], ['auth', 'role:empleado,administrador']);
$router->post('empleado/categorias/desactivar', [EmployeeController::class, 'deactivateCategory'], ['auth', 'role:empleado,administrador']);

$router->get('empleado/productos', [EmployeeController::class, 'products'], ['auth', 'role:empleado,administrador']);
$router->post('empleado/productos/crear', [EmployeeController::class, 'storeProduct'], ['auth', 'role:empleado,administrador']);
$router->post('empleado/productos/editar', [EmployeeController::class, 'updateProduct'], ['auth', 'role:empleado,administrador']);
$router->post('empleado/productos/desactivar', [EmployeeController::class, 'deactivateProduct'], ['auth', 'role:empleado,administrador']);

$router->get('empleado/pedidos', [EmployeeController::class, 'orders'], ['auth', 'role:empleado,administrador']);
$router->get('empleado/pedidos/ver', [EmployeeController::class, 'orderShow'], ['auth', 'role:empleado,administrador']);
$router->post('empleado/pedidos/estado', [EmployeeController::class, 'updateOrderStatus'], ['auth', 'role:empleado,administrador']);

$router->get('admin', [AdminController::class, 'dashboard'], ['auth', 'role:administrador']);
$router->get('admin/usuarios', [AdminController::class, 'users'], ['auth', 'role:administrador']);
$router->post('admin/usuarios/crear', [AdminController::class, 'storeUser'], ['auth', 'role:administrador']);
$router->post('admin/usuarios/editar', [AdminController::class, 'updateUser'], ['auth', 'role:administrador']);
$router->post('admin/usuarios/desactivar', [AdminController::class, 'deactivateUser'], ['auth', 'role:administrador']);
$router->post('admin/usuarios/eliminar', [AdminController::class, 'deleteUser'], ['auth', 'role:administrador']);
$router->get('admin/informes', [AdminController::class, 'reports'], ['auth', 'role:administrador']);

<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;

class EmployeeController extends Controller
{
    public function dashboard(): void
    {
        $orderModel = new Order();
        $productModel = new Product();
        $categoryModel = new Category();

        $this->view('employee/dashboard', [
            'title' => 'Panel de empleado',
            'stats' => [
                'products' => $productModel->countAllActive(),
                'categories' => $categoryModel->countAllActive(),
                'pending_orders' => $orderModel->countByStatus('pendiente'),
            ],
        ]);
    }

    public function categories(): void
    {
        $categoryModel = new Category();
        $this->view('employee/categories/index', [
            'title' => 'Gestion de categorias',
            'categories' => $categoryModel->getAllForBackoffice(),
            'parents' => $categoryModel->getParentOptions(),
        ]);
    }

    public function storeCategory(): void
    {
        if (!verify_csrf()) {
            flash('error', 'No se pudo guardar la categoria.');
            redirect('empleado/categorias');
        }

        $categoryModel = new Category();
        $name = trim((string) $this->input('nombre', FILTER_SANITIZE_SPECIAL_CHARS));

        if ($name === '') {
            flash('error', 'El nombre de la categoria es obligatorio.');
            redirect('empleado/categorias');
        }

        $categoryModel->create([
            'parent_id' => (int) ($_POST['parent_id'] ?? 0) ?: null,
            'nombre' => $name,
            'slug' => trim((string) $this->input('slug', FILTER_SANITIZE_SPECIAL_CHARS)),
            'descripcion' => trim((string) $this->input('descripcion', FILTER_SANITIZE_SPECIAL_CHARS)),
            'imagen' => trim((string) $this->input('imagen', FILTER_SANITIZE_SPECIAL_CHARS)),
            'orden' => (int) ($_POST['orden'] ?? 0),
        ]);

        flash('success', 'Categoria creada correctamente.');
        redirect('empleado/categorias');
    }

    public function updateCategory(): void
    {
        if (!verify_csrf()) {
            flash('error', 'No se pudo actualizar la categoria.');
            redirect('empleado/categorias');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $name = trim((string) $this->input('nombre', FILTER_SANITIZE_SPECIAL_CHARS));

        if ($id < 1 || $name === '') {
            flash('error', 'Datos de categoria no validos.');
            redirect('empleado/categorias');
        }

        $categoryModel = new Category();
        $categoryModel->update($id, [
            'parent_id' => (int) ($_POST['parent_id'] ?? 0) ?: null,
            'nombre' => $name,
            'slug' => trim((string) $this->input('slug', FILTER_SANITIZE_SPECIAL_CHARS)),
            'descripcion' => trim((string) $this->input('descripcion', FILTER_SANITIZE_SPECIAL_CHARS)),
            'imagen' => trim((string) $this->input('imagen', FILTER_SANITIZE_SPECIAL_CHARS)),
            'orden' => (int) ($_POST['orden'] ?? 0),
        ]);

        flash('success', 'Categoria actualizada.');
        redirect('empleado/categorias');
    }

    public function deactivateCategory(): void
    {
        if (!verify_csrf()) {
            flash('error', 'No se pudo desactivar la categoria.');
            redirect('empleado/categorias');
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            (new Category())->softDelete($id);
            flash('success', 'Categoria desactivada correctamente.');
        }

        redirect('empleado/categorias');
    }

    public function products(): void
    {
        $productModel = new Product();
        $categoryModel = new Category();
        $search = trim((string) $this->query('buscar', FILTER_SANITIZE_SPECIAL_CHARS));
        $sort = trim((string) $this->query('orden', FILTER_SANITIZE_SPECIAL_CHARS));

        $this->view('employee/products/index', [
            'title' => 'Gestion de productos',
            'products' => $productModel->getAllForBackoffice($search, $sort),
            'categories' => $categoryModel->getFilterOptions(),
            'search' => $search,
            'sort' => $sort,
        ]);
    }

    public function storeProduct(): void
    {
        if (!verify_csrf()) {
            flash('error', 'No se pudo guardar el producto.');
            redirect('empleado/productos');
        }

        $categoryId = (int) ($_POST['categoria_id'] ?? 0);
        $name = trim((string) $this->input('nombre', FILTER_SANITIZE_SPECIAL_CHARS));
        $code = trim((string) $this->input('codigo', FILTER_SANITIZE_SPECIAL_CHARS));

        if ($categoryId < 1 || $name === '' || $code === '') {
            flash('error', 'Categoria, nombre y codigo son obligatorios.');
            redirect('empleado/productos');
        }

        (new Product())->create([
            'categoria_id' => $categoryId,
            'codigo' => $code,
            'nombre' => $name,
            'slug' => trim((string) $this->input('slug', FILTER_SANITIZE_SPECIAL_CHARS)),
            'descripcion' => trim((string) $this->input('descripcion', FILTER_SANITIZE_SPECIAL_CHARS)),
            'precio' => (float) ($_POST['precio'] ?? 0),
            'precio_oferta' => $_POST['precio_oferta'] !== '' ? (float) $_POST['precio_oferta'] : null,
            'stock' => max(0, (int) ($_POST['stock'] ?? 0)),
            'imagen' => trim((string) $this->input('imagen', FILTER_SANITIZE_SPECIAL_CHARS)),
            'destacado' => isset($_POST['destacado']) ? 1 : 0,
        ]);

        flash('success', 'Producto creado correctamente.');
        redirect('empleado/productos');
    }

    public function updateProduct(): void
    {
        if (!verify_csrf()) {
            flash('error', 'No se pudo actualizar el producto.');
            redirect('empleado/productos');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $categoryId = (int) ($_POST['categoria_id'] ?? 0);
        $name = trim((string) $this->input('nombre', FILTER_SANITIZE_SPECIAL_CHARS));
        $code = trim((string) $this->input('codigo', FILTER_SANITIZE_SPECIAL_CHARS));

        if ($id < 1 || $categoryId < 1 || $name === '' || $code === '') {
            flash('error', 'Datos de producto no validos.');
            redirect('empleado/productos');
        }

        (new Product())->update($id, [
            'categoria_id' => $categoryId,
            'codigo' => $code,
            'nombre' => $name,
            'slug' => trim((string) $this->input('slug', FILTER_SANITIZE_SPECIAL_CHARS)),
            'descripcion' => trim((string) $this->input('descripcion', FILTER_SANITIZE_SPECIAL_CHARS)),
            'precio' => (float) ($_POST['precio'] ?? 0),
            'precio_oferta' => $_POST['precio_oferta'] !== '' ? (float) $_POST['precio_oferta'] : null,
            'stock' => max(0, (int) ($_POST['stock'] ?? 0)),
            'imagen' => trim((string) $this->input('imagen', FILTER_SANITIZE_SPECIAL_CHARS)),
            'destacado' => isset($_POST['destacado']) ? 1 : 0,
        ]);

        flash('success', 'Producto actualizado.');
        redirect('empleado/productos');
    }

    public function deactivateProduct(): void
    {
        if (!verify_csrf()) {
            flash('error', 'No se pudo desactivar el producto.');
            redirect('empleado/productos');
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            (new Product())->softDelete($id);
            flash('success', 'Producto desactivado correctamente.');
        }

        redirect('empleado/productos');
    }

    public function orders(): void
    {
        $status = trim((string) $this->query('estado', FILTER_SANITIZE_SPECIAL_CHARS));

        $this->view('employee/orders/index', [
            'title' => 'Gestion de pedidos',
            'orders' => (new Order())->getAllForBackoffice($status),
            'status' => $status,
        ]);
    }

    public function orderShow(): void
    {
        $id = (int) ($this->query('id', FILTER_VALIDATE_INT) ?: 0);
        $order = (new Order())->findById($id);

        if (!$order) {
            http_response_code(404);
            $this->view('errors.404', ['title' => 'Pedido no encontrado']);
            return;
        }

        $this->view('employee/orders/show', [
            'title' => 'Detalle del pedido',
            'order' => $order,
        ]);
    }

    public function updateOrderStatus(): void
    {
        if (!verify_csrf()) {
            flash('error', 'No se pudo actualizar el estado del pedido.');
            redirect('empleado/pedidos');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $status = trim((string) $this->input('estado', FILTER_SANITIZE_SPECIAL_CHARS));

        if (!in_array($status, config('app.order_statuses', []), true)) {
            flash('error', 'Estado de pedido no valido.');
            redirect('empleado/pedidos');
        }

        (new Order())->updateStatus($id, $status);
        flash('success', 'Estado del pedido actualizado.');
        redirect('empleado/pedidos');
    }
}

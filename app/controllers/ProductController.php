<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(): void
    {
        $productModel = new Product();
        $categoryModel = new Category();

        $filters = [
            'search' => trim((string) $this->query('buscar', FILTER_SANITIZE_SPECIAL_CHARS)),
            'category_slug' => trim((string) $this->query('categoria', FILTER_SANITIZE_SPECIAL_CHARS)),
            'sort' => trim((string) $this->query('orden', FILTER_SANITIZE_SPECIAL_CHARS)),
            'min_price' => $this->query('precio_min', FILTER_VALIDATE_FLOAT),
            'max_price' => $this->query('precio_max', FILTER_VALIDATE_FLOAT),
        ];

        $page = (int) ($this->query('pagina', FILTER_VALIDATE_INT) ?: 1);
        $catalog = $productModel->paginateCatalog($filters, max(1, $page), (int) config('app.pagination.products_per_page', 9));

        $this->view('products.index', [
            'title' => 'Catalogo',
            'catalog' => $catalog,
            'filters' => $filters,
            'categories' => $categoryModel->getFilterOptions(),
        ]);
    }

    public function show(): void
    {
        $slug = trim((string) $this->query('slug', FILTER_SANITIZE_SPECIAL_CHARS));

        if ($slug === '') {
            http_response_code(404);
            $this->view('errors.404', ['title' => 'Producto no encontrado']);
            return;
        }

        $productModel = new Product();
        $product = $productModel->findActiveBySlug($slug);

        if (!$product) {
            http_response_code(404);
            $this->view('errors.404', ['title' => 'Producto no encontrado']);
            return;
        }

        $related = $productModel->getRelated((int) $product['categoria_id'], (int) $product['id']);

        $this->view('products.show', [
            'title' => $product['nombre'],
            'product' => $product,
            'related' => $related,
        ]);
    }
}

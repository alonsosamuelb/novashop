<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index(): void
    {
        $productModel = new Product();
        $categoryModel = new Category();

        $this->view('home.index', [
            'title' => 'NovaShop',
            'featuredProducts' => $productModel->getFeatured((int) config('app.featured_limit', 8)),
            'homeCategories' => $categoryModel->getFilterOptions(),
        ]);
    }
}

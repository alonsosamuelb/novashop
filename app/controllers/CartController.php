<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;

class CartController extends Controller
{
    public function index(): void
    {
        $items = $this->buildCartViewData();

        $this->view('cart.index', [
            'title' => 'Carrito',
            'cart' => $items,
        ]);
    }

    public function add(): void
    {
        if (!verify_csrf()) {
            flash('error', 'No se pudo anadir el producto al carrito.');
            back();
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = max(1, (int) ($_POST['quantity'] ?? 1));

        $productModel = new Product();
        $product = $productModel->findById($productId);

        if (!$product || (int) $product['activo'] !== 1) {
            flash('error', 'El producto seleccionado no esta disponible.');
            back();
        }

        $cart = cart_items();
        $currentQuantity = (int) ($cart[$productId] ?? 0);
        $newQuantity = min($currentQuantity + $quantity, (int) $product['stock']);

        if ($newQuantity < 1) {
            flash('error', 'No hay stock disponible para este producto.');
            back();
        }

        $cart[$productId] = $newQuantity;
        session_put('cart', $cart);

        flash('success', 'Producto anadido al carrito.');
        back();
    }

    public function update(): void
    {
        if (!verify_csrf()) {
            flash('error', 'No se pudo actualizar el carrito.');
            redirect('carrito');
        }

        $removeProductId = (int) ($_POST['remove_product_id'] ?? 0);
        if ($removeProductId > 0) {
            $cart = cart_items();
            unset($cart[$removeProductId]);
            session_put('cart', $cart);

            flash('success', 'Producto eliminado del carrito.');
            redirect('carrito');
        }

        $quantities = $_POST['quantities'] ?? [];
        $productIds = array_map('intval', array_keys($quantities));

        $productModel = new Product();
        $products = $productModel->findAvailableByIds($productIds);
        $productsById = [];

        foreach ($products as $product) {
            $productsById[(int) $product['id']] = $product;
        }

        $cart = [];

        foreach ($quantities as $productId => $quantity) {
            $productId = (int) $productId;
            $quantity = (int) $quantity;

            if ($quantity <= 0 || !isset($productsById[$productId])) {
                continue;
            }

            $cart[$productId] = min($quantity, (int) $productsById[$productId]['stock']);
        }

        session_put('cart', $cart);

        flash('success', 'Carrito actualizado.');
        redirect('carrito');
    }

    public function remove(): void
    {
        if (!verify_csrf()) {
            flash('error', 'No se pudo eliminar el producto.');
            redirect('carrito');
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        $cart = cart_items();
        unset($cart[$productId]);
        session_put('cart', $cart);

        flash('success', 'Producto eliminado del carrito.');
        redirect('carrito');
    }

    public function clear(): void
    {
        if (!verify_csrf()) {
            flash('error', 'No se pudo vaciar el carrito.');
            redirect('carrito');
        }

        session_put('cart', []);
        flash('success', 'Carrito vaciado correctamente.');
        redirect('carrito');
    }

    private function buildCartViewData(): array
    {
        $cartItems = cart_items();
        $productModel = new Product();
        $products = $productModel->findAvailableByIds(array_keys($cartItems));
        $items = [];
        $subtotal = 0.0;

        foreach ($products as $product) {
            $quantity = (int) ($cartItems[$product['id']] ?? 0);
            if ($quantity < 1) {
                continue;
            }

            $price = (float) ($product['precio_oferta'] ?: $product['precio']);
            $lineSubtotal = $price * $quantity;
            $subtotal += $lineSubtotal;

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => $price,
                'subtotal' => $lineSubtotal,
            ];
        }

        return [
            'items' => $items,
            'count' => array_sum(array_column($items, 'quantity')),
            'subtotal' => $subtotal,
            'shipping' => count($items) > 0 ? (float) config('app.shipping.base_cost', 4.99) : 0.0,
            'total' => count($items) > 0 ? $subtotal + (float) config('app.shipping.base_cost', 4.99) : 0.0,
        ];
    }
}

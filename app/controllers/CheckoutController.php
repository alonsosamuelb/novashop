<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Order;
use App\Models\Product;
use Throwable;

class CheckoutController extends Controller
{
    public function index(): void
    {
        $cart = $this->getCheckoutCart();

        if (empty($cart['items'])) {
            flash('error', 'Tu carrito esta vacio.');
            redirect('catalogo');
        }

        $user = auth_user();

        $this->view('checkout.index', [
            'title' => 'Finalizar compra',
            'cart' => $cart,
            'user' => $user,
        ]);
    }

    public function store(): void
    {
        if (!verify_csrf()) {
            flash('error', 'No se pudo procesar el pedido.');
            redirect('checkout');
        }

        $cart = $this->getCheckoutCart();

        if (empty($cart['items'])) {
            flash('error', 'Tu carrito esta vacio.');
            redirect('catalogo');
        }

        $data = [
            'nombre' => trim((string) $this->input('nombre', FILTER_SANITIZE_SPECIAL_CHARS)),
            'apellidos' => trim((string) $this->input('apellidos', FILTER_SANITIZE_SPECIAL_CHARS)),
            'email' => trim((string) $this->input('email', FILTER_SANITIZE_EMAIL)),
            'telefono' => trim((string) $this->input('telefono', FILTER_SANITIZE_SPECIAL_CHARS)),
            'direccion_envio' => trim((string) $this->input('direccion_envio', FILTER_SANITIZE_SPECIAL_CHARS)),
            'ciudad' => trim((string) $this->input('ciudad', FILTER_SANITIZE_SPECIAL_CHARS)),
            'provincia' => trim((string) $this->input('provincia', FILTER_SANITIZE_SPECIAL_CHARS)),
            'codigo_postal' => trim((string) $this->input('codigo_postal', FILTER_SANITIZE_SPECIAL_CHARS)),
            'pais' => trim((string) $this->input('pais', FILTER_SANITIZE_SPECIAL_CHARS)) ?: 'Espana',
            'observaciones' => trim((string) $this->input('observaciones', FILTER_SANITIZE_SPECIAL_CHARS)),
        ];

        with_old($data);

        $errors = $this->validateCheckoutData($data);
        if ($errors !== []) {
            flash('error', implode(' ', $errors));
            redirect('checkout');
        }

        $orderModel = new Order();

        try {
            $orderId = $orderModel->createFromCheckout(
                $data,
                cart_items(),
                array_map(static fn ($item) => $item['product'], $cart['items']),
                auth_user()
            );
        } catch (Throwable $exception) {
            flash('error', 'No se pudo completar el pedido: ' . $exception->getMessage());
            redirect('checkout');
        }

        clear_old();
        session_put('cart', []);
        session_put('last_order_id', $orderId);

        flash('success', 'Pedido generado correctamente.');
        redirect('pedido-confirmado');
    }

    public function success(): void
    {
        $orderId = (int) session_get('last_order_id', 0);

        if ($orderId < 1) {
            flash('error', 'No hay ningun pedido reciente para mostrar.');
            redirect('');
        }

        $orderModel = new Order();
        $order = $orderModel->findById($orderId);

        if (!$order) {
            flash('error', 'No se pudo recuperar el pedido.');
            redirect('');
        }

        $this->view('checkout.success', [
            'title' => 'Pedido confirmado',
            'order' => $order,
        ]);
    }

    private function getCheckoutCart(): array
    {
        $productModel = new Product();
        $cartItems = cart_items();
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

        $shipping = count($items) > 0 ? (float) config('app.shipping.base_cost', 4.99) : 0.0;

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $subtotal + $shipping,
        ];
    }

    private function validateCheckoutData(array $data): array
    {
        $errors = [];

        if ($data['nombre'] === '' || mb_strlen($data['nombre']) < 2) {
            $errors[] = 'El nombre es obligatorio.';
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Debes indicar un correo valido.';
        }

        if ($data['direccion_envio'] === '' || $data['ciudad'] === '' || $data['provincia'] === '' || $data['codigo_postal'] === '') {
            $errors[] = 'Debes completar todos los datos de envio obligatorios.';
        }

        return $errors;
    }
}

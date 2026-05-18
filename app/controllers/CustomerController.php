<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Order;
use App\Models\User;

class CustomerController extends Controller
{
    public function dashboard(): void
    {
        $orderModel = new Order();
        $user = auth_user();

        $this->view('customer/dashboard', [
            'title' => 'Mi cuenta',
            'orders' => $orderModel->getByUserId((int) $user['id']),
            'user' => $user,
        ]);
    }

    public function profile(): void
    {
        $this->view('customer/profile', [
            'title' => 'Mi perfil',
            'user' => auth_user(),
        ]);
    }

    public function updateProfile(): void
    {
        if (!verify_csrf()) {
            flash('error', 'No se pudo actualizar el perfil.');
            redirect('mi-perfil');
        }

        $user = auth_user();
        $data = [
            'nombre' => trim((string) $this->input('nombre', FILTER_SANITIZE_SPECIAL_CHARS)),
            'apellidos' => trim((string) $this->input('apellidos', FILTER_SANITIZE_SPECIAL_CHARS)),
            'email' => trim((string) $this->input('email', FILTER_SANITIZE_EMAIL)),
            'telefono' => trim((string) $this->input('telefono', FILTER_SANITIZE_SPECIAL_CHARS)),
            'dni' => trim((string) $this->input('dni', FILTER_SANITIZE_SPECIAL_CHARS)),
            'password' => (string) ($_POST['password'] ?? ''),
            'password_confirmation' => (string) ($_POST['password_confirmation'] ?? ''),
        ];

        with_old($data);

        $errors = [];
        if ($data['nombre'] === '' || mb_strlen($data['nombre']) < 2) {
            $errors[] = 'El nombre es obligatorio.';
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El email no es valido.';
        }

        if ($data['password'] !== '' && mb_strlen($data['password']) < 8) {
            $errors[] = 'La nueva contrasena debe tener al menos 8 caracteres.';
        }

        if ($data['password'] !== $data['password_confirmation']) {
            $errors[] = 'La confirmacion de contrasena no coincide.';
        }

        $userModel = new User();
        if ($userModel->emailExistsForOtherUser($data['email'], (int) $user['id'])) {
            $errors[] = 'Ese correo ya esta siendo utilizado por otro usuario.';
        }

        if ($errors !== []) {
            flash('error', implode(' ', $errors));
            redirect('mi-perfil');
        }

        $userModel->updateProfile((int) $user['id'], [
            'nombre' => $data['nombre'],
            'apellidos' => $data['apellidos'],
            'email' => $data['email'],
            'telefono' => $data['telefono'],
            'dni' => $data['dni'],
            'password' => $data['password'] !== '' ? password_hash($data['password'], PASSWORD_DEFAULT) : null,
        ]);

        clear_old();
        session_put('auth_user', $userModel->findById((int) $user['id']));

        flash('success', 'Perfil actualizado correctamente.');
        redirect('mi-perfil');
    }

    public function orders(): void
    {
        $orderModel = new Order();
        $user = auth_user();

        $this->view('customer/orders', [
            'title' => 'Mis pedidos',
            'orders' => $orderModel->getByUserId((int) $user['id']),
        ]);
    }

    public function orderShow(): void
    {
        $id = (int) ($this->query('id', FILTER_VALIDATE_INT) ?: 0);
        $user = auth_user();
        $orderModel = new Order();
        $order = $orderModel->findById($id);

        if (!$order || (int) $order['usuario_id'] !== (int) $user['id']) {
            http_response_code(404);
            $this->view('errors.404', ['title' => 'Pedido no encontrado']);
            return;
        }

        $this->view('customer/order-show', [
            'title' => 'Detalle del pedido',
            'order' => $order,
        ]);
    }
}

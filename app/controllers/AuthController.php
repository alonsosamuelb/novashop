<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->view('auth.login', [
            'title' => 'Iniciar sesion',
        ]);
    }

    public function login(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token CSRF no valido.');
            redirect('login');
        }

        $email = trim((string) $this->input('email', FILTER_SANITIZE_EMAIL));
        $password = (string) ($_POST['password'] ?? '');

        with_old([
            'email' => $email,
        ]);

        if ($email === '' || $password === '') {
            flash('error', 'Debes completar email y contrasena.');
            redirect('login');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'El email introducido no es valido.');
            redirect('login');
        }

        if (!Auth::attempt($email, $password)) {
            flash('error', 'Credenciales invalidas o usuario inactivo.');
            redirect('login');
        }

        clear_old();
        flash('success', 'Sesion iniciada correctamente.');
        redirect('');
    }

    public function showRegister(): void
    {
        $this->view('auth.register', [
            'title' => 'Crear cuenta',
        ]);
    }

    public function register(): void
    {
        if (!verify_csrf()) {
            flash('error', 'Token CSRF no valido.');
            redirect('registro');
        }

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

        $errors = $this->validateRegisterData($data);

        if ($errors !== []) {
            flash('error', implode(' ', $errors));
            redirect('registro');
        }

        $userModel = new User();

        if ($userModel->emailExists($data['email'])) {
            flash('error', 'Ya existe una cuenta con ese correo.');
            redirect('registro');
        }

        $userId = $userModel->create([
            'nombre' => $data['nombre'],
            'apellidos' => $data['apellidos'],
            'email' => $data['email'],
            'telefono' => $data['telefono'],
            'dni' => $data['dni'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'rol' => 'cliente',
            'activo' => 1,
        ]);

        clear_old();

        $user = $userModel->findById($userId);
        if ($user) {
            session_regenerate_id(true);
            session_put('auth_user', $user);
        }

        flash('success', 'Cuenta creada correctamente. Ya has iniciado sesion.');
        redirect('');
    }

    public function logout(): void
    {
        if (!verify_csrf()) {
            flash('error', 'No se pudo cerrar la sesion.');
            redirect('');
        }

        Auth::logout();
        flash('success', 'Sesion cerrada correctamente.');
        redirect('');
    }

    private function validateRegisterData(array $data): array
    {
        $errors = [];

        if ($data['nombre'] === '' || mb_strlen($data['nombre']) < 2) {
            $errors[] = 'El nombre es obligatorio y debe tener al menos 2 caracteres.';
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Debes indicar un email valido.';
        }

        if (mb_strlen($data['password']) < 8) {
            $errors[] = 'La contrasena debe tener al menos 8 caracteres.';
        }

        if ($data['password'] !== $data['password_confirmation']) {
            $errors[] = 'La confirmacion de contrasena no coincide.';
        }

        return $errors;
    }
}

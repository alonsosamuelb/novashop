<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Order;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard(): void
    {
        $userModel = new User();
        $orderModel = new Order();

        $this->view('admin/dashboard', [
            'title' => 'Panel de administracion',
            'stats' => [
                'clientes' => $userModel->countByRole('cliente'),
                'empleados' => $userModel->countByRole('empleado'),
                'administradores' => $userModel->countByRole('administrador'),
                'ventas_totales' => $orderModel->getTotalRevenue(),
            ],
        ]);
    }

    public function users(): void
    {
        $role = trim((string) $this->query('rol', FILTER_SANITIZE_SPECIAL_CHARS));
        $search = trim((string) $this->query('buscar', FILTER_SANITIZE_SPECIAL_CHARS));

        $this->view('admin/users/index', [
            'title' => 'Gestion de usuarios',
            'users' => (new User())->getBackofficeUsers($role, $search),
            'role' => $role,
            'search' => $search,
        ]);
    }

    public function storeUser(): void
    {
        if (!verify_csrf()) {
            flash('error', 'No se pudo crear el usuario.');
            redirect('admin/usuarios');
        }

        $role = trim((string) $this->input('rol', FILTER_SANITIZE_SPECIAL_CHARS));
        $email = trim((string) $this->input('email', FILTER_SANITIZE_EMAIL));
        $name = trim((string) $this->input('nombre', FILTER_SANITIZE_SPECIAL_CHARS));
        $password = (string) ($_POST['password'] ?? '');

        if (!in_array($role, config('app.roles', []), true) || $name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($password) < 8) {
            flash('error', 'Debes completar nombre, email, rol y contrasena valida.');
            redirect('admin/usuarios');
        }

        $userModel = new User();
        if ($userModel->emailExists($email)) {
            flash('error', 'Ya existe un usuario con ese correo.');
            redirect('admin/usuarios');
        }

        $userModel->create([
            'nombre' => $name,
            'apellidos' => trim((string) $this->input('apellidos', FILTER_SANITIZE_SPECIAL_CHARS)),
            'email' => $email,
            'telefono' => trim((string) $this->input('telefono', FILTER_SANITIZE_SPECIAL_CHARS)),
            'dni' => trim((string) $this->input('dni', FILTER_SANITIZE_SPECIAL_CHARS)),
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'rol' => $role,
            'activo' => 1,
        ]);

        flash('success', 'Usuario creado correctamente.');
        redirect('admin/usuarios');
    }

    public function updateUser(): void
    {
        if (!verify_csrf()) {
            flash('error', 'No se pudo actualizar el usuario.');
            redirect('admin/usuarios');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $currentUser = auth_user();
        $role = trim((string) $this->input('rol', FILTER_SANITIZE_SPECIAL_CHARS));
        $email = trim((string) $this->input('email', FILTER_SANITIZE_EMAIL));
        $name = trim((string) $this->input('nombre', FILTER_SANITIZE_SPECIAL_CHARS));

        if ($id < 1 || $name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Datos de usuario no validos.');
            redirect('admin/usuarios');
        }

        if ($id === (int) $currentUser['id'] && $role !== $currentUser['rol']) {
            flash('error', 'No puedes cambiar tu propio rol.');
            redirect('admin/usuarios');
        }

        if (!in_array($role, config('app.roles', []), true)) {
            flash('error', 'El rol seleccionado no es valido.');
            redirect('admin/usuarios');
        }

        $userModel = new User();
        if ($userModel->emailExistsForOtherUser($email, $id)) {
            flash('error', 'Ese correo ya pertenece a otro usuario.');
            redirect('admin/usuarios');
        }

        $userModel->updateByAdmin($id, [
            'nombre' => $name,
            'apellidos' => trim((string) $this->input('apellidos', FILTER_SANITIZE_SPECIAL_CHARS)),
            'email' => $email,
            'telefono' => trim((string) $this->input('telefono', FILTER_SANITIZE_SPECIAL_CHARS)),
            'dni' => trim((string) $this->input('dni', FILTER_SANITIZE_SPECIAL_CHARS)),
            'rol' => $role,
            'password' => ($_POST['password'] ?? '') !== '' ? password_hash((string) $_POST['password'], PASSWORD_DEFAULT) : null,
        ]);

        if ($id === (int) $currentUser['id']) {
            session_put('auth_user', $userModel->findById($id));
        }

        flash('success', 'Usuario actualizado correctamente.');
        redirect('admin/usuarios');
    }

    public function deactivateUser(): void
    {
        if (!verify_csrf()) {
            flash('error', 'No se pudo desactivar el usuario.');
            redirect('admin/usuarios');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $currentUser = auth_user();

        if ($id === (int) $currentUser['id']) {
            flash('error', 'No puedes desactivar tu propia cuenta.');
            redirect('admin/usuarios');
        }

        if ($id > 0) {
            (new User())->softDeactivate($id);
            flash('success', 'Usuario desactivado correctamente.');
        }

        redirect('admin/usuarios');
    }

    public function deleteUser(): void
    {
        if (!verify_csrf()) {
            flash('error', 'No se pudo eliminar el usuario.');
            redirect('admin/usuarios');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $currentUser = auth_user();

        if ($id === (int) $currentUser['id']) {
            flash('error', 'No puedes eliminar tu propia cuenta.');
            redirect('admin/usuarios');
        }

        if ($id > 0) {
            (new User())->softDelete($id);
            flash('success', 'Usuario eliminado correctamente.');
        }

        redirect('admin/usuarios');
    }

    public function reports(): void
    {
        $orderModel = new Order();

        $this->view('admin/reports/index', [
            'title' => 'Informes',
            'salesTotal' => $orderModel->getTotalRevenue(),
            'topProducts' => $orderModel->getTopSellingProducts(),
            'monthlyRevenue' => $orderModel->getMonthlyRevenue(),
        ]);
    }
}

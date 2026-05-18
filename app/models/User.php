<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    public function findActiveByEmail(string $email): ?array
    {
        $sql = 'SELECT * FROM usuarios WHERE email = :email AND deleted_at IS NULL LIMIT 1';
        $statement = $this->db->prepare($sql);
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();

        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT id, nombre, apellidos, email, telefono, dni, rol, activo, ultimo_acceso, created_at, updated_at
                FROM usuarios
                WHERE id = :id AND deleted_at IS NULL
                LIMIT 1';
        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $id]);
        $user = $statement->fetch();

        return $user ?: null;
    }

    public function emailExists(string $email): bool
    {
        $sql = 'SELECT COUNT(*) FROM usuarios WHERE email = :email AND deleted_at IS NULL';
        $statement = $this->db->prepare($sql);
        $statement->execute(['email' => $email]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function emailExistsForOtherUser(string $email, int $excludedId): bool
    {
        $sql = 'SELECT COUNT(*) FROM usuarios WHERE email = :email AND id <> :id AND deleted_at IS NULL';
        $statement = $this->db->prepare($sql);
        $statement->execute([
            'email' => $email,
            'id' => $excludedId,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO usuarios (nombre, apellidos, email, password, telefono, dni, rol, activo)
                VALUES (:nombre, :apellidos, :email, :password, :telefono, :dni, :rol, :activo)';

        $statement = $this->db->prepare($sql);
        $statement->execute([
            'nombre' => $data['nombre'],
            'apellidos' => $data['apellidos'] ?: null,
            'email' => $data['email'],
            'password' => $data['password'],
            'telefono' => $data['telefono'] ?: null,
            'dni' => $data['dni'] ?: null,
            'rol' => $data['rol'] ?? 'cliente',
            'activo' => $data['activo'] ?? 1,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function touchLastAccess(int $id): void
    {
        $sql = 'UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = :id';
        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $id]);
    }

    public function updateProfile(int $id, array $data): void
    {
        $sql = 'UPDATE usuarios
                SET nombre = :nombre,
                    apellidos = :apellidos,
                    email = :email,
                    telefono = :telefono,
                    dni = :dni,
                    password = COALESCE(:password, password)
                WHERE id = :id';

        $statement = $this->db->prepare($sql);
        $statement->execute([
            'id' => $id,
            'nombre' => $data['nombre'],
            'apellidos' => $data['apellidos'] ?: null,
            'email' => $data['email'],
            'telefono' => $data['telefono'] ?: null,
            'dni' => $data['dni'] ?: null,
            'password' => $data['password'],
        ]);
    }

    public function countByRole(string $role): int
    {
        $sql = 'SELECT COUNT(*) FROM usuarios WHERE rol = :rol AND activo = 1 AND deleted_at IS NULL';
        $statement = $this->db->prepare($sql);
        $statement->execute(['rol' => $role]);

        return (int) $statement->fetchColumn();
    }

    public function getBackofficeUsers(string $role = '', string $search = ''): array
    {
        $conditions = ['deleted_at IS NULL'];
        $params = [];

        if ($role !== '' && in_array($role, config('app.roles', []), true)) {
            $conditions[] = 'rol = :rol';
            $params['rol'] = $role;
        }

        if ($search !== '') {
            $conditions[] = '(nombre LIKE :search OR apellidos LIKE :search OR email LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }

        $sql = 'SELECT id, nombre, apellidos, email, telefono, dni, rol, activo, ultimo_acceso, created_at
                FROM usuarios
                WHERE ' . implode(' AND ', $conditions) . '
                ORDER BY activo DESC, created_at DESC';

        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    public function updateByAdmin(int $id, array $data): void
    {
        $sql = 'UPDATE usuarios
                SET nombre = :nombre,
                    apellidos = :apellidos,
                    email = :email,
                    telefono = :telefono,
                    dni = :dni,
                    rol = :rol,
                    password = COALESCE(:password, password)
                WHERE id = :id';

        $statement = $this->db->prepare($sql);
        $statement->execute([
            'id' => $id,
            'nombre' => $data['nombre'],
            'apellidos' => $data['apellidos'] ?: null,
            'email' => $data['email'],
            'telefono' => $data['telefono'] ?: null,
            'dni' => $data['dni'] ?: null,
            'rol' => $data['rol'],
            'password' => $data['password'],
        ]);
    }

    public function softDeactivate(int $id): void
    {
        $sql = 'UPDATE usuarios SET activo = 0 WHERE id = :id';
        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $id]);
    }

    public function softDelete(int $id): void
    {
        $sql = 'UPDATE usuarios
                SET activo = 0,
                    deleted_at = NOW()
                WHERE id = :id';

        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $id]);
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Category extends Model
{
    public function countAllActive(): int
    {
        $sql = 'SELECT COUNT(*) FROM categorias WHERE activo = 1 AND deleted_at IS NULL';
        return (int) $this->db->query($sql)->fetchColumn();
    }

    public function getFilterOptions(): array
    {
        $sql = 'SELECT id, nombre, slug, parent_id
                FROM categorias
                WHERE activo = 1 AND deleted_at IS NULL
                ORDER BY nombre ASC';

        return $this->db->query($sql)->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $sql = 'SELECT * FROM categorias WHERE slug = :slug AND activo = 1 AND deleted_at IS NULL LIMIT 1';
        $statement = $this->db->prepare($sql);
        $statement->execute(['slug' => $slug]);
        $category = $statement->fetch();

        return $category ?: null;
    }

    public function getAllForBackoffice(): array
    {
        $sql = 'SELECT c.*, p.nombre AS parent_name
                FROM categorias c
                LEFT JOIN categorias p ON p.id = c.parent_id
                WHERE c.deleted_at IS NULL
                ORDER BY c.activo DESC, c.orden ASC, c.nombre ASC';

        return $this->db->query($sql)->fetchAll();
    }

    public function getParentOptions(): array
    {
        $sql = 'SELECT id, nombre FROM categorias WHERE activo = 1 AND deleted_at IS NULL ORDER BY nombre ASC';
        return $this->db->query($sql)->fetchAll();
    }

    public function create(array $data): int
    {
        $slug = $this->resolveUniqueSlug($data['slug'] ?: $data['nombre']);
        $sql = 'INSERT INTO categorias (parent_id, nombre, slug, descripcion, imagen, orden, activo)
                VALUES (:parent_id, :nombre, :slug, :descripcion, :imagen, :orden, 1)';

        $statement = $this->db->prepare($sql);
        $statement->execute([
            'parent_id' => $data['parent_id'],
            'nombre' => $data['nombre'],
            'slug' => $slug,
            'descripcion' => $data['descripcion'] ?: null,
            'imagen' => $data['imagen'] ?: null,
            'orden' => $data['orden'] ?? 0,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $slug = $this->resolveUniqueSlug($data['slug'] ?: $data['nombre'], $id);

        $sql = 'UPDATE categorias
                SET parent_id = :parent_id,
                    nombre = :nombre,
                    slug = :slug,
                    descripcion = :descripcion,
                    imagen = :imagen,
                    orden = :orden
                WHERE id = :id AND deleted_at IS NULL';

        $statement = $this->db->prepare($sql);
        $statement->execute([
            'id' => $id,
            'parent_id' => $data['parent_id'],
            'nombre' => $data['nombre'],
            'slug' => $slug,
            'descripcion' => $data['descripcion'] ?: null,
            'imagen' => $data['imagen'] ?: null,
            'orden' => $data['orden'] ?? 0,
        ]);
    }

    public function softDelete(int $id): void
    {
        $sql = 'UPDATE categorias SET activo = 0 WHERE id = :id';
        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $id]);
    }

    private function resolveUniqueSlug(string $source, ?int $exceptId = null): string
    {
        $base = slugify($source);
        $slug = $base;
        $counter = 1;

        while ($this->slugExists($slug, $exceptId)) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?int $exceptId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM categorias WHERE slug = :slug';
        $params = ['slug' => $slug];

        if ($exceptId !== null) {
            $sql .= ' AND id <> :id';
            $params['id'] = $exceptId;
        }

        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return (int) $statement->fetchColumn() > 0;
    }
}

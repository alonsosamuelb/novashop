<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

class Product extends Model
{
    public function countAllActive(): int
    {
        $sql = 'SELECT COUNT(*) FROM productos WHERE activo = 1 AND deleted_at IS NULL';
        return (int) $this->db->query($sql)->fetchColumn();
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT * FROM productos WHERE id = :id AND deleted_at IS NULL LIMIT 1';
        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $id]);
        $product = $statement->fetch();

        return $product ?: null;
    }

    public function findAvailableByIds(array $ids): array
    {
        $ids = array_values(array_filter(array_map('intval', $ids)));

        if ($ids === []) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT p.*, c.nombre AS categoria_nombre, c.slug AS categoria_slug
                FROM productos p
                INNER JOIN categorias c ON c.id = p.categoria_id
                WHERE p.id IN ($placeholders)
                  AND p.activo = 1
                  AND p.deleted_at IS NULL
                  AND c.activo = 1
                ORDER BY p.nombre ASC";

        $statement = $this->db->prepare($sql);
        $statement->execute($ids);

        return $statement->fetchAll();
    }

    public function getAllForBackoffice(string $search = '', string $sort = ''): array
    {
        $conditions = ['p.deleted_at IS NULL'];
        $params = [];

        if ($search !== '') {
            $conditions[] = '(p.nombre LIKE :search OR p.codigo LIKE :search OR c.nombre LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }

        $allowedSorts = [
            'nombre_asc' => 'p.nombre ASC',
            'nombre_desc' => 'p.nombre DESC',
            'stock_asc' => 'p.stock ASC',
            'stock_desc' => 'p.stock DESC',
            'precio_desc' => 'COALESCE(p.precio_oferta, p.precio) DESC',
        ];
        $orderBy = $allowedSorts[$sort] ?? 'p.activo DESC, p.created_at DESC, p.id DESC';

        $sql = 'SELECT p.*, c.nombre AS categoria_nombre
                FROM productos p
                INNER JOIN categorias c ON c.id = p.categoria_id
                WHERE ' . implode(' AND ', $conditions) . '
                ORDER BY ' . $orderBy;

        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    public function create(array $data): int
    {
        $slug = $this->resolveUniqueSlug($data['slug'] ?: $data['nombre']);
        $sql = 'INSERT INTO productos (
                    categoria_id, codigo, nombre, slug, descripcion, precio, precio_oferta,
                    stock, imagen, destacado, activo
                ) VALUES (
                    :categoria_id, :codigo, :nombre, :slug, :descripcion, :precio, :precio_oferta,
                    :stock, :imagen, :destacado, 1
                )';

        $statement = $this->db->prepare($sql);
        $statement->execute([
            'categoria_id' => $data['categoria_id'],
            'codigo' => $data['codigo'],
            'nombre' => $data['nombre'],
            'slug' => $slug,
            'descripcion' => $data['descripcion'],
            'precio' => $data['precio'],
            'precio_oferta' => $data['precio_oferta'],
            'stock' => $data['stock'],
            'imagen' => $data['imagen'] ?: 'spiderman.jpg',
            'destacado' => $data['destacado'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $slug = $this->resolveUniqueSlug($data['slug'] ?: $data['nombre'], $id);
        $sql = 'UPDATE productos
                SET categoria_id = :categoria_id,
                    codigo = :codigo,
                    nombre = :nombre,
                    slug = :slug,
                    descripcion = :descripcion,
                    precio = :precio,
                    precio_oferta = :precio_oferta,
                    stock = :stock,
                    imagen = :imagen,
                    destacado = :destacado
                WHERE id = :id AND deleted_at IS NULL';

        $statement = $this->db->prepare($sql);
        $statement->execute([
            'id' => $id,
            'categoria_id' => $data['categoria_id'],
            'codigo' => $data['codigo'],
            'nombre' => $data['nombre'],
            'slug' => $slug,
            'descripcion' => $data['descripcion'],
            'precio' => $data['precio'],
            'precio_oferta' => $data['precio_oferta'],
            'stock' => $data['stock'],
            'imagen' => $data['imagen'] ?: 'spiderman.jpg',
            'destacado' => $data['destacado'],
        ]);
    }

    public function softDelete(int $id): void
    {
        $sql = 'UPDATE productos SET activo = 0 WHERE id = :id';
        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $id]);
    }

    public function getFeatured(int $limit = 8): array
    {
        $sql = 'SELECT p.*, c.nombre AS categoria_nombre, c.slug AS categoria_slug
                FROM productos p
                INNER JOIN categorias c ON c.id = p.categoria_id
                WHERE p.activo = 1 AND p.deleted_at IS NULL AND c.activo = 1 AND p.destacado = 1
                ORDER BY p.created_at DESC, p.id DESC
                LIMIT :limit';

        $statement = $this->db->prepare($sql);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function paginateCatalog(array $filters, int $page = 1, int $perPage = 9): array
    {
        $conditions = ['p.activo = 1', 'p.deleted_at IS NULL', 'c.activo = 1'];
        $params = [];

        if (!empty($filters['search'])) {
            $conditions[] = '(p.nombre LIKE :search_name OR p.codigo LIKE :search_code OR c.nombre LIKE :search_category)';
            $searchTerm = '%' . $filters['search'] . '%';
            $params['search_name'] = $searchTerm;
            $params['search_code'] = $searchTerm;
            $params['search_category'] = $searchTerm;
        }

        if (!empty($filters['category_slug'])) {
            $conditions[] = '(c.slug = :category_slug_direct OR c.parent_id = (
                SELECT id FROM categorias WHERE slug = :category_slug_parent LIMIT 1
            ))';
            $params['category_slug_direct'] = $filters['category_slug'];
            $params['category_slug_parent'] = $filters['category_slug'];
        }

        if (!empty($filters['min_price'])) {
            $conditions[] = 'COALESCE(p.precio_oferta, p.precio) >= :min_price';
            $params['min_price'] = (float) $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $conditions[] = 'COALESCE(p.precio_oferta, p.precio) <= :max_price';
            $params['max_price'] = (float) $filters['max_price'];
        }

        $where = implode(' AND ', $conditions);
        $allowedSorts = [
            'nombre_asc' => 'p.nombre ASC, p.id ASC',
            'nombre_desc' => 'p.nombre DESC, p.id DESC',
            'precio_asc' => 'COALESCE(p.precio_oferta, p.precio) ASC, p.id ASC',
            'precio_desc' => 'COALESCE(p.precio_oferta, p.precio) DESC, p.id DESC',
            'nuevos' => 'p.created_at DESC, p.id DESC',
        ];
        $sort = $allowedSorts[$filters['sort'] ?? 'nuevos'] ?? $allowedSorts['nuevos'];

        $countSql = 'SELECT COUNT(*)
                     FROM productos p
                     INNER JOIN categorias c ON c.id = p.categoria_id
                     WHERE ' . $where;
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        $offset = max(0, ($page - 1) * $perPage);

        $sql = 'SELECT p.*, c.nombre AS categoria_nombre, c.slug AS categoria_slug
                FROM productos p
                INNER JOIN categorias c ON c.id = p.categoria_id
                WHERE ' . $where . '
                ORDER BY ' . $sort . '
                LIMIT :limit OFFSET :offset';

        $statement = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $statement->bindValue(':' . $key, $value);
        }

        $statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return [
            'items' => $statement->fetchAll(),
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'pages' => (int) max(1, ceil($total / $perPage)),
        ];
    }

    public function findActiveBySlug(string $slug): ?array
    {
        $sql = 'SELECT p.*, c.nombre AS categoria_nombre, c.slug AS categoria_slug
                FROM productos p
                INNER JOIN categorias c ON c.id = p.categoria_id
                WHERE p.slug = :slug
                  AND p.activo = 1
                  AND p.deleted_at IS NULL
                  AND c.activo = 1
                LIMIT 1';

        $statement = $this->db->prepare($sql);
        $statement->execute(['slug' => $slug]);
        $product = $statement->fetch();

        return $product ?: null;
    }

    public function getRelated(int $categoryId, int $excludeId, int $limit = 4): array
    {
        $sql = 'SELECT id, nombre, slug, precio, precio_oferta, imagen
                FROM productos
                WHERE categoria_id = :category_id
                  AND id <> :exclude_id
                  AND activo = 1
                  AND deleted_at IS NULL
                ORDER BY destacado DESC, created_at DESC, id DESC
                LIMIT :limit';

        $statement = $this->db->prepare($sql);
        $statement->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $statement->bindValue(':exclude_id', $excludeId, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
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
        $sql = 'SELECT COUNT(*) FROM productos WHERE slug = :slug';
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

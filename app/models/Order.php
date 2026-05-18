<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Services\OrderNotifier;
use Exception;
use Throwable;

class Order extends Model
{
    public function getByUserId(int $userId): array
    {
        $sql = 'SELECT * FROM pedidos WHERE usuario_id = :usuario_id ORDER BY fecha_pedido DESC';
        $statement = $this->db->prepare($sql);
        $statement->execute(['usuario_id' => $userId]);

        return $statement->fetchAll();
    }

    public function getAllForBackoffice(string $status = ''): array
    {
        $sql = 'SELECT * FROM pedidos';
        $params = [];

        if ($status !== '' && in_array($status, config('app.order_statuses', []), true)) {
            $sql .= ' WHERE estado = :estado';
            $params['estado'] = $status;
        }

        $sql .= ' ORDER BY fecha_pedido DESC';
        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    public function countByStatus(string $status): int
    {
        $sql = 'SELECT COUNT(*) FROM pedidos WHERE estado = :estado';
        $statement = $this->db->prepare($sql);
        $statement->execute(['estado' => $status]);

        return (int) $statement->fetchColumn();
    }

    public function createFromCheckout(array $customerData, array $cartItems, array $products, ?array $user = null): int
    {
        $shippingCost = (float) config('app.shipping.base_cost', 4.99);
        $subtotal = 0.0;

        foreach ($products as $product) {
            $quantity = (int) ($cartItems[$product['id']] ?? 0);
            $unitPrice = (float) ($product['precio_oferta'] ?: $product['precio']);
            $subtotal += $unitPrice * $quantity;
        }

        $total = $subtotal + $shippingCost;
        $number = $this->generateOrderNumber();

        $this->db->beginTransaction();

        try {
            $orderSql = 'INSERT INTO pedidos (
                            numero, usuario_id, cliente_nombre, cliente_email, cliente_telefono,
                            direccion_envio, ciudad, provincia, codigo_postal, pais, observaciones,
                            subtotal, gastos_envio, total, estado, metodo_pago, es_invitado, activo, fecha_pedido
                        ) VALUES (
                            :numero, :usuario_id, :cliente_nombre, :cliente_email, :cliente_telefono,
                            :direccion_envio, :ciudad, :provincia, :codigo_postal, :pais, :observaciones,
                            :subtotal, :gastos_envio, :total, :estado, :metodo_pago, :es_invitado, :activo, NOW()
                        )';

            $statement = $this->db->prepare($orderSql);
            $statement->execute([
                'numero' => $number,
                'usuario_id' => $user['id'] ?? null,
                'cliente_nombre' => trim($customerData['nombre'] . ' ' . $customerData['apellidos']),
                'cliente_email' => $customerData['email'],
                'cliente_telefono' => $customerData['telefono'] ?: null,
                'direccion_envio' => $customerData['direccion_envio'],
                'ciudad' => $customerData['ciudad'],
                'provincia' => $customerData['provincia'],
                'codigo_postal' => $customerData['codigo_postal'],
                'pais' => $customerData['pais'],
                'observaciones' => $customerData['observaciones'] ?: null,
                'subtotal' => $subtotal,
                'gastos_envio' => $shippingCost,
                'total' => $total,
                'estado' => 'pendiente',
                'metodo_pago' => 'simulado',
                'es_invitado' => $user ? 0 : 1,
                'activo' => 1,
            ]);

            $orderId = (int) $this->db->lastInsertId();

            $detailSql = 'INSERT INTO detalle_pedido (
                            pedido_id, producto_id, codigo_producto, nombre_producto,
                            precio_unitario, cantidad, subtotal
                          ) VALUES (
                            :pedido_id, :producto_id, :codigo_producto, :nombre_producto,
                            :precio_unitario, :cantidad, :subtotal
                          )';
            $detailStatement = $this->db->prepare($detailSql);

            $stockSql = 'UPDATE productos
                         SET stock = stock - :cantidad_descontar
                         WHERE id = :id AND stock >= :cantidad_disponible';
            $stockStatement = $this->db->prepare($stockSql);

            foreach ($products as $product) {
                $quantity = (int) ($cartItems[$product['id']] ?? 0);
                $unitPrice = (float) ($product['precio_oferta'] ?: $product['precio']);
                $lineSubtotal = $unitPrice * $quantity;

                $detailStatement->execute([
                    'pedido_id' => $orderId,
                    'producto_id' => $product['id'],
                    'codigo_producto' => $product['codigo'],
                    'nombre_producto' => $product['nombre'],
                    'precio_unitario' => $unitPrice,
                    'cantidad' => $quantity,
                    'subtotal' => $lineSubtotal,
                ]);

                $stockStatement->execute([
                    'cantidad_descontar' => $quantity,
                    'cantidad_disponible' => $quantity,
                    'id' => $product['id'],
                ]);

                if ($stockStatement->rowCount() !== 1) {
                    throw new Exception('No hay stock suficiente para completar el pedido.');
                }
            }

            $this->db->commit();

            $order = $this->findById($orderId);
            if ($order) {
                (new OrderNotifier())->sendOrderConfirmation($order);
            }

            return $orderId;
        } catch (Throwable $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT * FROM pedidos WHERE id = :id LIMIT 1';
        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $id]);
        $order = $statement->fetch();

        if (!$order) {
            return null;
        }

        $detailsSql = 'SELECT * FROM detalle_pedido WHERE pedido_id = :pedido_id ORDER BY id ASC';
        $detailsStatement = $this->db->prepare($detailsSql);
        $detailsStatement->execute(['pedido_id' => $id]);
        $order['items'] = $detailsStatement->fetchAll();

        return $order;
    }

    public function updateStatus(int $id, string $status): void
    {
        $sql = 'UPDATE pedidos SET estado = :estado WHERE id = :id';
        $statement = $this->db->prepare($sql);
        $statement->execute([
            'id' => $id,
            'estado' => $status,
        ]);
    }

    public function getTotalRevenue(): float
    {
        $sql = 'SELECT COALESCE(SUM(total), 0) FROM pedidos WHERE activo = 1';
        return (float) $this->db->query($sql)->fetchColumn();
    }

    public function getTopSellingProducts(int $limit = 5): array
    {
        $sql = 'SELECT nombre_producto, SUM(cantidad) AS unidades, SUM(subtotal) AS ingresos
                FROM detalle_pedido
                GROUP BY nombre_producto
                ORDER BY unidades DESC, ingresos DESC
                LIMIT :limit';

        $statement = $this->db->prepare($sql);
        $statement->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getMonthlyRevenue(): array
    {
        $sql = "SELECT DATE_FORMAT(fecha_pedido, '%Y-%m') AS mes, SUM(total) AS ingresos
                FROM pedidos
                WHERE activo = 1
                GROUP BY DATE_FORMAT(fecha_pedido, '%Y-%m')
                ORDER BY mes ASC";

        return $this->db->query($sql)->fetchAll();
    }

    private function generateOrderNumber(): string
    {
        return 'PED' . date('ymdHis') . strtoupper(bin2hex(random_bytes(2)));
    }
}

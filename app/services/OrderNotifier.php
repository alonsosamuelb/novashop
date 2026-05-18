<?php

declare(strict_types=1);

namespace App\Services;

class OrderNotifier
{
    public function sendOrderConfirmation(array $order): void
    {
        $logDir = BASE_PATH . '/storage/logs';

        if (!is_dir($logDir)) {
            mkdir($logDir, 0775, true);
        }

        $lines = [];
        $lines[] = str_repeat('=', 72);
        $lines[] = 'Fecha: ' . date('Y-m-d H:i:s');
        $lines[] = 'Para: ' . $order['cliente_email'];
        $lines[] = 'Asunto: Confirmacion de pedido ' . $order['numero'];
        $lines[] = 'Hola ' . $order['cliente_nombre'] . ',';
        $lines[] = 'Tu pedido ha sido registrado correctamente.';
        $lines[] = 'Numero: ' . $order['numero'];
        $lines[] = 'Estado: ' . ucfirst($order['estado']);
        $lines[] = 'Total: ' . format_price($order['total']);
        $lines[] = 'Productos:';

        foreach ($order['items'] as $item) {
            $lines[] = '- ' . $item['nombre_producto'] . ' x ' . $item['cantidad'] . ' = ' . format_price($item['subtotal']);
        }

        $lines[] = '';

        file_put_contents($logDir . '/order_emails.log', implode(PHP_EOL, $lines) . PHP_EOL, FILE_APPEND);
    }
}

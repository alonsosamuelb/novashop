<section class="page-banner rounded-4 p-4 p-md-5 mb-4">
    <h1 class="display-6 fw-bold mb-2">Detalle del pedido</h1>
    <p class="lead mb-0"><?= e($order['numero']) ?></p>
</section>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order['items'] as $item): ?>
                                <tr>
                                    <td><?= e($item['nombre_producto']) ?></td>
                                    <td><?= e((string) $item['cantidad']) ?></td>
                                    <td><?= e(format_price($item['precio_unitario'])) ?></td>
                                    <td><?= e(format_price($item['subtotal'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Datos del pedido</h2>
                <p class="mb-2"><strong>Cliente:</strong> <?= e($order['cliente_nombre']) ?></p>
                <p class="mb-2"><strong>Email:</strong> <?= e($order['cliente_email']) ?></p>
                <p class="mb-2"><strong>Direccion:</strong> <?= e($order['direccion_envio']) ?></p>
                <p class="mb-2"><strong>Estado:</strong> <?= e(ucfirst($order['estado'])) ?></p>
                <p class="mb-0"><strong>Total:</strong> <?= e(format_price($order['total'])) ?></p>
            </div>
        </div>
    </div>
</div>

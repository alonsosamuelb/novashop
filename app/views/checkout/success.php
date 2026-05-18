<section class="page-banner rounded-4 p-4 p-md-5 mb-4">
    <h1 class="display-6 fw-bold mb-2">Pedido confirmado</h1>
    <p class="lead mb-0">Tu compra se ha registrado correctamente con estado inicial pendiente.</p>
</section>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex flex-wrap justify-content-between gap-3 mb-4">
                    <div>
                        <div class="text-muted small">Numero de pedido</div>
                        <div class="h4 mb-0"><?= e($order['numero']) ?></div>
                    </div>
                    <div>
                        <div class="text-muted small">Estado</div>
                        <span class="badge text-bg-warning"><?= e(ucfirst($order['estado'])) ?></span>
                    </div>
                </div>

                <h2 class="h5">Datos de envio</h2>
                <p class="mb-4">
                    <?= e($order['cliente_nombre']) ?><br>
                    <?= e($order['direccion_envio']) ?><br>
                    <?= e($order['codigo_postal']) ?>, <?= e($order['ciudad']) ?>, <?= e($order['provincia']) ?><br>
                    <?= e($order['pais']) ?>
                </p>

                <h2 class="h5">Detalle del pedido</h2>
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

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Resumen economico</h2>
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <span><?= e(format_price($order['subtotal'])) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Envio</span>
                    <span><?= e(format_price($order['gastos_envio'])) ?></span>
                </div>
                <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                    <span>Total</span>
                    <span><?= e(format_price($order['total'])) ?></span>
                </div>

                <div class="d-grid gap-2">
                    <a href="<?= e(app_url('catalogo')) ?>" class="btn btn-primary">Seguir comprando</a>
                    <a href="<?= e(app_url()) ?>" class="btn btn-outline-secondary">Volver al inicio</a>
                </div>
                <p class="small text-muted mt-3 mb-0">
                    Confirmacion simulada registrada en <code>storage/logs/order_emails.log</code>.
                </p>
            </div>
        </div>
    </div>
</div>

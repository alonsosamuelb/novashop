<section class="page-banner rounded-4 p-4 p-md-5 mb-4">
    <h1 class="display-6 fw-bold mb-2">Mis pedidos</h1>
    <p class="lead mb-0">Historial completo de pedidos del cliente autenticado.</p>
</section>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <?php if (empty($orders)): ?>
            <p class="text-muted mb-0">No hay pedidos registrados para esta cuenta.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Numero</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= e($order['numero']) ?></td>
                                <td><?= e(date('d/m/Y H:i', strtotime($order['fecha_pedido']))) ?></td>
                                <td><span class="badge text-bg-light"><?= e(ucfirst($order['estado'])) ?></span></td>
                                <td><?= e(format_price($order['total'])) ?></td>
                                <td><a href="<?= e(app_url('mis-pedidos/ver?id=' . $order['id'])) ?>" class="btn btn-sm btn-outline-dark">Detalle</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

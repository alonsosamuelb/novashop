<section class="page-banner rounded-4 p-4 p-md-5 mb-4">
    <h1 class="display-6 fw-bold mb-2">Mi cuenta</h1>
    <p class="lead mb-0">Consulta tus datos y el historial de pedidos realizados.</p>
</section>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Resumen</h2>
                <p class="mb-1"><strong>Nombre:</strong> <?= e(trim(($user['nombre'] ?? '') . ' ' . ($user['apellidos'] ?? ''))) ?></p>
                <p class="mb-1"><strong>Email:</strong> <?= e($user['email'] ?? '') ?></p>
                <p class="mb-3"><strong>Rol:</strong> <?= e(ucfirst($user['rol'] ?? 'cliente')) ?></p>
                <div class="d-grid gap-2">
                    <a href="<?= e(app_url('mi-perfil')) ?>" class="btn btn-primary">Editar perfil</a>
                    <a href="<?= e(app_url('mis-pedidos')) ?>" class="btn btn-outline-secondary">Ver pedidos</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Ultimos pedidos</h2>
                <?php if (empty($orders)): ?>
                    <p class="text-muted mb-0">Todavia no has realizado pedidos.</p>
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
                                <?php foreach (array_slice($orders, 0, 5) as $order): ?>
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
    </div>
</div>

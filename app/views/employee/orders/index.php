<section class="page-banner rounded-4 p-4 p-md-5 mb-4">
    <h1 class="display-6 fw-bold mb-2">Gestion de pedidos</h1>
    <p class="lead mb-0">Consulta todos los pedidos y actualiza su estado.</p>
</section>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form method="GET" action="<?= e(app_url('empleado/pedidos')) ?>" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Filtrar por estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos</option>
                    <?php foreach (config('app.order_statuses', []) as $orderStatus): ?>
                        <option value="<?= e($orderStatus) ?>" <?= $status === $orderStatus ? 'selected' : '' ?>><?= e(ucfirst($orderStatus)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-grid">
                <button type="submit" class="btn btn-dark">Aplicar filtro</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Numero</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= e($order['numero']) ?></td>
                            <td>
                                <div class="fw-semibold"><?= e($order['cliente_nombre']) ?></div>
                                <div class="small text-muted"><?= e($order['cliente_email']) ?></div>
                            </td>
                            <td><?= e(date('d/m/Y H:i', strtotime($order['fecha_pedido']))) ?></td>
                            <td><?= e(format_price($order['total'])) ?></td>
                            <td>
                                <form action="<?= e(app_url('empleado/pedidos/estado')) ?>" method="POST" class="d-flex gap-2">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= e((string) $order['id']) ?>">
                                    <select name="estado" class="form-select form-select-sm">
                                        <?php foreach (config('app.order_statuses', []) as $orderStatus): ?>
                                            <option value="<?= e($orderStatus) ?>" <?= $order['estado'] === $orderStatus ? 'selected' : '' ?>><?= e(ucfirst($orderStatus)) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                                </form>
                            </td>
                            <td>
                                <a href="<?= e(app_url('empleado/pedidos/ver?id=' . $order['id'])) ?>" class="btn btn-sm btn-outline-dark">Detalle</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

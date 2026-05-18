<section class="page-banner rounded-4 p-4 p-md-5 mb-4">
    <h1 class="display-6 fw-bold mb-2">Informes</h1>
    <p class="lead mb-0">Resumen comercial con ventas acumuladas, productos mas vendidos e ingresos mensuales.</p>
</section>

<?php
$salesTotal = isset($salesTotal) ? (float) $salesTotal : 0.0;
$topProducts = isset($topProducts) && is_array($topProducts) ? $topProducts : [];
$monthlyRevenue = isset($monthlyRevenue) && is_array($monthlyRevenue) ? $monthlyRevenue : [];
?>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="info-card p-4 rounded-4 h-100">
            <div class="text-muted small">Ventas totales</div>
            <div class="display-6 fw-bold"><?= e(format_price($salesTotal)) ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-card p-4 rounded-4 h-100">
            <div class="text-muted small">Productos destacados</div>
            <div class="display-6 fw-bold"><?= e((string) count($topProducts)) ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-card p-4 rounded-4 h-100">
            <div class="text-muted small">Meses con ventas</div>
            <div class="display-6 fw-bold"><?= e((string) count($monthlyRevenue)) ?></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 mb-0">Productos mas vendidos</h2>
                    <a href="<?= e(app_url('admin')) ?>" class="btn btn-sm btn-outline-secondary">Volver al panel</a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Unidades</th>
                                <th>Ingresos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($topProducts === []): ?>
                                <tr>
                                    <td colspan="3" class="text-muted py-4">Todavia no hay ventas registradas.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($topProducts as $product): ?>
                                    <tr>
                                        <td class="fw-semibold"><?= e((string) ($product['nombre_producto'] ?? 'Producto')) ?></td>
                                        <td><?= e((string) (int) ($product['unidades'] ?? 0)) ?></td>
                                        <td><?= e(format_price((float) ($product['ingresos'] ?? 0))) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Ingresos por mes</h2>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Mes</th>
                                <th>Ingresos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($monthlyRevenue === []): ?>
                                <tr>
                                    <td colspan="2" class="text-muted py-4">No hay meses con facturacion todavia.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($monthlyRevenue as $month): ?>
                                    <tr>
                                        <td><?= e((string) ($month['mes'] ?? '-')) ?></td>
                                        <td><?= e(format_price((float) ($month['ingresos'] ?? 0))) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

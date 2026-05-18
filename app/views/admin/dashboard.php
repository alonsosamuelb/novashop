<section class="page-banner rounded-4 p-4 p-md-5 mb-4">
    <h1 class="display-6 fw-bold mb-2">Panel de administracion</h1>
</section>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="info-card p-4 rounded-4 h-100">
            <div class="text-muted small">Clientes activos</div>
            <div class="display-6 fw-bold"><?= e((string) $stats['clientes']) ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-card p-4 rounded-4 h-100">
            <div class="text-muted small">Empleados activos</div>
            <div class="display-6 fw-bold"><?= e((string) $stats['empleados']) ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-card p-4 rounded-4 h-100">
            <div class="text-muted small">Administradores</div>
            <div class="display-6 fw-bold"><?= e((string) $stats['administradores']) ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-card p-4 rounded-4 h-100">
            <div class="text-muted small">Ventas totales</div>
            <div class="display-6 fw-bold"><?= e(format_price($stats['ventas_totales'])) ?></div>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap gap-2">
    <a href="<?= e(app_url('admin/usuarios')) ?>" class="btn btn-primary">Gestionar usuarios</a>
    <a href="<?= e(app_url('admin/informes')) ?>" class="btn btn-dark">Ver informes</a>
    <a href="<?= e(app_url('empleado')) ?>" class="btn btn-outline-secondary">Panel de empleado</a>
</div>

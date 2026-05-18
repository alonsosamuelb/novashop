<section class="page-banner rounded-4 p-4 p-md-5 mb-4">
    <h1 class="display-6 fw-bold mb-2">Panel de empleado</h1>
</section>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="info-card p-4 rounded-4 h-100">
            <div class="text-muted small">Productos activos</div>
            <div class="display-6 fw-bold"><?= e((string) $stats['products']) ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-card p-4 rounded-4 h-100">
            <div class="text-muted small">Categorias activas</div>
            <div class="display-6 fw-bold"><?= e((string) $stats['categories']) ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-card p-4 rounded-4 h-100">
            <div class="text-muted small">Pedidos pendientes</div>
            <div class="display-6 fw-bold"><?= e((string) $stats['pending_orders']) ?></div>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap gap-2">
    <a href="<?= e(app_url('empleado/categorias')) ?>" class="btn btn-primary">Gestionar categorias</a>
    <a href="<?= e(app_url('empleado/productos')) ?>" class="btn btn-dark">Gestionar productos</a>
    <a href="<?= e(app_url('empleado/pedidos')) ?>" class="btn btn-outline-secondary">Gestionar pedidos</a>
</div>

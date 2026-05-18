<section class="page-banner rounded-4 p-4 p-md-5 mb-4">
    <h1 class="display-6 fw-bold mb-2">Finalizar compra</h1>
    <p class="lead mb-0">Completa tus datos de envio y confirma el pedido.</p>
</section>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h4 mb-0">Datos del cliente</h2>
                    <span class="badge <?= $user ? 'text-bg-success' : 'text-bg-secondary' ?>">
                        <?= $user ? 'Compra como usuario registrado' : 'Compra como invitado' ?>
                    </span>
                </div>

                <form action="<?= e(app_url('checkout')) ?>" method="POST" class="row g-3">
                    <?= csrf_field() ?>

                    <div class="col-md-6">
                        <label class="form-label" for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" value="<?= e((string) old('nombre', $user['nombre'] ?? '')) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="apellidos">Apellidos</label>
                        <input type="text" id="apellidos" name="apellidos" class="form-control" value="<?= e((string) old('apellidos', $user['apellidos'] ?? '')) ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?= e((string) old('email', $user['email'] ?? '')) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="telefono">Telefono</label>
                        <input type="text" id="telefono" name="telefono" class="form-control" value="<?= e((string) old('telefono', $user['telefono'] ?? '')) ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="direccion_envio">Direccion de envio</label>
                        <input type="text" id="direccion_envio" name="direccion_envio" class="form-control" value="<?= e((string) old('direccion_envio')) ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="ciudad">Ciudad</label>
                        <input type="text" id="ciudad" name="ciudad" class="form-control" value="<?= e((string) old('ciudad')) ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="provincia">Provincia</label>
                        <input type="text" id="provincia" name="provincia" class="form-control" value="<?= e((string) old('provincia')) ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="codigo_postal">Codigo postal</label>
                        <input type="text" id="codigo_postal" name="codigo_postal" class="form-control" value="<?= e((string) old('codigo_postal')) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="pais">Pais</label>
                        <input type="text" id="pais" name="pais" class="form-control" value="<?= e((string) old('pais', 'Espana')) ?>" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="observaciones">Observaciones</label>
                        <textarea id="observaciones" name="observaciones" class="form-control" rows="3"><?= e((string) old('observaciones')) ?></textarea>
                    </div>

                    <div class="col-12">
                        <div class="simulated-payment rounded-4 p-4">
                            <h3 class="h6">Confirmacion de pedido</h3>
                            <p class="mb-0 text-muted">Tu pedido se registra de forma inmediata y pasa a estado inicial <strong>pendiente</strong> para su preparacion y gestion interna.</p>
                        </div>
                    </div>

                    <div class="col-12 d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Confirmar pedido</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Resumen del pedido</h2>
                <?php foreach ($cart['items'] as $item): ?>
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                        <div>
                            <div class="fw-semibold"><?= e($item['product']['nombre']) ?></div>
                            <div class="small text-muted">Cantidad: <?= e((string) $item['quantity']) ?></div>
                        </div>
                        <div class="text-end"><?= e(format_price($item['subtotal'])) ?></div>
                    </div>
                <?php endforeach; ?>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <span><?= e(format_price($cart['subtotal'])) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Envio</span>
                    <span><?= e(format_price($cart['shipping'])) ?></span>
                </div>
                <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>Total</span>
                    <span><?= e(format_price($cart['total'])) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

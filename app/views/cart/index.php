<section class="page-banner rounded-4 p-4 p-md-5 mb-4">
    <h1 class="display-6 fw-bold mb-2">Carrito de compra</h1>
    <p class="lead mb-0">Gestiona cantidades, revisa el importe total y continua al checkout.</p>
</section>

<?php if (empty($cart['items'])): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-5 text-center">
            <h2 class="h4">Tu carrito esta vacio</h2>
            <p class="text-muted mb-4">Explora el catalogo y anade productos para comenzar tu pedido.</p>
            <a href="<?= e(app_url('catalogo')) ?>" class="btn btn-primary">Ir al catalogo</a>
        </div>
    </div>
<?php else: ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="<?= e(app_url('carrito/actualizar')) ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart['items'] as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="<?= e(asset_url('img/products/' . $item['product']['imagen'])) ?>" alt="<?= e($item['product']['nombre']) ?>" class="cart-thumb rounded-3">
                                                    <div>
                                                        <div class="fw-semibold"><?= e($item['product']['nombre']) ?></div>
                                                        <div class="small text-muted"><?= e($item['product']['codigo']) ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= e(format_price($item['unit_price'])) ?></td>
                                            <td>
                                                <input
                                                    type="number"
                                                    min="1"
                                                    max="<?= e((string) $item['product']['stock']) ?>"
                                                    name="quantities[<?= e((string) $item['product']['id']) ?>]"
                                                    class="form-control"
                                                    value="<?= e((string) $item['quantity']) ?>"
                                                >
                                            </td>
                                            <td><?= e(format_price($item['subtotal'])) ?></td>
                                            <td class="text-end">
                                                <button type="submit" name="remove_product_id" value="<?= e((string) $item['product']['id']) ?>" class="btn btn-outline-danger btn-sm">
                                                    Quitar
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex flex-wrap gap-2 justify-content-between mt-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-dark">Actualizar carrito</button>
                                <a href="<?= e(app_url('catalogo')) ?>" class="btn btn-outline-secondary">Seguir comprando</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h5 mb-3">Resumen</h2>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Productos</span>
                        <span><?= e((string) $cart['count']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span><?= e(format_price($cart['subtotal'])) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Envio</span>
                        <span><?= e(format_price($cart['shipping'])) ?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                        <span>Total</span>
                        <span><?= e(format_price($cart['total'])) ?></span>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="<?= e(app_url('checkout')) ?>" class="btn btn-primary">Finalizar compra</a>
                        <form action="<?= e(app_url('carrito/vaciar')) ?>" method="POST">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('¿Seguro que quieres vaciar el carrito?')">Vaciar carrito</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

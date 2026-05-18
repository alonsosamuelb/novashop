<div class="row g-5 align-items-start">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm overflow-hidden">
            <img src="<?= e(product_image_url($product['imagen'])) ?>" class="img-fluid product-detail-image" alt="<?= e($product['nombre']) ?>">
        </div>
    </div>

    <div class="col-lg-7">
        <a href="<?= e(app_url('catalogo')) ?>" class="text-decoration-none small text-uppercase fw-semibold">Volver al catalogo</a>
        <h1 class="display-6 fw-bold mt-2 mb-3"><?= e($product['nombre']) ?></h1>
        <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="badge text-bg-dark"><?= e($product['categoria_nombre']) ?></span>
            <span class="badge text-bg-light">Codigo <?= e($product['codigo']) ?></span>
            <span class="badge <?= (int) $product['stock'] > 0 ? 'text-bg-success' : 'text-bg-danger' ?>">
                <?= (int) $product['stock'] > 0 ? 'En stock' : 'Sin stock' ?>
            </span>
        </div>

        <div class="mb-4">
            <?php if (!empty($product['precio_oferta'])): ?>
                <span class="display-6 fw-bold text-primary"><?= e(format_price($product['precio_oferta'])) ?></span>
                <span class="fs-5 text-muted text-decoration-line-through ms-2"><?= e(format_price($product['precio'])) ?></span>
            <?php else: ?>
                <span class="display-6 fw-bold text-primary"><?= e(format_price($product['precio'])) ?></span>
            <?php endif; ?>
        </div>

        <p class="lead text-muted"><?= nl2br(e($product['descripcion'])) ?></p>

        <div class="card border-0 bg-white shadow-sm mt-4">
            <div class="card-body">
                <form action="<?= e(app_url('carrito/anadir')) ?>" method="POST" class="row g-3 align-items-end">
                    <?= csrf_field() ?>
                    <input type="hidden" name="product_id" value="<?= e((string) $product['id']) ?>">

                    <div class="col-sm-4">
                        <label class="form-label" for="quantity">Cantidad</label>
                        <input type="number" id="quantity" name="quantity" min="1" max="<?= e((string) $product['stock']) ?>" value="1" class="form-control">
                    </div>

                    <div class="col-sm-8 d-grid d-sm-flex gap-2">
                        <button type="submit" class="btn btn-primary">Anadir al carrito</button>
                        <a href="<?= e(app_url('carrito')) ?>" class="btn btn-outline-secondary">Ver carrito</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($related)): ?>
    <section class="mt-5">
        <h2 class="h3 mb-3">Relacionados</h2>
        <div class="row g-4">
            <?php foreach ($related as $item): ?>
                <div class="col-md-6 col-xl-3">
                    <article class="card border-0 shadow-sm h-100">
                        <img src="<?= e(product_image_url($item['imagen'])) ?>" class="card-img-top product-thumb" alt="<?= e($item['nombre']) ?>">
                        <div class="card-body">
                            <h3 class="h6"><?= e($item['nombre']) ?></h3>
                            <p class="text-primary fw-bold mb-3"><?= e(format_price($item['precio_oferta'] ?: $item['precio'])) ?></p>
                            <a href="<?= e(app_url('producto?slug=' . urlencode($item['slug']))) ?>" class="btn btn-outline-dark btn-sm">Ver producto</a>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

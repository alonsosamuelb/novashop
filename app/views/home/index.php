<section class="hero-section rounded-4 p-4 p-md-5 mb-4 text-white overflow-hidden">
    <div class="row align-items-center g-4">
        <div class="col-lg-7">
            <span class="badge text-bg-warning mb-3">Escaparate principal</span>
            <h1 class="display-4 fw-bold">Gaming, anime y fantasia para una coleccion con personalidad</h1>
          
            <div class="d-flex flex-wrap gap-2">
                <a href="<?= e(app_url('catalogo')) ?>" class="btn btn-light btn-lg">Ver catalogo</a>
                <?php if (!is_logged_in()): ?>
                    <a href="<?= e(app_url('registro')) ?>" class="btn btn-outline-light btn-lg">Crear cuenta</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="glass-card p-4 rounded-4">
                <h2 class="h5 mb-3">Categorias destacadas</h2>
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach (($homeCategories ?? []) as $category): ?>
                        <a href="<?= e(app_url('catalogo?categoria=' . urlencode($category['slug']))) ?>" class="btn btn-sm btn-outline-light rounded-pill">
                            <?= e($category['nombre']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="h3 mb-1">Productos destacados</h2>
            <p class="text-muted mb-0">Selecciones principales para la portada de la tienda.</p>
        </div>
        <a href="<?= e(app_url('catalogo')) ?>" class="btn btn-outline-primary">Explorar catalogo</a>
    </div>
</section>

<div class="row g-4 mb-5">
    <?php foreach ($featuredProducts ?? [] as $product): ?>
        <div class="col-sm-6 col-xl-3">
            <article class="card product-card border-0 shadow-sm h-100">
                <img src="<?= e(product_image_url($product['imagen'])) ?>" class="card-img-top product-thumb" alt="<?= e($product['nombre']) ?>">
                <div class="card-body d-flex flex-column">
                    <div class="small text-uppercase text-muted mb-2"><?= e($product['categoria_nombre']) ?></div>
                    <h3 class="h5"><?= e($product['nombre']) ?></h3>
                    <p class="text-muted small flex-grow-1"><?= e(mb_strimwidth($product['descripcion'], 0, 95, '...')) ?></p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <?php if (!empty($product['precio_oferta'])): ?>
                                <span class="fw-bold text-primary"><?= e(format_price($product['precio_oferta'])) ?></span>
                                <span class="text-muted text-decoration-line-through small"><?= e(format_price($product['precio'])) ?></span>
                            <?php else: ?>
                                <span class="fw-bold text-primary"><?= e(format_price($product['precio'])) ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="<?= e(app_url('producto?slug=' . urlencode($product['slug']))) ?>" class="btn btn-sm btn-dark">Ver detalle</a>
                    </div>
                </div>
            </article>
        </div>
    <?php endforeach; ?>
</div>

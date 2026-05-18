<section class="page-banner rounded-4 p-4 p-md-5 mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-lg-7">
            <h1 class="display-6 fw-bold mb-2">Catalogo de productos</h1>
            <p class="lead mb-0">Busca, filtra y ordena articulos del escaparate principal.</p>
        </div>
        <div class="col-lg-5 text-lg-end">
            <span class="badge rounded-pill text-bg-dark"><?= e((string) $catalog['total']) ?> resultados</span>
        </div>
    </div>
</section>

<div class="row g-4">
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm sticky-lg-top filter-card">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Filtros</h2>
                <form method="GET" action="<?= e(app_url('catalogo')) ?>" class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="buscar">Buscar</label>
                        <input type="text" class="form-control" id="buscar" name="buscar" value="<?= e((string) ($filters['search'] ?? '')) ?>" placeholder="Nombre, codigo o categoria">
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="categoria">Categoria</label>
                        <select class="form-select" id="categoria" name="categoria">
                            <option value="">Todas</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= e($category['slug']) ?>" <?= ($filters['category_slug'] ?? '') === $category['slug'] ? 'selected' : '' ?>>
                                    <?= e($category['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-6">
                        <label class="form-label" for="precio_min">Precio min.</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="precio_min" name="precio_min" value="<?= e((string) ($filters['min_price'] ?? '')) ?>">
                    </div>

                    <div class="col-6">
                        <label class="form-label" for="precio_max">Precio max.</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="precio_max" name="precio_max" value="<?= e((string) ($filters['max_price'] ?? '')) ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="orden">Ordenar por</label>
                        <select class="form-select" id="orden" name="orden">
                            <option value="nuevos" <?= ($filters['sort'] ?? '') === 'nuevos' ? 'selected' : '' ?>>Mas recientes</option>
                            <option value="nombre_asc" <?= ($filters['sort'] ?? '') === 'nombre_asc' ? 'selected' : '' ?>>Nombre A-Z</option>
                            <option value="nombre_desc" <?= ($filters['sort'] ?? '') === 'nombre_desc' ? 'selected' : '' ?>>Nombre Z-A</option>
                            <option value="precio_asc" <?= ($filters['sort'] ?? '') === 'precio_asc' ? 'selected' : '' ?>>Precio ascendente</option>
                            <option value="precio_desc" <?= ($filters['sort'] ?? '') === 'precio_desc' ? 'selected' : '' ?>>Precio descendente</option>
                        </select>
                    </div>

                    <div class="col-12 d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Aplicar filtros</button>
                        <a href="<?= e(app_url('catalogo')) ?>" class="btn btn-outline-secondary">Limpiar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <?php if (empty($catalog['items'])): ?>
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5 text-center">
                    <h2 class="h4">No se han encontrado productos</h2>
                    <p class="text-muted mb-0">Prueba a cambiar la busqueda o quitar algun filtro.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($catalog['items'] as $product): ?>
                    <div class="col-md-6 col-xl-4">
                        <article class="card product-card border-0 shadow-sm h-100">
                            <img src="<?= e(product_image_url($product['imagen'])) ?>" class="card-img-top product-thumb" alt="<?= e($product['nombre']) ?>">
                            <div class="card-body d-flex flex-column">
                                <div class="small text-uppercase text-muted mb-2"><?= e($product['categoria_nombre']) ?></div>
                                <h2 class="h5"><?= e($product['nombre']) ?></h2>
                                <p class="small text-muted mb-3">Codigo: <?= e($product['codigo']) ?></p>
                                <p class="text-muted flex-grow-1"><?= e(mb_strimwidth($product['descripcion'], 0, 110, '...')) ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <?php if (!empty($product['precio_oferta'])): ?>
                                            <span class="fw-bold text-primary"><?= e(format_price($product['precio_oferta'])) ?></span>
                                            <span class="text-muted text-decoration-line-through small"><?= e(format_price($product['precio'])) ?></span>
                                        <?php else: ?>
                                            <span class="fw-bold text-primary"><?= e(format_price($product['precio'])) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <form action="<?= e(app_url('carrito/anadir')) ?>" method="POST">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="product_id" value="<?= e((string) $product['id']) ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-outline-primary btn-sm">Anadir</button>
                                        </form>
                                        <a href="<?= e(app_url('producto?slug=' . urlencode($product['slug']))) ?>" class="btn btn-dark btn-sm">Detalle</a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (($catalog['pages'] ?? 1) > 1): ?>
                <nav class="mt-4" aria-label="Paginacion del catalogo">
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $catalog['pages']; $i++): ?>
                            <?php
                            $query = $_GET;
                            $query['pagina'] = $i;
                            $link = app_url('catalogo?' . http_build_query($query));
                            ?>
                            <li class="page-item <?= $i === (int) $catalog['page'] ? 'active' : '' ?>">
                                <a class="page-link" href="<?= e($link) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<section class="page-banner rounded-4 p-4 p-md-5 mb-4">
    <h1 class="display-6 fw-bold mb-2">Gestion de productos</h1>
    <p class="lead mb-0">CRUD de productos con busqueda, ordenacion y baja logica.</p>
</section>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form method="GET" action="<?= e(app_url('empleado/productos')) ?>" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label">Buscar</label>
                <input type="text" name="buscar" class="form-control" value="<?= e($search) ?>" placeholder="Nombre, codigo o categoria">
            </div>
            <div class="col-md-4">
                <label class="form-label">Orden</label>
                <select name="orden" class="form-select">
                    <option value="">Mas recientes</option>
                    <option value="nombre_asc" <?= $sort === 'nombre_asc' ? 'selected' : '' ?>>Nombre A-Z</option>
                    <option value="nombre_desc" <?= $sort === 'nombre_desc' ? 'selected' : '' ?>>Nombre Z-A</option>
                    <option value="stock_asc" <?= $sort === 'stock_asc' ? 'selected' : '' ?>>Stock ascendente</option>
                    <option value="stock_desc" <?= $sort === 'stock_desc' ? 'selected' : '' ?>>Stock descendente</option>
                    <option value="precio_desc" <?= $sort === 'precio_desc' ? 'selected' : '' ?>>Precio mayor</option>
                </select>
            </div>
            <div class="col-md-3 d-grid">
                <button type="submit" class="btn btn-dark">Aplicar</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Nuevo producto</h2>
                <form action="<?= e(app_url('empleado/productos/crear')) ?>" method="POST" class="row g-3">
                    <?= csrf_field() ?>
                    <div class="col-12">
                        <label class="form-label">Categoria</label>
                        <select name="categoria_id" class="form-select" required>
                            <option value="">Selecciona</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= e((string) $category['id']) ?>"><?= e($category['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Codigo</label>
                        <input type="text" name="codigo" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Slug opcional</label>
                        <input type="text" name="slug" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripcion</label>
                        <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Precio</label>
                        <input type="number" step="0.01" name="precio" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Oferta</label>
                        <input type="number" step="0.01" name="precio_oferta" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Stock</label>
                        <input type="number" name="stock" class="form-control" min="0" value="0" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Imagen</label>
                        <input type="text" name="imagen" class="form-control" placeholder="spiderman.jpg">
                    </div>
                    <div class="col-12 form-check">
                        <input type="checkbox" name="destacado" class="form-check-input" id="destacado">
                        <label class="form-check-label" for="destacado">Producto destacado</label>
                    </div>
                    <div class="col-12 d-grid">
                        <button type="submit" class="btn btn-primary">Crear producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Categoria</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?= e($product['nombre']) ?></div>
                                        <div class="small text-muted"><?= e($product['codigo']) ?></div>
                                    </td>
                                    <td><?= e($product['categoria_nombre']) ?></td>
                                    <td><?= e(format_price($product['precio_oferta'] ?: $product['precio'])) ?></td>
                                    <td><?= e((string) $product['stock']) ?></td>
                                    <td><span class="badge <?= (int) $product['activo'] === 1 ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= (int) $product['activo'] === 1 ? 'Activo' : 'Inactivo' ?></span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-dark" type="button" data-bs-toggle="collapse" data-bs-target="#edit-product-<?= e((string) $product['id']) ?>">Editar</button>
                                        <?php if ((int) $product['activo'] === 1): ?>
                                            <form action="<?= e(app_url('empleado/productos/desactivar')) ?>" method="POST" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="id" value="<?= e((string) $product['id']) ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que quieres desactivar este producto?')">Desactivar</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr class="collapse" id="edit-product-<?= e((string) $product['id']) ?>">
                                    <td colspan="6">
                                        <form action="<?= e(app_url('empleado/productos/editar')) ?>" method="POST" class="row g-3">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= e((string) $product['id']) ?>">
                                            <div class="col-md-4">
                                                <select name="categoria_id" class="form-select" required>
                                                    <?php foreach ($categories as $category): ?>
                                                        <option value="<?= e((string) $category['id']) ?>" <?= (int) $product['categoria_id'] === (int) $category['id'] ? 'selected' : '' ?>><?= e($category['nombre']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" name="codigo" class="form-control" value="<?= e($product['codigo']) ?>" required>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" name="nombre" class="form-control" value="<?= e($product['nombre']) ?>" required>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" name="slug" class="form-control" value="<?= e($product['slug']) ?>">
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="imagen" class="form-control" value="<?= e($product['imagen']) ?>">
                                            </div>
                                            <div class="col-12">
                                                <textarea name="descripcion" class="form-control" rows="3" required><?= e($product['descripcion']) ?></textarea>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" step="0.01" name="precio" class="form-control" value="<?= e((string) $product['precio']) ?>" required>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" step="0.01" name="precio_oferta" class="form-control" value="<?= e((string) ($product['precio_oferta'] ?? '')) ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" name="stock" class="form-control" value="<?= e((string) $product['stock']) ?>" min="0" required>
                                            </div>
                                            <div class="col-md-3 form-check d-flex align-items-center">
                                                <input type="checkbox" name="destacado" class="form-check-input me-2" <?= (int) $product['destacado'] === 1 ? 'checked' : '' ?>>
                                                <label class="form-check-label">Destacado</label>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary btn-sm">Guardar cambios</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="page-banner rounded-4 p-4 p-md-5 mb-4">
    <h1 class="display-6 fw-bold mb-2">Gestion de categorias</h1>
    <p class="lead mb-0">Crear, editar y desactivar categorias y subcategorias.</p>
</section>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Nueva categoria</h2>
                <form action="<?= e(app_url('empleado/categorias/crear')) ?>" method="POST" class="row g-3">
                    <?= csrf_field() ?>
                    <div class="col-12">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Slug opcional</label>
                        <input type="text" name="slug" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Categoria padre</label>
                        <select name="parent_id" class="form-select">
                            <option value="">Ninguna</option>
                            <?php foreach ($parents as $parent): ?>
                                <option value="<?= e((string) $parent['id']) ?>"><?= e($parent['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripcion</label>
                        <textarea name="descripcion" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-8">
                        <label class="form-label">Imagen</label>
                        <input type="text" name="imagen" class="form-control" placeholder="spiderman.jpg">
                    </div>
                    <div class="col-4">
                        <label class="form-label">Orden</label>
                        <input type="number" name="orden" class="form-control" value="0">
                    </div>
                    <div class="col-12 d-grid">
                        <button type="submit" class="btn btn-primary">Crear categoria</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Padre</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?= e($category['nombre']) ?></div>
                                        <div class="small text-muted"><?= e($category['slug']) ?></div>
                                    </td>
                                    <td><?= e($category['parent_name'] ?: '-') ?></td>
                                    <td><span class="badge <?= (int) $category['activo'] === 1 ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= (int) $category['activo'] === 1 ? 'Activa' : 'Inactiva' ?></span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-dark" type="button" data-bs-toggle="collapse" data-bs-target="#edit-category-<?= e((string) $category['id']) ?>">Editar</button>
                                        <?php if ((int) $category['activo'] === 1): ?>
                                            <form action="<?= e(app_url('empleado/categorias/desactivar')) ?>" method="POST" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="id" value="<?= e((string) $category['id']) ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que quieres desactivar esta categoria?')">Desactivar</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr class="collapse" id="edit-category-<?= e((string) $category['id']) ?>">
                                    <td colspan="4">
                                        <form action="<?= e(app_url('empleado/categorias/editar')) ?>" method="POST" class="row g-3">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= e((string) $category['id']) ?>">
                                            <div class="col-md-4">
                                                <input type="text" name="nombre" class="form-control" value="<?= e($category['nombre']) ?>" required>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" name="slug" class="form-control" value="<?= e($category['slug']) ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <select name="parent_id" class="form-select">
                                                    <option value="">Ninguna</option>
                                                    <?php foreach ($parents as $parent): ?>
                                                        <option value="<?= e((string) $parent['id']) ?>" <?= (int) ($category['parent_id'] ?? 0) === (int) $parent['id'] ? 'selected' : '' ?>>
                                                            <?= e($parent['nombre']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="descripcion" class="form-control" value="<?= e($category['descripcion'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" name="imagen" class="form-control" value="<?= e($category['imagen'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" name="orden" class="form-control" value="<?= e((string) $category['orden']) ?>">
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

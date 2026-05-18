<section class="page-banner rounded-4 p-4 p-md-5 mb-4">
    <h1 class="display-6 fw-bold mb-2">Gestion de usuarios</h1>
    <p class="lead mb-0">CRUD de clientes, empleados y administradores con control de roles y baja logica.</p>
</section>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form method="GET" action="<?= e(app_url('admin/usuarios')) ?>" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label">Buscar</label>
                <input type="text" name="buscar" class="form-control" value="<?= e($search) ?>" placeholder="Nombre o email">
            </div>
            <div class="col-md-4">
                <label class="form-label">Rol</label>
                <select name="rol" class="form-select">
                    <option value="">Todos</option>
                    <?php foreach (config('app.roles', []) as $roleOption): ?>
                        <option value="<?= e($roleOption) ?>" <?= $role === $roleOption ? 'selected' : '' ?>><?= e(ucfirst($roleOption)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-grid">
                <button type="submit" class="btn btn-dark">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Nuevo usuario</h2>
                <form action="<?= e(app_url('admin/usuarios/crear')) ?>" method="POST" class="row g-3">
                    <?= csrf_field() ?>
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input type="text" name="apellidos" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Telefono</label>
                        <input type="text" name="telefono" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">DNI</label>
                        <input type="text" name="dni" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Rol</label>
                        <select name="rol" class="form-select" required>
                            <?php foreach (config('app.roles', []) as $roleOption): ?>
                                <option value="<?= e($roleOption) ?>"><?= e(ucfirst($roleOption)) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contrasena</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-12 d-grid">
                        <button type="submit" class="btn btn-primary">Crear usuario</button>
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
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Ultimo acceso</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?= e(trim($user['nombre'] . ' ' . ($user['apellidos'] ?? ''))) ?></div>
                                        <div class="small text-muted"><?= e($user['email']) ?></div>
                                    </td>
                                    <td><span class="badge text-bg-light"><?= e(ucfirst($user['rol'])) ?></span></td>
                                    <td><span class="badge <?= (int) $user['activo'] === 1 ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= (int) $user['activo'] === 1 ? 'Activo' : 'Inactivo' ?></span></td>
                                    <td><?= e($user['ultimo_acceso'] ? date('d/m/Y H:i', strtotime($user['ultimo_acceso'])) : 'Sin acceso') ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-dark" type="button" data-bs-toggle="collapse" data-bs-target="#edit-user-<?= e((string) $user['id']) ?>">Editar</button>
                                        <?php if ((int) $user['activo'] === 1): ?>
                                            <form action="<?= e(app_url('admin/usuarios/desactivar')) ?>" method="POST" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="id" value="<?= e((string) $user['id']) ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que quieres desactivar este usuario?')">Desactivar</button>
                                            </form>
                                        <?php endif; ?>
                                        <form action="<?= e(app_url('admin/usuarios/eliminar')) ?>" method="POST" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= e((string) $user['id']) ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que quieres eliminar este usuario? Esta accion lo ocultara del sistema.')">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                                <tr class="collapse" id="edit-user-<?= e((string) $user['id']) ?>">
                                    <td colspan="5">
                                        <form action="<?= e(app_url('admin/usuarios/editar')) ?>" method="POST" class="row g-3">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= e((string) $user['id']) ?>">
                                            <div class="col-md-4">
                                                <input type="text" name="nombre" class="form-control" value="<?= e($user['nombre']) ?>" required>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" name="apellidos" class="form-control" value="<?= e($user['apellidos'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="email" name="email" class="form-control" value="<?= e($user['email']) ?>" required>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="telefono" class="form-control" value="<?= e($user['telefono'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="dni" class="form-control" value="<?= e($user['dni'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <select name="rol" class="form-select">
                                                    <?php foreach (config('app.roles', []) as $roleOption): ?>
                                                        <option value="<?= e($roleOption) ?>" <?= $user['rol'] === $roleOption ? 'selected' : '' ?>><?= e(ucfirst($roleOption)) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="password" name="password" class="form-control" placeholder="Nueva contrasena">
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

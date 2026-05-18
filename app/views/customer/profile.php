<section class="page-banner rounded-4 p-4 p-md-5 mb-4">
    <h1 class="display-6 fw-bold mb-2">Mi perfil</h1>
    <p class="lead mb-0">Actualiza tus datos personales y tu contrasena.</p>
</section>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <form action="<?= e(app_url('mi-perfil')) ?>" method="POST" class="row g-3">
                    <?= csrf_field() ?>
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="<?= e((string) old('nombre', $user['nombre'] ?? '')) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input type="text" name="apellidos" class="form-control" value="<?= e((string) old('apellidos', $user['apellidos'] ?? '')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= e((string) old('email', $user['email'] ?? '')) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Telefono</label>
                        <input type="text" name="telefono" class="form-control" value="<?= e((string) old('telefono', $user['telefono'] ?? '')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">DNI</label>
                        <input type="text" name="dni" class="form-control" value="<?= e((string) old('dni', $user['dni'] ?? '')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nueva contrasena</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirmar nueva contrasena</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        <a href="<?= e(app_url('mi-cuenta')) ?>" class="btn btn-outline-secondary">Volver</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

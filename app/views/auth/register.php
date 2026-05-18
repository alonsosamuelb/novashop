<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 mb-3">Crear cuenta</h1>
                <p class="text-muted">Registro de cliente con validacion basica, password hash y sesion automatica.</p>

                <form action="<?= e(app_url('registro')) ?>" method="POST" class="row g-3" novalidate>
                    <?= csrf_field() ?>

                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" value="<?= e((string) old('nombre')) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input type="text" id="apellidos" name="apellidos" class="form-control" value="<?= e((string) old('apellidos')) ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo electronico</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?= e((string) old('email')) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label for="telefono" class="form-label">Telefono</label>
                        <input type="text" id="telefono" name="telefono" class="form-control" value="<?= e((string) old('telefono')) ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="dni" class="form-label">DNI</label>
                        <input type="text" id="dni" name="dni" class="form-control" value="<?= e((string) old('dni')) ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Contrasena</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Repetir contrasena</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Crear cuenta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 mb-3">Iniciar sesion</h1>
                <p class="text-muted">Accede con tus credenciales para continuar con tu compra y gestionar tu cuenta.</p>

                <form action="<?= e(app_url('login')) ?>" method="POST" novalidate>
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electronico</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            value="<?= e((string) old('email')) ?>"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contrasena</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>

                <hr class="my-4">

                <p class="mb-0 text-muted">
                    ¿Aun no tienes cuenta?
                    <a href="<?= e(app_url('registro')) ?>">Registrate aqui</a>.
                </p>
            </div>
        </div>
    </div>
</div>

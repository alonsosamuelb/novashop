<nav class="navbar navbar-expand-lg navbar-dark site-navbar sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-3" href="<?= e(app_url()) ?>">
            <img src="<?= e(asset_url('img/branding/logo.png')) ?>" alt="NovaShop" class="brand-logo">
            <span class="navbar-brand-copy">
                <span class="navbar-brand-name">NovaShop</span>
                <span class="navbar-brand-tag">Gaming, anime y fantasia</span>
            </span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Abrir menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-3 mb-lg-0 navbar-main-links">
                <li class="nav-item"><a class="nav-link <?= is_active_route('') ? 'active' : '' ?>" href="<?= e(app_url()) ?>">Inicio</a></li>
                <li class="nav-item"><a class="nav-link <?= is_active_route('catalogo') ? 'active' : '' ?>" href="<?= e(app_url('catalogo')) ?>">Catalogo</a></li>
                <li class="nav-item"><a class="nav-link <?= is_active_route('envio') ? 'active' : '' ?>" href="<?= e(app_url('envio')) ?>">Envio</a></li>
            </ul>

            <div class="navbar-actions d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2">
                <a href="<?= e(app_url('carrito')) ?>" class="btn btn-outline-light btn-sm position-relative nav-cart-btn nav-action-soft">
                    <span class="nav-cart-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="20" r="1.5"></circle>
                            <circle cx="18" cy="20" r="1.5"></circle>
                            <path d="M3 4h2l2.2 10.2a1 1 0 0 0 1 .8h8.9a1 1 0 0 0 1-.8L20 8H7.2"></path>
                        </svg>
                    </span>
                    <span class="cart-badge badge rounded-pill text-bg-warning"><?= e((string) cart_count()) ?></span>
                </a>
                <?php if (is_logged_in()): ?>
                    <a href="<?= e(app_url('mi-cuenta')) ?>" class="btn btn-outline-light btn-sm nav-action-soft">Mi cuenta</a>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <?php if (has_role(['empleado', 'administrador'])): ?>
                            <a href="<?= e(app_url('empleado')) ?>" class="btn btn-warning btn-sm nav-action-warm">Panel</a>
                        <?php endif; ?>
                        <?php if (has_role('administrador')): ?>
                            <a href="<?= e(app_url('admin')) ?>" class="btn btn-danger btn-sm nav-action-danger">Admin</a>
                        <?php endif; ?>
                    </div>
                    <div class="navbar-user small">
                        <span class="navbar-user-name"><?= e(auth_user()['nombre'] ?? '') ?></span>
                        <span class="navbar-user-role"><?= e(auth_user()['rol'] ?? '') ?></span>
                    </div>
                    <form action="<?= e(app_url('logout')) ?>" method="POST" class="m-0">
                        <?= csrf_field() ?>
                        <button class="btn btn-outline-light btn-sm nav-action-soft" type="submit">Cerrar sesion</button>
                    </form>
                <?php else: ?>
                    <a href="<?= e(app_url('login')) ?>" class="btn btn-outline-light btn-sm nav-action-soft">Iniciar sesion</a>
                    <a href="<?= e(app_url('registro')) ?>" class="btn btn-primary btn-sm nav-action-primary">Registro</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

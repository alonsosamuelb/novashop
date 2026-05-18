<footer class="site-footer mt-auto">
    <div class="container footer-shell">
        <div class="footer-top rounded-4 p-4 p-lg-5">
            <div class="row g-4 align-items-start">
                <div class="col-lg-7">
                    <span class="footer-kicker">NovaShop</span>
                    <h2 class="h3 text-white mt-2 mb-3">Coleccionismo, fantasia y cultura pop en una sola tienda.</h2>
                    <p class="text-white-50 mb-0">
                        Una tienda pensada para descubrir figuras destacadas, comprar con claridad y disfrutar de una experiencia mas cuidada en cada visita.
                    </p>
                </div>

                <div class="col-lg-5">
                    <h3 class="footer-title">Informacion</h3>
                    <div class="footer-links footer-links-grid">
                        <a href="<?= e(app_url('quienes-somos')) ?>">Quienes somos</a>
                        <a href="<?= e(app_url('condiciones-generales')) ?>">Condiciones generales</a>
                        <a href="<?= e(app_url('politica-devoluciones')) ?>">Politica de devoluciones</a>
                        <a href="<?= e(app_url('contacto')) ?>">Contacto</a>
                    </div>
                    <p class="text-white-50 small mb-0 mt-3">
                        Compra como invitado o cliente registrado, consulta tus pedidos y disfruta de una atencion clara antes y despues de cada compra.
                    </p>
                </div>
            </div>
        </div>

        <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 py-4">
            <p class="mb-0 footer-meta">© <?= date('Y') ?> NovaShop. Todos los derechos reservados.</p>
            <p class="mb-0 footer-meta">Figuras, gaming, anime y fantasia para coleccionistas con personalidad.</p>
        </div>
    </div>
</footer>

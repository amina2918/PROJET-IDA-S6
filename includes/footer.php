    </div> <!-- Fin du container-fluid -->
</main>

<!-- Footer Moderne -->
<footer>
    <div class="container-fluid px-3 px-lg-4">
        <div class="row g-4 mb-4">
            <!-- À Propos -->
            <div class="col-md-6 col-lg-3 footer-section" data-aos="fade-up">
                <h5>E-Tech-Boutique</h5>
                <p>
                    <i class="bi bi-geo-alt me-2"></i>
                    Découvrez une sélection unique d' accessoires. 
                    , nous combinons tradition et modernité.
                </p>
                <div class="social-icons">
                    <i class="bi bi-facebook"></i>
                    <i class="bi bi-instagram"></i>
                    <a href="https://wa.me/221783783058" target="_blank">
                    <i class="bi bi-whatsapp"></i>
                    </a>
                    <a href="#">
                    <i class="bi bi-tiktok"></i>
                    </a>
                </div>
            </div>

            <!-- Liens Utiles -->
            <div class="col-md-6 col-lg-3 footer-section" data-aos="fade-up" data-aos-delay="100">
                <h5>Navigation</h5>
                <p><a href="index.php"><i class="bi bi-chevron-right me-2"></i>Accueil</a></p>
                <p><a href="produits.php"><i class="bi bi-chevron-right me-2"></i>Tous les produits</a></p>
                <p><a href="panier.php"><i class="bi bi-chevron-right me-2"></i>Panier</a></p>
                <p><a href="#"><i class="bi bi-chevron-right me-2"></i>À propos</a></p>
            </div>

            <!-- Contact -->
            <div class="col-md-6 col-lg-3 footer-section" data-aos="fade-up" data-aos-delay="200">
                <h5>Contact</h5>
                <p>
                    <i class="bi bi-whatsapp me-2"></i>
                    <a href="https://wa.me/221783783058" target="_blank">+22783783058</a>
                </p>
                <p>
                    <i class="bi bi-envelope me-2"></i>
                    <a href=""></a>
                </p>
                <p>
                    <i class="bi bi-clock me-2"></i>
                    <span>Lun - Sam : 9h - 21h</span>
                </p>
            </div>

            <!-- Newsletter -->
            <div class="col-md-6 col-lg-3 footer-section" data-aos="fade-up" data-aos-delay="300">
                <h5>Newsletter</h5>
                <p>Recevez nos offres exclusives et nouveautés</p>
                <form class="newsletter-form">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Votre email" required>
                        <button class="btn btn-accent" type="submit">
                            <i class="bi bi-send"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <p class="mb-2">
                © <?= date('Y') ?> E-Tech-Boutique Tous droits réservés
            </p>
            <p class="mb-0">
                <a href="#">Mentions légales</a> | 
                <a href="#">Conditions générales</a> | 
                <a href="#">Politique de confidentialité</a>
            </p>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="assets/js/main.js"></script>

<!-- AOS Initialization -->
<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });
</script>

</body>
</html>
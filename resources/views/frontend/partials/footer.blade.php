    <!-- CTA Section -->
    <section class="cta-section py-7">
        <div class="container text-center">
            <h2 class="display-4 fw-bold mb-4">আজই শপিং শুরু করুন!</h2>
            <p class="lead mb-5">৫০০০+ প্রোডাক্ট | ফ্রি শিপিং | নিরাপদ পেমেন্ট</p>
            <a href="register.html" class="btn btn-accent btn-lg px-5 py-3 fs-4 me-3">
                <i class="fas fa-user-plus me-2"></i>অ্যাকাউন্ট তৈরি
            </a>
            <a href="#categories" class="btn btn-outline-light btn-lg px-5 py-3 fs-4">
                <i class="fas fa-shopping-bag me-2"></i>কেনাকাটা শুরু
            </a>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="{{asset('frontend_assets/js/bootstrap.bundle.min.js')}}"></script>
    <script>
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
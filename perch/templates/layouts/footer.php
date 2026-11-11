  </main>

  <footer class="site-main">
    <div class="restrict">
      <div>
        <h3>Connect with Us</h3>
        <ul class="inline connect">
        <li><a href="https://www.facebook.com/countryside-jobs-service" target="_blank"><i class="fab fa-facebook"></i><span>Facebook</span></a></li>
        <li><a href="https://bsky.app/profile/countrysidejobs.bsky.social" target="_blank"><i class="fab fa-bluesky"></i><span>Bluesky</span></a></li>
        <li><a href="https://www.threads.com/@countrysidejobs" target="_blank"><i class="fab fa-threads"></i><span>Threads</span></a></li>
        <li><a href="https://www.instagram.com/countrysidejobs/" target="_blank"><i class="fab fa-instagram"></i><span>Instagram</span></a></li>
        <li><a href="https://www.linkedin.com/company/countryside-jobs-service/" target="_blank"><i class="fab fa-linkedin"></i><span>LinkedIn</span></a></li>
        <li><a href="https://x.com/CountrysideJobs" target="_blank"><i class="fab fa-x-twitter"></i><span>X</span></a></li>
        </ul>
      </div>
      <div class="flow list-block" id="email">
        <h4>Daily Email</h4>
        <p>Get jobs, features and news directly in your inbox.</p>
        <?php perch_content('Mailing List Subscription'); ?>
      </div>
      <div class="flow list-block">
        <h4>Readers</h4>
        <?php cjs_menu_manager_menu('footer-readers'); ?>
      </div>
      <div class="flow list-block">
        <h4>Advertisers</h4>
        <?php cjs_menu_manager_menu('footer-advertisers'); ?>
      </div>
      <div class="flow list-block">
        <h4>About Us</h4>
        <?php cjs_menu_manager_menu('footer-about-us'); ?>
      </div>
    </div>
    <div class="colophon">
      <div class="restrict">
        <p>&copy; Countryside Jobs Service <?= date('Y') ?></p>
        <p><a href="https://hellotechnology.co.uk">Website by Hello Technology</a></p>
      </div>
    </div>
  </footer>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script src="<?php echo cjs_asset('/assets/javascript/import.js'); ?>" defer></script>
  <script src="<?php echo cjs_asset('/assets/javascript/scripts.js'); ?>" defer></script>
  <script src="https://kit.fontawesome.com/1dd8560de8.js" crossorigin="anonymous" async defer></script>
  <script>
    /* A submitted Perch form is replaced by its <perch:success> block, which sits
       wherever the form region sat. On the advertising pages that is well down the
       page, so whoever submits lands back at the top with no sign that anything
       happened — which is exactly what an advertiser told us. Bring the
       confirmation into view and give it focus so it is also read out.

       Inline rather than in scripts.js on purpose: that file is jQuery-dependent,
       and this must run even if jQuery has not loaded. */
    (function () {
      var panel = document.getElementById('form-confirmation');
      if (!panel) return;

      var smooth = !window.matchMedia || !window.matchMedia('(prefers-reduced-motion: reduce)').matches;

      try {
        panel.scrollIntoView({ behavior: smooth ? 'smooth' : 'auto', block: 'center' });
      } catch (e) {
        panel.scrollIntoView(true);
      }

      try {
        panel.focus({ preventScroll: true });
      } catch (e) {
        panel.focus();
      }
    })();
  </script>
  <script>
    const sentinel = document.querySelector('.masthead,header');
    const stickyEl = document.querySelector('nav');
    
    const observer = new IntersectionObserver(([entry]) => {
        stickyEl.classList.toggle('is-sticky', !entry.isIntersecting);
    }, {
        threshold: 0,
    });
    
    observer.observe(sentinel);
  </script>
</body>
</html>
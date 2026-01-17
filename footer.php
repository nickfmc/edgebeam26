<footer class="o-section c-page-footer" id="c-page-footer" role="contentinfo" itemscope itemtype="https://schema.org/WPFooter">

    <div class="c-page-footer-upper">
      <div class="o-wrapper-wide">
        <img src="<?php bloginfo( 'template_url' ) ?>/img/logo_white.svg" alt="EdgeBeam Wireless Logo" />
        <!-- /.c-footer-widgets -->
         <nav class="c-footer-navigation" role="navigation">
  <?php gdt_nav_menu('tertiary-menu', 'tertiary-menu'); // Adjust using Menus in WordPress Admin ?>
</nav>
        <div>
          <p>2 Seaport Lane<br />8th Floor, Suite 8B<br />Boston, MA 02210<br /><a href="tel:17815488407">1.781.548.8407</a><br /><a href="mailto:info@edgebeamwireless.com">info@edgebeamwireless.com</a></p>
        </div>
        <!-- /.c-logo-copy-wrap -->
      </div>
      <!-- /.o-wrapper-wide -->
    </div>
    <div class="c-page-footer-lower">
      <div class="o-wrapper-wide">
        <div class="">
            <div class="c-copywrite">
              ©2025 EdgeBeam Wireless, LLC. All Rights Reserved.
            </div>
         
        </div>
      </div>
    </div>
  </footer>
  <!-- /.c-page-footer -->

  <?php // get_template_part( 'template-part/navigation/nav-modal' ); ?>

  <!-- Bottom Banner Popup -->
  <div id="bottom-banner" class="c-bottom-banner" role="dialog" aria-labelledby="banner-title" aria-live="polite">
    <div class="c-bottom-banner__content">
      <div class="c-bottom-banner__text">
        <h3 id="banner-title" class="c-bottom-banner__title">Come Meet Us at AIoT World Expo</h3>
        <p class="c-bottom-banner__subtitle">Discover the transformative power of one-to-many data distribution where it counts most, in the last mile. <a target="_blank" href="mailto:sbrumer@edgebeamwireless.com" class="c-bottom-banner__link">Schedule a Meeting</a></p>
      </div>
      <button id="close-banner" class="c-bottom-banner__close" aria-label="Close banner">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
          <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
    </div>
  </div>

  <!-- all js scripts are loaded in lib/gdt-enqueues.php -->
  <?php wp_footer(); ?>

</body>
</html>

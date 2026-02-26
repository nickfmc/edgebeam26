
<footer class="o-section c-page-footer" id="c-page-footer" role="contentinfo" itemscope itemtype="https://schema.org/WPFooter">

    <div class="c-page-footer-upper">
      <div class="o-wrapper-wide">
        <!-- Logo and Tagline Section -->
        <div class="c-footer-brand">
          <img src="<?php bloginfo( 'template_url' ) ?>/img/logo_white.svg" alt="EdgeBeam Wireless Logo" class="c-footer-logo" />
          <p class="c-footer-tagline">The world's first hybrid network operator enabling one-to-many data distribution for today's wireless networks.</p>
          <div class="c-footer-social-divider"></div>
          <div class="c-footer-social">
            <div class="c-footer-social-icon">
              <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="36" height="36" rx="4" fill="white"/>
                <path d="M26 12.7C26 12.3 25.7 12 25.3 12H22.2C20.4 12 19.6 13 19.6 14.4V17H25.3C25.5 17 25.6 17.1 25.7 17.2C25.7 17.4 25.7 17.6 25.6 17.7L24.9 20.7C24.8 21 24.5 21.1 24.3 21.1H19.6V29C19.6 29.4 19.3 29.7 18.9 29.7H15.7C15.3 29.7 15 29.4 15 29V21.1H12.7C12.3 21.1 12 20.8 12 20.4V17.4C12 17 12.3 16.7 12.7 16.7H15V14.1C15 11 16.9 9 19.7 9H25.3C25.7 9 26 9.3 26 9.7V12.7Z" fill="#0A66C2"/>
              </svg>
            </div>
            <span class="c-footer-social-text">LinkedIn</span>
          </div>
        </div>

        <!-- Solutions Menu (2 columns) with Devices underneath -->
        <div class="c-footer-menu-section c-footer-menu-section--with-devices">
          <nav class="c-footer-navigation c-footer-navigation--two-col" role="navigation">
            <?php 
            if ( has_nav_menu( 'footer-solutions' ) ) {
              wp_nav_menu( array(
                'theme_location' => 'footer-solutions',
                'container'      => false,
                'menu_class'     => 'c-footer-menu',
                'depth'          => 1,
              ) );
            }
            ?>
          </nav>
          
          <!-- Devices Menu -->
          <nav class="c-footer-navigation c-footer-navigation--devices" role="navigation">
            <?php 
            if ( has_nav_menu( 'footer-devices' ) ) {
              wp_nav_menu( array(
                'theme_location' => 'footer-devices',
                'container'      => false,
                'menu_class'     => 'c-footer-menu',
                'depth'          => 1,
              ) );
            }
            ?>
          </nav>
        </div>

        <!-- About Menu -->
        <div class="c-footer-menu-section">
          <nav class="c-footer-navigation" role="navigation">
            <?php 
            if ( has_nav_menu( 'footer-about' ) ) {
              wp_nav_menu( array(
                'theme_location' => 'footer-about',
                'container'      => false,
                'menu_class'     => 'c-footer-menu',
                'depth'          => 1,
              ) );
            }
            ?>
          </nav>
        </div>

        <!-- Deep Dive Menu -->
        <div class="c-footer-menu-section">
          <nav class="c-footer-navigation" role="navigation">
            <?php 
            if ( has_nav_menu( 'footer-deep-dive' ) ) {
              wp_nav_menu( array(
                'theme_location' => 'footer-deep-dive',
                'container'      => false,
                'menu_class'     => 'c-footer-menu',
                'depth'          => 1,
              ) );
            }
            ?>
          </nav>
        </div>

        <!-- Contact Section -->
        <div class="c-footer-contact">
          <div class="c-footer-contact-info">
            <p class="c-footer-contact-title">Contact EdgeBeam</p>
            <p>2 Seaport Lane<br />8th Floor, Suite 8B<br />Boston, MA 02210</p>
            <p><a href="tel:17815488407">1.781.548.8407</a><br /><a href="mailto:info@edgebeamwireless.com">info@edgebeamwireless.com</a></p>
          </div>
        </div>
      </div>
      <!-- /.o-wrapper-wide -->
    </div>
    <div class="c-page-footer-lower">
      <div class="o-wrapper-wide">
        <div class="c-footer-copyright-container">
          <div class="c-copywrite">
            Copyright © 2026 EdgeBeam Wireless. All Rights Reserved. Terms and Conditions. Privacy Policy.
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!-- /.c-page-footer -->

  <?php // get_template_part( 'template-part/navigation/nav-modal' ); ?>

  <!-- Bottom Banner Popup -->
  <?php 
  $banner_enabled = get_field('banner_enabled', 'option');
  if( $banner_enabled ): 
    $banner_title = get_field('banner_title', 'option');
    $banner_text = get_field('banner_text', 'option');
    $banner_link_text = get_field('banner_link_text', 'option');
    $banner_link_url = get_field('banner_link_url', 'option');
  ?>
  <div id="bottom-banner" class="c-bottom-banner" role="dialog" aria-labelledby="banner-title" aria-live="polite">
    <div class="c-bottom-banner__content">
      <div class="c-bottom-banner__text">
        <?php if( $banner_title ): ?>
          <h3 id="banner-title" class="c-bottom-banner__title"><?php echo esc_html($banner_title); ?></h3>
        <?php endif; ?>
        <?php if( $banner_text ): ?>
          <p class="c-bottom-banner__subtitle">
            <?php echo esc_html($banner_text); ?>
            <?php if( $banner_link_url && $banner_link_text ): ?>
              <a target="_blank" href="<?php echo esc_url($banner_link_url); ?>" class="c-bottom-banner__link"><?php echo esc_html($banner_link_text); ?></a>
            <?php endif; ?>
          </p>
        <?php endif; ?>
      </div>
      <button id="close-banner" class="c-bottom-banner__close" aria-label="Close banner">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
          <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
    </div>
  </div>
  <?php endif; ?>

  <!-- all js scripts are loaded in lib/gdt-enqueues.php -->
  <?php wp_footer(); ?>

</body>
</html>

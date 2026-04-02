
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
              <a href="https://www.linkedin.com/company/edgebeam/" target="_blank" aria-label="Visit our LinkedIn profile">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                  <rect width="36" height="36" rx="8" fill="#E7E6F2"/>
                  <path d="M9 11.4201C9 10.8428 9.20271 10.3666 9.60811 9.99148C10.0135 9.6163 10.5405 9.42871 11.1892 9.42871C11.8263 9.42871 12.3417 9.6134 12.7355 9.98282C13.1409 10.3638 13.3436 10.8602 13.3436 11.472C13.3436 12.0261 13.1467 12.4879 12.7529 12.8573C12.3475 13.2382 11.8147 13.4287 11.1544 13.4287H11.1371C10.5 13.4287 9.98456 13.2382 9.59073 12.8573C9.19691 12.4763 9 11.9972 9 11.4201ZM9.22587 26.5716V15.0045H13.083V26.5716H9.22587ZM15.2201 26.5716H19.0772V20.1127C19.0772 19.7086 19.1236 19.3969 19.2162 19.1776C19.3784 18.7851 19.6245 18.4532 19.9546 18.182C20.2847 17.9107 20.6988 17.775 21.1969 17.775C22.4942 17.775 23.1429 18.6466 23.1429 20.3897V26.5716H27V19.9395C27 18.231 26.5946 16.9352 25.7838 16.0521C24.973 15.169 23.9015 14.7274 22.5695 14.7274C21.0753 14.7274 19.9112 15.3681 19.0772 16.6495V16.6841H19.0598L19.0772 16.6495V15.0045H15.2201C15.2432 15.3739 15.2548 16.5225 15.2548 18.4504C15.2548 20.3782 15.2432 23.0853 15.2201 26.5716Z" fill="#1D252D"/>
                </svg>      
              </a>
               </div>
          </div>
        </div>

        <!-- Solutions Menu -->
        <div class="c-footer-menu-section">
          <nav class="c-footer-navigation" role="navigation" aria-label="Solutions">
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
        </div>

        <!-- Devices + About Menus (stacked) -->
        <div class="c-footer-menu-section c-footer-menu-section--stacked">
          <nav class="c-footer-navigation" role="navigation" aria-label="Devices">
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

          <nav class="c-footer-navigation" role="navigation" aria-label="About">
            <?php 
            if ( has_nav_menu( 'footer-about' ) ) {
              wp_nav_menu( array(
                'theme_location' => 'footer-about',
                'container'      => false,
                'menu_class'     => 'c-footer-menu',
                // 'depth'          => 1,
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

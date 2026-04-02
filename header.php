<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
 
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  
  <!-- we can safely use the favicon uploader in customizer now. plugins such as perf matters will add the missing black favicon.io -->
  <?php // Load google fonts ------------------------------- ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> 
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
  <?php // other html head stuff (before WP/theme scripts are loaded) ------- ?>
<!-- GSAP Core Library (Required) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js"></script>

<!-- ScrollTrigger Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollTrigger.min.js"></script>

  <?php wp_head(); // wordpress head functions -- DONOTREMOVE ?>

  <?php // START Google Analytics here ?> 
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-1HF3P5GCYB"></script> <script>   window.dataLayer = window.dataLayer || [];   function gtag(){dataLayer.push(arguments);}   gtag('js', new Date());   gtag('config', 'G-1HF3P5GCYB'); </script>
  <?php // END Analytics ?>
  <!-- Start of HubSpot Embed Code -->
<script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/51157240.js"></script>
<!-- End of HubSpot Embed Code -->
  <!-- GSAP Core Library (Required) -->


</head>


<body <?php body_class(pretty_body_class()); ?> itemscope itemtype="https://schema.org/WebPage">
<!-- Skip links should be the first focusable elements -->
<div class="skip-links" role="navigation" aria-label="Skip links navigation">
    <a href="#main-content" class="skip-link" role="link" aria-label="Skip to main content">Skip to main content</a>
    <a href="#site-navigation" class="skip-link" role="link" aria-label="Skip to main navigation">Skip to main navigation</a>
    <a href="#c-page-footer" class="skip-link" role="link" aria-label="Skip to page footer">Skip to page footer</a>
</div>
<!-- END Skip links should be the first focusable elements -->

  <header id="c-page-header" class="o-section c-page-header" role="banner" itemscope itemtype="https://schema.org/WPHeader">
    <div class="o-wrapper-wide  u-relative">
      <?php get_template_part( 'template-part/header/logo' ); ?>
      <?php get_template_part( 'template-part/navigation/nav-main' ); ?>
      <?php //get_template_part( 'template-part/navigation/nav-tertiary' ); ?>
      
      <!-- <div class="c-modal-nav-button-wrap">
        <a class="toggle hc-nav-trigger mobile-nav" href="#" role="button" aria-label="Open Menu" aria-controls="hc-nav-1" aria-expanded="false">
          <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 512 512"><path fill="currentColor" d="M32 96v64h448V96H32zm0 128v64h448v-64H32zm0 128v64h448v-64H32z"/></svg>
        </a>
      </div> -->

      <div class="c-cl-mobile-nav">
        <button href="#" id="open-modal-nav" class="c-modal-nav-button"  aria-expanded="false" aria-haspopup="menu" aria-label="Open menu">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="20" viewBox="0 0 28 20" fill="none">
          <!-- Top wave - dark blue -->
          <path d="M0 2Q7 -2 14 2Q21 6 28 2" stroke="#001871" stroke-width="3" stroke-linecap="round" fill="none"/>
          <!-- Middle wave - medium blue -->
          <path d="M0 10Q7 6 14 10Q21 14 28 10" stroke="#003fae" stroke-width="3" stroke-linecap="round" fill="none"/>
          <!-- Bottom wave - orange/gold -->
          <path d="M0 18Q7 14 14 18Q21 22 28 18" stroke="#f1a24c" stroke-width="3" stroke-linecap="round" fill="none"/>
        </svg>
          </button>
        
        <div class="x-body-wrapper"></div>
          <div id="modal-nav-wrap" class="c-modal-nav-wrap" tabindex="-1">
            <button id="close-modal-nav" class="c-close-modal-nav" aria-label="Close menu">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
              <path d="M18 6L6 18M6 6L18 18" stroke="#414651" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            </button>
            <nav class="c-modal-nav" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
            <div class="c-modal-nav-header">
              <div class="c-modal-nav-logo"> 
                <a href="/" rel="nofollow">
                  <img src="<?php bloginfo('template_url') ?>/img/Logo.svg" alt="<?php bloginfo('name'); ?>" />
                </a>
              </div> <!-- /c-main-logo -->
            </div>
            
              <?php  gdt_nav_menu( 'mobile-menu', 'c-mobile-menu' ); // Adjust using Menus in WordPress Admin ?>
              <div class="c-cl-mobile-nav-footer">
                <a href="/contact" class="c-contact-link c-contact-link--mobile" aria-label="Contact us">Contact Us</a>
                
                <div class="c-cl-mobile-nav-footer-social">
           
           
         
            </div>
              </div>
            </nav>
          </div> <!-- /modal-nav-wrap -->
      </div>
    </div>
    <!-- /o-wrapper-wide-->
     <!-- popout search -->
    <div id="search-popup" role="dialog" aria-hidden="true" inert="true">
    <button type="button" id="close-search-popup" class="c-search-close-button" aria-label="Close search popup" onclick="closeSearchPopup()">
    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="m13.41 12l4.3-4.29a1 1 0 1 0-1.42-1.42L12 10.59l-4.29-4.3a1 1 0 0 0-1.42 1.42l4.3 4.29l-4.3 4.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0l4.29-4.3l4.29 4.3a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.42Z"/></svg>
    </button>
    <form role="search" method="get" id="search-form" class="c-search-form" action="<?php echo home_url( '/' ); ?>">
        <div>
        <label for="s" class="u-visually-hidden">Search for:</label>
        <input type="search" id="s" name="s" value="" class="search-input" placeholder="Search..." />
        <button type="submit" id="search-submit" class="search-submit">Search</button>
        </div>
    </form>
    </div>
    <!-- popout search -->
  </header> 
  <!-- /c-page-header -->

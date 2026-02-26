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
  <!-- Google tag (gtag.js) --> <script async src="https://www.googletagmanager.com/gtag/js?id=G-1HF3P5GCYB"></script> <script>   window.dataLayer = window.dataLayer || [];   function gtag(){dataLayer.push(arguments);}   gtag('js', new Date());   gtag('config', 'G-1HF3P5GCYB'); </script>
  <?php // END Analytics ?>
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
                
                
                <div class="c-cl-mobile-nav-footer-social">
           
              <a target="_blank" href="https://linkedin.com" aria-label="Visit our LinkedIn profile">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <path d="M22.2234 0H1.77187C0.792187 0 0 0.773438 0 1.72969V22.2656C0 23.2219 0.792187 24 1.77187 24H22.2234C23.2031 24 24 23.2219 24 22.2703V1.72969C24 0.773438 23.2031 0 22.2234 0ZM7.12031 20.4516H3.55781V8.99531H7.12031V20.4516ZM5.33906 7.43438C4.19531 7.43438 3.27188 6.51094 3.27188 5.37187C3.27188 4.23281 4.19531 3.30937 5.33906 3.30937C6.47813 3.30937 7.40156 4.23281 7.40156 5.37187C7.40156 6.50625 6.47813 7.43438 5.33906 7.43438ZM20.4516 20.4516H16.8937V14.8828C16.8937 13.5562 16.8703 11.8453 15.0422 11.8453C13.1906 11.8453 12.9094 13.2937 12.9094 14.7891V20.4516H9.35625V8.99531H12.7687V10.5609H12.8156C13.2891 9.66094 14.4516 8.70938 16.1813 8.70938C19.7859 8.70938 20.4516 11.0813 20.4516 14.1656V20.4516Z" fill="#001871"/>
                </svg>
              </a>
         
            </div>
              </div>
            </nav>
          </div> <!-- /modal-nav-wrap -->
      </div>
    </div>
    <!-- /o-wrapper-wide-->
  </header> 
  <!-- /c-page-header -->

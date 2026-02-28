<nav id="site-navigation" class="c-main-navigation" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
   
   <?php 
   gdt_nav_menu( 'main-menu', 'c-main-menu', array(
      'walker' => new GB_Pro_Mega_Menu_Walker( 'click' )
   )); // Adjust using Menus in WordPress Admin 
   ?>
   

    
    <button id="search-button" aria-label="Open search" aria-expanded="false" aria-controls="search-popup">
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
  <path d="M11.8355 11.8355L9.03323 9.03323M1 5.67048C1 3.09105 3.09105 1 5.67048 1C8.24993 1 10.341 3.09105 10.341 5.67048C10.341 8.24993 8.24993 10.341 5.67048 10.341C3.09105 10.341 1 8.24993 1 5.67048Z" stroke="#0047BB" stroke-width="2" stroke-linecap="square" stroke-linejoin="round"/>
</svg> <span>Search</span>
    </button> 

  
      <a href="/contact" class="c-contact-link" aria-label="Contact us">Contact Us</a>
   






</nav>

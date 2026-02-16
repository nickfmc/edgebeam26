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

</nav>

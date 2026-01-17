<nav id="site-navigation" class="c-main-navigation" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
   
   <?php 
   gdt_nav_menu( 'main-menu', 'c-main-menu', array(
      'walker' => new Accessible_Nav_Walker()
   )); // Adjust using Menus in WordPress Admin 
   ?>
   



</nav>

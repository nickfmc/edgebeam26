<?php
/*
Template Name: TOC Page
*/
?>

<?php get_header(); ?>
<?php
$hero_bg_url  = get_the_post_thumbnail_url( get_the_ID(), 'full' );
$hero_style   = $hero_bg_url ? ' style="background-image: url(' . esc_url( $hero_bg_url ) . ');"' : '';
$hero_classes = 'o-layout-row c-pillar-hero' . ( $hero_bg_url ? ' has-featured-bg' : '' );
?>
<div class="<?php echo esc_attr( $hero_classes ); ?>"<?php echo $hero_style; ?>>
  <?php if ( $hero_bg_url ) : ?>
  <div class="c-pillar-hero__overlay" aria-hidden="true"></div>
  <?php endif; ?>
  <div class="o-wrapper-wide">
    <h1 class="h2-style"><?php echo the_title(); ?></h1>
  </div>
</div>
<?php $toc_data = get_table_of_contents(get_the_content(), get_the_ID()); ?>
<div class="o-layout-row c-pillar-content">
   <div class="o-wrapper-wide">
     <div class="c-pillar-content-inner">
       <aside>
       
                <div class="c-single-post__toc-widget">
                  <?php echo $toc_data['toc_html']; ?>
       
                  <!-- Divider -->
                  <div class="c-single-post__widget-divider"></div>
       
                  <!-- Share Section -->
                  <!-- <div class="c-single-post__share-widget">
                    <h4 class="c-single-post__share-title">Share:</h4>
                    <div class="c-single-post__share-buttons"> -->
                      <!-- AddToAny BEGIN -->
       <!-- <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
       <a class="a2a_button_facebook"><svg xmlns="http://www.w3.org/2000/svg" width="7" height="14" viewBox="0 0 7 14" fill="none">
       <path fill-rule="evenodd" clip-rule="evenodd" d="M4.65425 14H1.5509V7.38906H0V4.84149H1.5509V3.31325C1.5509 1.23644 2.42648 0 4.91428 0H6.98525V2.54758H5.69128C4.72256 2.54758 4.65813 2.90318 4.65813 3.5677L4.65425 4.84149H7L6.72522 7.38906H4.65425V14Z" fill="#1C5195"/>
       </svg></a>
       <a class="a2a_button_x"><svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
       <path d="M6.80827 8.14834L2.36633 13.296C2.24865 13.4309 2.08347 13.5 1.91734 13.5C1.77837 13.5 1.63885 13.4516 1.52601 13.3532C1.27817 13.1369 1.2526 12.7607 1.46891 12.5128L6.05219 7.20327L6.80827 8.14834ZM8.42412 6.29679L13.0074 0.987216C13.2237 0.739374 13.1981 0.363205 12.9503 0.146889C12.7025 -0.0694262 12.3261 -0.043856 12.11 0.203986L7.66805 5.35164L8.42412 6.29679Z" fill="#1C5195"/>
       <path d="M13.6307 13.4999H10.5337C10.3527 13.4999 10.1816 13.4176 10.0686 13.2763L0.380506 0.967623C0.237487 0.788868 0.209614 0.543886 0.308798 0.337576C0.407982 0.131266 0.616595 0 0.845536 0H3.94256C4.12354 0 4.29459 0.0822698 4.40759 0.223542L14.0957 12.5322C14.2387 12.711 14.2666 12.956 14.1674 13.1623C14.0683 13.3686 13.8596 13.4999 13.6307 13.4999ZM10.8199 12.3087H12.3915L3.65629 1.19116H2.08474L10.8199 12.3087Z" fill="#1C5195"/>
       </svg>
       </a>
       <a class="a2a_button_linkedin"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
       <path d="M3.62988 14H0.726562V4.64844H3.62988V14ZM11.0186 4.4209C13.9545 4.4209 14.5 6.35618 14.5 8.875V14H11.6016V9.45312C11.6016 8.3667 11.5851 6.97266 10.0957 6.97266C8.585 6.97283 8.35845 8.15609 8.3584 9.37207V14H5.45996V4.64844H8.23438V5.92969H8.27148C8.66004 5.19462 9.60474 4.42104 11.0186 4.4209ZM2.18359 0C3.11189 0 3.86816 0.756794 3.86816 1.68652C3.86814 2.61624 3.11188 3.37305 2.18359 3.37305C1.25005 3.37289 0.50002 2.61614 0.5 1.68652C0.5 0.756891 1.25004 0.000157856 2.18359 0Z" fill="#1C5195"/>
       </svg></a>
       <a class="a2a_button_email">
         <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 32 32"><path fill="#1C5195" d="M28 6H4a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h24a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2m-2.2 2L16 14.78L6.2 8ZM4 24V8.91l11.43 7.91a1 1 0 0 0 1.14 0L28 8.91V24Z"/></svg>
       </a>
       </div>
       <script defer src="https://static.addtoany.com/menu/page.js"></script> -->
       <!-- AddToAny END -->
                    <!-- </div>
                  </div>
                </div> -->
       
         </aside>
       
       
         <main id="main-content" class="" role="main" itemscope itemprop="mainContentOfPage" itemtype="https://schema.org/WebPageElement">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          <section class="editor-content  clearfix">
           <?php  // Get table of contents and processed content
       
                echo $toc_data['content'];
                ?>
          </section>
          <!-- /editor-content -->
        <?php endwhile; endif; // END main loop (if/while) ?>
         </main>
     </div>
   </div>

 
  <!-- /container -->
</div>
<!-- /layout-row-->


<?php get_footer(); ?>

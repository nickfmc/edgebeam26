<?php get_header(); ?>

<div class="o-layout-row c-blog-header">
  <header class=" o-wrapper-wide">
    <?php 
    if (have_posts()) : while (have_posts()) : the_post();
    $category = get_the_category();
    if (!empty($category)) {
        echo '<span class="is-eyebrow">' . esc_html($category[0]->name) . '</span>';
    }
    ?>
    <h1 class="c-blog-title h2-style"><?php the_title(); ?></h1> 
    <div class="c-post-meta">
      <?php echo get_the_author(); ?>   |   <?php echo gdt_get_read_time(); ?> Min Read
    </div>
  </header>

</div>

<div class="o-layout-row">
  <main id="main-content" class="editor-content post-editor-content" role="main" itemscope itemprop="mainContentOfPage" itemtype="https://schema.org/WebPageElement">

      <article <?php post_class(); ?> role="article">
        <?php the_content(); ?>
      </article>
    <?php endwhile; ?>
      <?php get_template_part( 'template-part/post/post-nav' ); ?>
    <?php else : ?>
      <?php get_template_part( 'template-part/post/not-found' ); ?>
    <?php endif; ?>
    
  </main>
</div>
<!-- /layout-row-->

<?php get_footer(); ?>

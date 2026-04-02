<?php get_header(); ?>

<!-- Hero Section with Light Blue Background -->
<section class="c-search-hero">
  <div class="c-search-hero__inner o-wrapper-wide">
    <h1 class="c-search-hero__title">Search Results</h1>
    <p class="c-search-hero__description">
      Didn't find what you're looking for? Try adjusting your keywords and querying again using the search bar below. Still have questions or want to see how this works in practice? <a href="<?php echo esc_url(home_url('/contact')); ?>" class="c-search-hero__link">Contact our team</a>.
    </p>

        
  </div>
  <div class="o-wrapper-wide">
    <!-- Search Bar Section -->
    <div class="c-search-bar-section">
      <form role="search" method="get" class="c-search-bar-container o-wrapper-wide" action="<?php echo esc_url(home_url('/')); ?>">
        <label for="search-page-input" class="c-search-bar-label">Search</label>
        <div class="c-search-bar-wrapper">
          <input 
            type="search" 
            id="search-page-input" 
            name="s"
            class="c-search-bar-input" 
            placeholder="Search field goes here"
            value="<?php echo esc_attr(get_search_query()); ?>"
          />
          <button type="submit" class="c-search-bar-button" aria-label="Submit search">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
              <path d="M6.5 11.5C9.26142 11.5 11.5 9.26142 11.5 6.5C11.5 3.73858 9.26142 1.5 6.5 1.5C3.73858 1.5 1.5 3.73858 1.5 6.5C1.5 9.26142 3.73858 11.5 6.5 11.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M12.5 12.5L10.5 10.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>
        
        <!-- Popular Searches Dropdown -->
        <!-- <div class="c-search-suggestions" style="display: none;">
          <ul class="c-search-suggestions__list">
            <li>Popular search item lorem ipsum dolor sit amet consectur</li>
            <li>Popular search item lorem ipsum dolor sit amet consectur</li>
            <li>Popular search item lorem ipsum dolor sit amet consectur</li>
          </ul>
        </div> -->
      </form>
    </div>
  </div>
</section>



<div class="o-layout-row">
  <main id="main-content" class="o-wrapper-wide" role="main" itemscope itemprop="mainContentOfPage" itemtype="https://schema.org/WebPageElement">



    <!-- Search Results Section -->
    <section class="c-search-results">
      <div class="c-search-content">
        
        <?php if (have_posts()) : ?>
          
          <div class="c-search-results-grid">
            <?php while (have_posts()) : the_post(); ?>
              
              <article <?php post_class('c-search-card'); ?> role="article">
                
                
                
                <div class="c-search-card__content">
                  <header class="c-search-card__header">
                    <h2 class="c-search-card__title">
                      <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                        <?php the_title(); ?>
                      </a>
                    </h2>
                  </header>
                  
                  <div class="c-search-card__excerpt">
                    <?php echo wp_trim_words(get_the_excerpt(), 40, '...'); ?>
                  </div>
                </div>
                
              </article>
              
            <?php endwhile; ?>
          </div>
          
          <?php get_template_part('template-part/post/post-nav'); ?>
          
        <?php else : ?>
          
          <div class="c-search-no-results">
            <h2 class="c-search-no-results__title"><?php _e("Sorry, No Results.", "flexdev"); ?></h2>
            <p class="c-search-no-results__text"><?php _e("Please try another search.", "flexdev"); ?></p>
          </div>
          
        <?php endif; ?>
        
      </div>
    </section>

  </main>
</div>
<!-- /layout-row-->

<?php get_footer(); ?>

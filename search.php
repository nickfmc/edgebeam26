<?php get_header(); ?>

<!-- Hero Section with Blue Gradient Background -->
<section class="c-search-hero">
  <div class="c-search-hero__overlay"></div>
  <div class="c-search-hero__content">
    <h1 class="c-search-hero__title">Search Results</h1>
    <p class="c-search-hero__description">
      Didn't find what you're looking for? Try adjusting your keywords and querying again using the search bar below. Still have questions or want to see how this works in practice? <a href="<?php echo esc_url(home_url('/contact')); ?>" class="c-search-hero__link">Contact our team</a>.
    </p>
  </div>
</section>

    <!-- Search Bar Section -->
    <section class="c-search-bar-section">
      <div class="c-search-bar-container o-wrapper-wide">
        <label for="search-page-input" class="c-search-bar-label">Search</label>
        <div class="c-search-bar-wrapper">
          <input 
            type="search" 
            id="search-page-input" 
            class="c-search-bar-input" 
            placeholder="Search field goes here"
            value="<?php echo esc_attr(get_search_query()); ?>"
          />
          <button type="button" class="c-search-bar-button">
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
                
                <div class="c-search-card__image">
                  <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('medium', array('class' => 'c-search-card__thumbnail')); ?>
                  <?php else : ?>
                    <div class="c-search-card__placeholder">
                      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 16L8.586 11.414C9.367 10.633 10.633 10.633 11.414 11.414L16 16M14 14L15.586 12.414C16.367 11.633 17.633 11.633 18.414 12.414L20 14M14 8H14.01M6 20H18C19.105 20 20 19.105 20 18V6C20 4.895 19.105 4 18 4H6C4.895 4 4 4.895 4 6V18C4 19.105 4.895 20 6 20Z" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </div>
                  <?php endif; ?>
                </div>
                
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

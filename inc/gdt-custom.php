<?php
/**
 * Custom functions for this project? If yes, drop them here!
 */

  // If using acf icon picker - https://github.com/houke/acf-icon-picker -  modify the path to the icons directory
//   add_filter( 'acf_icon_path_suffix', 'acf_icon_path_suffix' );

//   function acf_icon_path_suffix( $path_suffix ) {
//       return 'img/icons/';
//   }
  
//used for Stackable blocks support - match to wrapper width 
global $content_width;
$content_width = 1180;


// ****************** Grab custom data and input into Generate Block headline field ************

                  // function custom_field_gb_query() {
                  //   // Start output buffering
                  //   ob_start();
                  //     //  shortcode content
                      

                  //   $output = ob_get_clean();

                  //   // Return the content as a string
                  //   return $output;
                  // }
                  // add_shortcode('custom_field_gb_query', 'custom_field_gb_query');



                  // add_filter( 'render_block_generateblocks/headline', function( $block_content, $block ) {
                  //   if ( 
                  //     !is_admin() && 
                  //     ! empty( $block['attrs']['className'] ) && 
                  //     strpos( $block['attrs']['className'], 'is-field-name' ) !== false 
                  //   ) {
                  //     $post_id = get_the_ID();
                  //     $block_content = do_shortcode('[custom_field_gb_query]');		
                  //   }

                  //   return $block_content;
                  // }, 10, 2 );

// ****************** Grab custom data and input into Generate Block headline field ************

// Calculate estimated read time for post
function gdt_get_read_time() {
  $post_content = get_post_field('post_content', get_the_ID());
  $word_count = str_word_count(strip_tags($post_content));
  $read_time = ceil($word_count / 200); // Average reading speed: 200 words per minute
  
  return $read_time;
}

// ****************** WP Grid Builder Category Facet Customization ************

/**
 * Modify the actual HTML output to add icons and color attributes
 */
add_filter( 'wp_grid_builder/facet/html', function( $html, $facet_id ) {
  // Check if this HTML has category inputs (name="cats")
  if ( strpos( $html, 'name="cats"' ) === false ) {
    return $html;
  }
  
  // Get all categories and their data
  $categories = get_categories( array( 'hide_empty' => false ) );
  
  foreach ( $categories as $category ) {
    $category_color = get_field( 'category_color', 'category_' . $category->term_id );
    $category_icon = get_field( 'category_icon', 'category_' . $category->term_id );
    
    // Skip if no color or icon
    if ( ! $category_color && ! $category_icon ) {
      continue;
    }
    
    // FIRST: Add color attributes to the button div (do this before modifying label)
    if ( $category_color ) {
      // Simple pattern: find button div followed by input with our category slug
      $slug_escaped = preg_quote( $category->slug, '/' );
      $button_pattern = '/(<div class="wpgb-button")((?:\s+[^>]*)?>)(\s*<input[^>]*?name="cats"[^>]*?value="' . $slug_escaped . '")/';
      
      $html = preg_replace_callback( $button_pattern, function( $matches ) use ( $category_color ) {
        $div_start = $matches[1];  // <div class="wpgb-button"
        $div_attrs = $matches[2];   // any existing attributes + >
        $input = $matches[3];       // the input tag
        
        // Insert our attributes before the closing >
        $new_attrs = ' data-category-color="' . esc_attr( $category_color ) . '" style="--category-color: ' . esc_attr( $category_color ) . ';"';
        
        return $div_start . $new_attrs . $div_attrs . $input;
      }, $html );
    }
    
    // SECOND: Add icon to the button label (after color is added)
    if ( $category_icon ) {
      $label_pattern = '/(<input[^>]*?name="cats"[^>]*?value="' . preg_quote( $category->slug, '/' ) . '"[^>]*>.*?<span class="wpgb-button-label">)(' . preg_quote( $category->name, '/' ) . ')(<\/span>)/s';
      
      $html = preg_replace_callback( $label_pattern, function( $matches ) use ( $category_icon ) {
        $before_label = $matches[1];
        $label_text = $matches[2];
        $after_label = $matches[3];
        
        // Add icon before label text
        $icon_html = '<img src="' . esc_url( $category_icon ) . '" alt="" class="category-icon" />';
        
        return $before_label . $icon_html . $label_text . $after_label;
      }, $html );
    }
  }
  
  return $html;
}, 10, 2 );

// ****************** End WP Grid Builder Category Facet Customization ************

?>

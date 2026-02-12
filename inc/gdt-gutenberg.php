<?php
/**
 * All your Gutenbergs belong to GDT. What does that mean?
 * Anyway, theme support and custom stuff related to Gutenberg belongs below.
 */

// ++ NOTE: More theme support options are available. Info here: 
// https://developer.wordpress.org/block-editor/developers/themes/theme-support/


// ++ Add the corresponding classname to wide block wrappers ( alignwide or alignfull )
add_theme_support( 'align-wide' ); 
add_theme_support( 'responsive-embeds' );


// ++ Enqueue block editor customization file...That is where we do our Gutenberg hackin'
function gdt_customizing_blocks() {
  wp_enqueue_script(
      'gdt_customizing_blocks',
      get_theme_file_uri('/dist/gutenberg-custom.js'), 
    array('wp-blocks', 'wp-dom'), 
    filemtime( get_stylesheet_directory() . '/dist/gutenberg-custom.js' ),
    true
  );
}
add_action( 'enqueue_block_editor_assets', 'gdt_customizing_blocks' );


/**
 * Add a block category for if it doesn't exist already.
 *
 * @param array $categories Array of block categories.
 *
 * @return array
 */



function gdt_custom_block_categories( $categories ) {
  // Check if our custom category already exists
  $category_exists = false;
  foreach ( $categories as $category ) {
    if ( $category['slug'] === 'gdt-blocks' ) {
      $category_exists = true;
      break;
    }
  }
  
  // Add our custom category if it doesn't exist
  if ( ! $category_exists ) {
    $categories = array_merge(
      array(
        array(
          'slug'  => 'gdt-blocks',
          'title' => __( 'Custom Blocks', 'gdt-theme' ),
          'icon'  => 'block-default',
        ),
      ),
      $categories
    );
  }
  
  return $categories;
}
add_filter( 'block_categories_all', 'gdt_custom_block_categories' );




/**
 * Register custom blocks
 */
function gdt_register_custom_blocks() {
  // Get the build directory (centralized build system)
  $build_dir = get_template_directory() . '/blocks/build';
  
  if ( ! file_exists( $build_dir ) ) {
    error_log( 'GDT Blocks: Build directory does not exist: ' . $build_dir );
    return;
  }
  
  // Scan for block directories in the build folder
  $block_folders = array_filter( glob( $build_dir . '/*' ), 'is_dir' );
  
  if ( empty( $block_folders ) ) {
    error_log( 'GDT Blocks: No block folders found in: ' . $build_dir );
    return;
  }
  
  // Register each block
  foreach ( $block_folders as $block_folder ) {
    $block_json = $block_folder . '/block.json';
    $block_name = basename( $block_folder );
    
    if ( file_exists( $block_json ) ) {
      $result = register_block_type( $block_folder );
      
      // Log registration attempts for debugging
      if ( $result ) {
        error_log( 'GDT Blocks: Successfully registered block: ' . $block_name );
        
        // Extra logging for keller-cares-card
        if ( $block_name === 'keller-cares-card' ) {
          error_log( 'GDT Blocks: Keller Cares Card block registered with name: ' . $result->name );
        }
      } else {
        error_log( 'GDT Blocks: Failed to register block: ' . $block_name );
      }
    } else {
      error_log( 'GDT Blocks: Missing block.json in: ' . $block_folder );
    }
  }
}
add_action( 'init', 'gdt_register_custom_blocks' );

/**
 * Enqueue block styles for both editor and frontend
 */
function gdt_enqueue_block_styles() {
  // Check if blocks CSS file exists
  $blocks_css = get_template_directory() . '/dist/blocks.css';
  
  if ( file_exists( $blocks_css ) ) {
    wp_enqueue_style(
      'gdt-blocks-style',
      get_theme_file_uri( '/dist/blocks.css' ),
      array(),
      filemtime( $blocks_css )
    );
  }
}
add_action( 'wp_enqueue_scripts', 'gdt_enqueue_block_styles' );
add_action( 'enqueue_block_editor_assets', 'gdt_enqueue_block_styles' );
?>
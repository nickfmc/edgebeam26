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

  /**
   * Helper: convert a hex color (#rrggbb or #rgb) to "r, g, b" string
   * used for --category-color-rgb so the glow box-shadow alpha works.
   */
  $hex_to_rgb = function( $hex ) {
    $hex = ltrim( $hex, '#' );
    if ( strlen( $hex ) === 3 ) {
      $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    }
    $r = hexdec( substr( $hex, 0, 2 ) );
    $g = hexdec( substr( $hex, 2, 2 ) );
    $b = hexdec( substr( $hex, 4, 2 ) );
    return "$r, $g, $b";
  };
  
  // Get all categories and their data
  $categories = get_categories( array( 'hide_empty' => false ) );
  
  foreach ( $categories as $category ) {
    $category_color        = get_field( 'category_color',       'category_' . $category->term_id );
    $category_icon         = get_field( 'category_icon',        'category_' . $category->term_id );
    $category_icon_svg     = get_field( 'category_icon_svg',    'category_' . $category->term_id );
    // ACF select field: "invert" (default) or "dark" — controls active-state styling
    $category_active_style = get_field( 'category_active_style', 'category_' . $category->term_id );
    
    // Skip if no color or icon
    if ( ! $category_color && ! $category_icon && ! $category_icon_svg ) {
      continue;
    }
    
    // FIRST: Add color attributes to the button div (do this before modifying label)
    if ( $category_color ) {
      $slug_escaped = preg_quote( $category->slug, '/' );
      $button_pattern = '/(<div class="wpgb-button")((?:\s+[^>]*)?>)(\s*<input[^>]*?name="cats"[^>]*?value="' . $slug_escaped . '")/';
      
      $html = preg_replace_callback( $button_pattern, function( $matches ) use ( $category_color, $category_active_style, $hex_to_rgb ) {
        $div_start = $matches[1];  // <div class="wpgb-button"
        $div_attrs = $matches[2];   // any existing attributes + >
        $input = $matches[3];       // the input tag

        $rgb = $hex_to_rgb( $category_color );

        // Build data attributes
        $new_attrs = ' data-category-color="' . esc_attr( $category_color ) . '"';

        // Only add data-active-style when the "dark" option is chosen in ACF
        if ( 'dark' === $category_active_style ) {
          $new_attrs .= ' data-active-style="dark"';
        }

        // CSS custom properties: color + RGB breakdown for glow opacity
        $new_attrs .= ' style="--category-color: ' . esc_attr( $category_color ) . '; --category-color-rgb: ' . esc_attr( $rgb ) . ';"';
        
        return $div_start . $new_attrs . $div_attrs . $input;
      }, $html );
    }
    
    // SECOND: Add icon to the button label (after color is added)
    if ( $category_icon_svg || $category_icon ) {
      $label_pattern = '/(<input[^>]*?name="cats"[^>]*?value="' . preg_quote( $category->slug, '/' ) . '"[^>]*>.*?<span class="wpgb-button-label">)(' . preg_quote( $category->name, '/' ) . ')(<\/span>)/s';
      
      $html = preg_replace_callback( $label_pattern, function( $matches ) use ( $category_icon, $category_icon_svg ) {
        $before_label = $matches[1];
        $label_text = $matches[2];
        $after_label = $matches[3];
        
        if ( $category_icon_svg ) {
          // Inline SVG — use currentColor so CSS fully controls the colour.
          // SVG must have fill="currentColor" on its paths for this to work.
          $allowed_svg = wp_kses( $category_icon_svg, [
            'svg'      => [ 'xmlns' => [], 'viewbox' => [], 'width' => [], 'height' => [], 'fill' => [], 'class' => [], 'aria-hidden' => [], 'role' => [] ],
            'path'     => [ 'd' => [], 'fill' => [], 'stroke' => [], 'stroke-width' => [], 'stroke-linejoin' => [], 'stroke-linecap' => [], 'fill-rule' => [], 'clip-rule' => [] ],
            'circle'   => [ 'cx' => [], 'cy' => [], 'r' => [], 'fill' => [], 'stroke' => [], 'stroke-width' => [] ],
            'rect'     => [ 'x' => [], 'y' => [], 'width' => [], 'height' => [], 'fill' => [], 'rx' => [], 'ry' => [], 'stroke' => [], 'stroke-width' => [] ],
            'g'        => [ 'fill' => [], 'stroke' => [], 'transform' => [], 'fill-rule' => [], 'clip-rule' => [] ],
            'polygon'  => [ 'points' => [], 'fill' => [], 'stroke' => [], 'stroke-width' => [] ],
            'polyline' => [ 'points' => [], 'fill' => [], 'stroke' => [], 'stroke-width' => [] ],
            'line'     => [ 'x1' => [], 'y1' => [], 'x2' => [], 'y2' => [], 'stroke' => [], 'stroke-width' => [], 'stroke-linecap' => [] ],
            'defs'     => [],
            'clippath' => [ 'id' => [] ],
            'use'      => [ 'href' => [], 'xlink:href' => [] ],
          ] );
          $icon_html = '<span class="category-icon" aria-hidden="true">' . $allowed_svg . '</span>';
        } else {
          // Fallback to <img> if no SVG code entered
          $icon_html = '<img src="' . esc_url( $category_icon ) . '" alt="" class="category-icon" />';
        }
        
        return $before_label . $icon_html . $label_text . $after_label;
      }, $html );
    }
  }
  
  return $html;
}, 10, 2 );

// ****************** End WP Grid Builder Category Facet Customization ************

/**
 * GenerateBlocks Pro - Theme Integration
 * 
 * Custom Walker for WordPress Navigation with Mega Menu Support
 * 
 * This snippet allows you to use GenerateBlocks Pro overlay panels as mega menus
 * with standard WordPress navigation (wp_nav_menu) without requiring the 
 * GenerateBlocks Navigation block.
 * 
 * INSTRUCTIONS:
 * 1. Copy this file to your theme directory (e.g., /wp-content/themes/your-theme/)
 * 2. Include it in your theme's functions.php:
 *    require_once get_template_directory() . '/theme_integration.php';
 * 3. Use the walker in your wp_nav_menu() calls (see example at bottom)
 * 
 * @package GenerateBlocks Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom Walker for GenerateBlocks Pro Mega Menus
 * 
 * Extends WordPress's default menu walker to add support for overlay panels
 * configured as mega menus in the WordPress menu editor.
 */
class GB_Pro_Mega_Menu_Walker extends Walker_Nav_Menu {
	
	/**
	 * Mega menu trigger type (click or hover)
	 * 
	 * @var string
	 */
	private $trigger_type = 'click';
	
	/**
	 * Cache for mega menu visibility checks
	 * 
	 * @var array
	 */
	private static $mega_menu_cache = [];

	/**
	 * Constructor
	 * 
	 * @param string $trigger_type The trigger type for mega menus ('click' or 'hover').
	 */
	public function __construct( $trigger_type = 'click' ) {
		$this->trigger_type = in_array( $trigger_type, [ 'click', 'hover' ], true ) ? $trigger_type : 'click';
	}

	/**
	 * Check if a mega menu should be displayed
	 * 
	 * Checks if the overlay exists, is published, and passes any display conditions.
	 * 
	 * @param int $overlay_id The overlay post ID.
	 * @return bool Whether the mega menu should be displayed.
	 */
	private function should_display_mega_menu( $overlay_id ) {
		// Check cache first.
		if ( isset( self::$mega_menu_cache[ $overlay_id ] ) ) {
			return self::$mega_menu_cache[ $overlay_id ];
		}

		// Check if overlay exists and is published.
		$overlay_post = get_post( $overlay_id );
		if ( ! $overlay_post || 'gblocks_overlay' !== $overlay_post->post_type || 'publish' !== $overlay_post->post_status ) {
			self::$mega_menu_cache[ $overlay_id ] = false;
			return false;
		}

		// Check display conditions if GenerateBlocks Pro is active.
		if ( class_exists( 'GenerateBlocks_Pro_Overlays' ) && class_exists( 'GenerateBlocks_Pro_Conditions' ) ) {
			$display_condition = GenerateBlocks_Pro_Overlays::get_overlay_meta( $overlay_id, '_gb_overlay_display_condition' );
			$display_conditions = [];

			if ( $display_condition ) {
				$condition_post = get_post( $display_condition );
				if ( $condition_post && 'publish' === $condition_post->post_status ) {
					$display_conditions = get_post_meta( $display_condition, '_gb_conditions', true ) ?? [];
				}
			}

			$show = true;

			if ( ! empty( $display_conditions ) ) {
				$show = GenerateBlocks_Pro_Conditions::show( $display_conditions );
				$invert_condition = GenerateBlocks_Pro_Overlays::get_overlay_meta( $overlay_id, '_gb_overlay_display_condition_invert' );

				// If invert is enabled, flip the result.
				if ( $invert_condition ) {
					$show = ! $show;
				}
			}

			self::$mega_menu_cache[ $overlay_id ] = $show;
			return $show;
		}

		// Default to showing if no conditions system is available.
		self::$mega_menu_cache[ $overlay_id ] = true;
		return true;
	}

	/**
	 * Starts the element output.
	 *
	 * Adds mega menu support to menu items by:
	 * - Adding data attributes for overlay triggers
	 * - Adding ARIA attributes for accessibility
	 * - Outputting the mega menu overlay content
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		// Get mega menu ID from post meta.
		$mega_menu_id = get_post_meta( $item->ID, '_gb_mega_menu', true );
		$has_mega_menu = $mega_menu_id && $this->should_display_mega_menu( $mega_menu_id );
		
		// Build the standard menu item.
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		
		$classes = empty( $item->classes ) ? [] : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		
		// Add mega menu class if applicable.
		if ( $has_mega_menu ) {
			$classes[] = 'menu-item-has-mega-menu';
		}
		
		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param WP_Post  $item  Menu item data object.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );
		
		/**
		 * Filters the CSS classes applied to a menu item's list item element.
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
		
		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
		
		$output .= $indent . '<li' . $id . $class_names . '>';
		
		$atts = [];
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';
		
		// Add mega menu attributes.
		if ( $has_mega_menu ) {
			$overlay_id = 'gb-overlay-' . $mega_menu_id;
			
			// Create a unique ID for this menu link that can be referenced for positioning.
			$anchor_id = 'gb-menu-anchor-' . $item->ID;
			$atts['id'] = $anchor_id;
			
			// Required attributes for mega menu functionality.
			$atts['data-gb-overlay'] = $overlay_id;
			$atts['data-gb-overlay-trigger-type'] = $this->trigger_type;
			$atts['aria-controls'] = $overlay_id;
			$atts['aria-haspopup'] = 'menu';
			
			// Add role and aria-expanded for click-based menus.
			if ( 'click' === $this->trigger_type ) {
				$atts['role'] = 'button';
				$atts['aria-expanded'] = 'false';
			}
		}
		
		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title        Title attribute.
		 *     @type string $target       Target attribute.
		 *     @type string $rel          The rel attribute.
		 *     @type string $href         The href attribute.
		 *     @type string $aria_current The aria-current attribute.
		 * }
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
		
		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}
		
		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );
		
		/**
		 * Filters a menu item's title.
		 *
		 * @param string   $title The menu item's title.
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
		
		$arrow = ( $has_mega_menu && 0 === $depth )
			? '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="8" viewBox="0 0 12 8" fill="none" class="mega-menu-arrow" aria-hidden="true" style="margin-left: 10px;"><path d="M0.733398 0.679562L5.7334 6.07728L10.7334 0.679562" stroke="#0047BB" stroke-width="2" stroke-linejoin="round"/></svg>'
			: '';

		$item_output = $args->before ?? '';
		$item_output .= '<a' . $attributes . '>';
		$item_output .= ( $args->link_before ?? '' ) . $title . $arrow . ( $args->link_after ?? '' );
		$item_output .= '</a>';
		$item_output .= $args->after ?? '';
		
		// Output the mega menu overlay content.
		if ( $has_mega_menu ) {
			$action_id = 'gb-mega-menu-' . $mega_menu_id;
			ob_start();
			
			/**
			 * Fires when outputting a mega menu overlay.
			 * 
			 * GenerateBlocks Pro hooks into this action to output the overlay content.
			 * 
			 * @param WP_Post $item The menu item object.
			 */
			do_action( $action_id, $item );
			
			$mega_menu_content = ob_get_clean();
			$item_output .= $mega_menu_content;
		}
		
		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $item        Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

/**
 * Shortcode to display WordPress menus
 * 
 * Usage: [display_menu menu="main-menu"]
 * 
 * Parameters:
 * - menu: Menu name, slug, or ID (required)
 * - container: Wrapper element (default: 'div')
 * - container_class: CSS class for wrapper
 * - container_id: ID for wrapper
 * - menu_class: CSS class for menu ul element
 * - menu_id: ID for menu ul element
 * - depth: How many levels of the menu to include (default: 0 = all levels)
 * - fallback: Show page list if menu doesn't exist (default: false)
 * 
 * Examples:
 * [display_menu menu="primary-menu"]
 * [display_menu menu="footer-menu" container_class="footer-nav" menu_class="footer-menu-list"]
 * [display_menu menu="23" depth="1"]
 */
function gdt_display_menu_shortcode( $atts ) {
	// Parse shortcode attributes with defaults
	$atts = shortcode_atts( array(
		'menu'            => '',
		'container'       => 'div',
		'container_class' => '',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => '',
		'depth'           => 0,
		'fallback'        => false,
	), $atts, 'display_menu' );
	
	// Validate that menu parameter is provided
	if ( empty( $atts['menu'] ) ) {
		return '<p>Please specify a menu name, slug, or ID.</p>';
	}
	
	// Build wp_nav_menu arguments
	$menu_args = array(
		'menu'            => $atts['menu'],
		'container'       => $atts['container'],
		'container_class' => $atts['container_class'],
		'container_id'    => $atts['container_id'],
		'menu_class'      => $atts['menu_class'],
		'menu_id'         => $atts['menu_id'],
		'depth'           => intval( $atts['depth'] ),
		'echo'            => false,
		'fallback_cb'     => filter_var( $atts['fallback'], FILTER_VALIDATE_BOOLEAN ) ? 'wp_page_menu' : false,
	);
	
	// Get the menu HTML
	$menu_html = wp_nav_menu( $menu_args );
	
	// Return empty string if menu doesn't exist and no fallback
	if ( ! $menu_html ) {
		return '';
	}
	
	return $menu_html;
}
add_shortcode( 'display_menu', 'gdt_display_menu_shortcode' );

/**
 * Get first category icon HTML (Function version for GenerateBlocks dynamic content)
 * 
 * This function version works properly in GenerateBlocks query loops.
 * Use this with GB's dynamic content or "Generate Custom Content" block option.
 * 
 * @param int $post_id Optional post ID. If not provided, uses current post.
 * @param bool $show_name Whether to show category name
 * @param string $wrapper_class CSS class for wrapper
 * @return string HTML output
 */
function gdt_get_first_category_icon( $post_id = 0, $show_name = false, $wrapper_class = 'category-icon-wrapper' ) {
	// Get post ID if not provided
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	
	if ( ! $post_id ) {
		return '';
	}
	
	// Get the first category
	$categories = get_the_category( $post_id );
	
	if ( empty( $categories ) ) {
		return '';
	}
	
	$first_category = $categories[0];
	
	// Get ACF fields
	$category_icon = get_field( 'category_icon', 'category_' . $first_category->term_id );
	$category_color = get_field( 'category_color', 'category_' . $first_category->term_id );
	
	if ( ! $category_icon ) {
		return '';
	}
	
	// Build output
	$output = '<div class="' . esc_attr( $wrapper_class ) . '"';
	$output .= ' data-post-id="' . esc_attr( $post_id ) . '" data-category="' . esc_attr( $first_category->slug ) . '"';
	
	if ( $category_color ) {
		$output .= ' style="background-color: ' . esc_attr( $category_color ) . ';"';
	}
	
	$output .= '>';
	$output .= '<img src="' . esc_url( $category_icon ) . '" alt="' . esc_attr( $first_category->name ) . '" class="category-icon" />';
	
	if ( $show_name ) {
		$output .= '<span class="category-name">' . esc_html( $first_category->name ) . '</span>';
	}
	
	$output .= '</div>';
	
	return $output;
}

/**
 * Register dynamic content source for GenerateBlocks Pro
 * This allows the function to be used in GB query loops properly
 */
add_filter( 'generateblocks_dynamic_content_output', function( $content, $attributes ) {
	// Check if this is our custom function call
	if ( isset( $attributes['dynamicContentType'] ) && $attributes['dynamicContentType'] === 'custom-field' ) {
		if ( isset( $attributes['customField'] ) && $attributes['customField'] === 'gdt_first_category_icon' ) {
			$post_id = isset( $attributes['id'] ) ? $attributes['id'] : get_the_ID();
			return gdt_get_first_category_icon( $post_id );
		}
	}
	return $content;
}, 10, 2 );

/**
 * Alternative: Hook into GenerateBlocks text/headline to display category icon
 * Add the class 'show-category-icon' to any text or headline block in a query loop
 */
add_filter( 'render_block_generateblocks/text', function( $block_content, $block ) {
	// Check if this block has our custom class
	if ( ! empty( $block['attrs']['className'] ) && 
	     strpos( $block['attrs']['className'], 'show-category-icon' ) !== false ) {
		
		// Try to get post ID from block context
		$post_id = 0;
		if ( ! empty( $block['context']['postId'] ) ) {
			$post_id = $block['context']['postId'];
		}
		
		// Get the category icon HTML
		$icon_html = gdt_get_first_category_icon( $post_id );
		
		// Prepend icon to the block content
		if ( $icon_html ) {
			// Find the opening div tag and insert after it
			$block_content = preg_replace(
				'/(<div[^>]*class="[^"]*gb-text[^"]*"[^>]*>)/',
				'$1' . $icon_html,
				$block_content,
				1
			);
		}
	}
	
	return $block_content;
}, 10, 2 );

add_filter( 'render_block_generateblocks/headline', function( $block_content, $block ) {
	// Check if this headline has our custom class
	if ( ! empty( $block['attrs']['className'] ) && 
	     strpos( $block['attrs']['className'], 'show-category-icon' ) !== false ) {
		
		// Try to get post ID from block context
		$post_id = 0;
		if ( ! empty( $block['context']['postId'] ) ) {
			$post_id = $block['context']['postId'];
		}
		
		// Get the category icon HTML
		$icon_html = gdt_get_first_category_icon( $post_id );
		
		// Prepend icon to the headline content
		if ( $icon_html ) {
			// Find the opening tag and insert after it
			$block_content = preg_replace(
				'/(<[^>]+class="[^"]*gb-headline[^"]*"[^>]*>)/',
				'$1' . $icon_html,
				$block_content,
				1
			);
		}
	}
	
	return $block_content;
}, 10, 2 );

/**
 * Display First Category Icon and Color
 * 
 * Displays the icon and background color from the first category of the current post.
 * Works within post loops and queries by using the current post ID from the loop context.
 * Uses ACF fields: category_icon and category_color
 * 
 * Usage: [first_category_icon]
 * 
 * Parameters:
 * - show_name: Display category name (default: false)
 * - wrapper_class: CSS class for wrapper element (default: 'category-icon-wrapper')
 * - post_id: Specific post ID (optional, defaults to current post in loop)
 * - debug: Show debug information (default: false)
 * 
 * Examples:
 * [first_category_icon]
 * [first_category_icon show_name="true"]
 * [first_category_icon wrapper_class="custom-class"]
 * [first_category_icon post_id="123"]
 * [first_category_icon debug="true"]
 */
function gdt_first_category_icon_shortcode( $atts ) {
	global $post;
	
	// Parse shortcode attributes with defaults
	$atts = shortcode_atts( array(
		'show_name'      => false,
		'wrapper_class'  => 'category-icon-wrapper',
		'post_id'        => 0,
		'debug'          => false,
	), $atts, 'first_category_icon' );
	
	$debug = filter_var( $atts['debug'], FILTER_VALIDATE_BOOLEAN );
	$debug_info = [];
	
	// Get post ID - try multiple methods
	$post_id = intval( $atts['post_id'] );
	
	if ( ! $post_id ) {
		// Try get_the_ID() first
		$post_id = get_the_ID();
		if ( $debug ) $debug_info[] = 'Used get_the_ID(): ' . $post_id;
	}
	
	if ( ! $post_id && isset( $post->ID ) ) {
		// Fall back to global $post
		$post_id = $post->ID;
		if ( $debug ) $debug_info[] = 'Used global $post->ID: ' . $post_id;
	}
	
	// Show debug if no post ID found
	if ( ! $post_id ) {
		if ( $debug ) {
			return '<div style="border: 2px solid red; padding: 10px; background: #fff;">
				<strong>DEBUG: No post ID found</strong><br>
				get_the_ID(): ' . get_the_ID() . '<br>
				$post exists: ' . ( isset( $post ) ? 'yes' : 'no' ) . '<br>
				$post->ID: ' . ( isset( $post->ID ) ? $post->ID : 'not set' ) . '
			</div>';
		}
		return '';
	}
	
	// Get the first category of the specified post
	$categories = get_the_category( $post_id );
	
	// Show debug if no categories
	if ( empty( $categories ) ) {
		if ( $debug ) {
			return '<div style="border: 2px solid orange; padding: 10px; background: #fff;">
				<strong>DEBUG: No categories found</strong><br>
				Post ID: ' . $post_id . '<br>
				Post title: ' . get_the_title( $post_id ) . '
			</div>';
		}
		return '';
	}
	
	// Get the first category
	$first_category = $categories[0];
	if ( $debug ) $debug_info[] = 'Category: ' . $first_category->name . ' (ID: ' . $first_category->term_id . ')';
	
	// Get ACF fields for this category
	$category_icon = get_field( 'category_icon', 'category_' . $first_category->term_id );
	$category_color = get_field( 'category_color', 'category_' . $first_category->term_id );
	
	if ( $debug ) {
		$debug_info[] = 'Icon: ' . ( $category_icon ? $category_icon : 'NOT SET' );
		$debug_info[] = 'Color: ' . ( $category_color ? $category_color : 'NOT SET' );
	}
	
	// Show debug if no icon
	if ( ! $category_icon ) {
		if ( $debug ) {
			return '<div style="border: 2px solid blue; padding: 10px; background: #fff;">
				<strong>DEBUG: No icon set for category</strong><br>
				Post ID: ' . $post_id . '<br>
				Category: ' . $first_category->name . ' (ID: ' . $first_category->term_id . ')<br>
				ACF Icon field: ' . ( $category_icon ? 'YES' : 'NO' ) . '<br>
				ACF Color field: ' . ( $category_color ? $category_color : 'NO' ) . '
			</div>';
		}
		return '';
	}
	
	// Build the HTML output
	$output = '<div class="' . esc_attr( $atts['wrapper_class'] ) . '"';
	
	// Add data attribute for debugging
	$output .= ' data-post-id="' . esc_attr( $post_id ) . '" data-category="' . esc_attr( $first_category->slug ) . '"';
	
	// Add inline style with background color if available
	if ( $category_color ) {
		$output .= ' style="background-color: ' . esc_attr( $category_color ) . ';"';
	}
	
	$output .= '>';
	
	// Add the icon
	$output .= '<img src="' . esc_url( $category_icon ) . '" alt="' . esc_attr( $first_category->name ) . '" class="category-icon" />';
	
	// Optionally add category name
	if ( filter_var( $atts['show_name'], FILTER_VALIDATE_BOOLEAN ) ) {
		$output .= '<span class="category-name">' . esc_html( $first_category->name ) . '</span>';
	}
	
	// Show debug info if enabled
	if ( $debug ) {
		$output .= '<div style="border: 2px solid green; padding: 5px; margin-top: 5px; font-size: 11px; background: #f0f0f0;">';
		$output .= '<strong>DEBUG INFO:</strong><br>';
		$output .= implode( '<br>', $debug_info );
		$output .= '</div>';
	}
	
	$output .= '</div>';
	
	return $output;
}
add_shortcode( 'first_category_icon', 'gdt_first_category_icon_shortcode' );

?>

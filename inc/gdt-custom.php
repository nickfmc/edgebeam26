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


// limit term list to parent-only terms
add_filter( 'generateblocks_dynamic_tag_output', function( $output, $options ) {
    if ( ! array_key_exists( 'parentonly', $options ) ) {
        return $output;
    }

    $taxonomy = $options['tax'] ?? $options['taxonomy'] ?? 'category';
    $post_id  = get_the_ID();
    $terms    = get_the_terms( $post_id, $taxonomy );

    if ( ! $terms || is_wp_error( $terms ) ) {
        return '';
    }

    $sep   = $options['sep'] ?? ', ';
    $names = array();

    foreach ( $terms as $term ) {
        if ( $term->parent === 0 ) {
            $names[] = '<span class="gb-term gb-term--' . sanitize_html_class( $term->slug ) . '">' . esc_html( $term->name ) . '</span>';
        }
    }

    if ( empty( $names ) ) {
        return '';
    }

    return implode( $sep, $names );
}, 10, 2 );

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


// ++ LOTTIE HERO SEQUENCE SHORTCODE ++++++++++++++++++++++++++

/**
 * [lottie_hero] shortcode
 *
 * Outputs a sequenced Lottie animation hero container.
 *
 * Attributes:
 *   files  Comma-separated filenames from /img/lottie/ (default: all four)
 *   fade   Crossfade duration in milliseconds (default: 600) 
 *
 * Example: [lottie_hero files="home1.json,home2.json,home3.json,home4.json" fade="600"]
 */
function lottie_hero_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'files' => 'home1.json,home2.json,home3.json,home4.json',
			'fade'  => 600,
		),
		$atts,
		'lottie_hero'
	);

	// Sanitize each filename to prevent directory traversal
	$file_names = array_map( 'trim', explode( ',', $atts['files'] ) );
	$file_urls  = array();
	foreach ( $file_names as $file ) {
		$safe_name    = basename( $file );
		$file_urls[]  = get_stylesheet_directory_uri() . '/img/lottie/' . rawurlencode( $safe_name );
	}

	$fade       = absint( $atts['fade'] );
	$files_json = wp_json_encode( $file_urls );

	$output  = '<div class="c-lottie-hero" data-lottie-files="' . esc_attr( $files_json ) . '" data-fade="' . esc_attr( $fade ) . '">';
	foreach ( $file_urls as $url ) {
		$output .= '<div class="c-lottie-hero__slide"></div>';
	}
	$output .= '</div>';

	return $output;
}
add_shortcode( 'lottie_hero', 'lottie_hero_shortcode' );


// ---------------------------------------------------------------------------
// Shortcode: [device_type_modal]
//
// Outputs a trigger link that opens a popup modal listing all published device
// posts assigned to the given device_type taxonomy term.
//
// Attributes:
//   device_type    (required) — slug of the device_type taxonomy term
//   link_text      — visible label on the trigger link (default "View Devices")
//   link_class     — extra CSS class(es) added to the trigger <a>
//   heading        — modal heading; defaults to the term's display name
//   posts_per_page — number of devices to show (-1 = all, default)
//
// Example: [device_type_modal device_type="rugged-tablet" link_text="See Rugged Tablets"]
// ---------------------------------------------------------------------------

function gdt_device_type_modal_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'device_type'    => '',
			'link_text'      => 'View Devices',
			'link_class'     => '',
			'heading'        => '',
			'posts_per_page' => -1,
		),
		$atts,
		'device_type_modal'
	);

	$term_slug = sanitize_title( $atts['device_type'] );
	if ( empty( $term_slug ) ) {
		return '';
	}

	$term = get_term_by( 'slug', $term_slug, 'device_type' );
	if ( ! $term || is_wp_error( $term ) ) {
		return '';
	}

	$query = new WP_Query( array(
		'post_type'      => 'device',
		'post_status'    => 'publish',
		'posts_per_page' => intval( $atts['posts_per_page'] ),
		'tax_query'      => array(
			array(
				'taxonomy' => 'device_type',
				'field'    => 'slug',
				'terms'    => $term_slug,
			),
		),
		'no_found_rows'  => true,
	) );

	if ( ! $query->have_posts() ) {
		return '';
	}

	// Enqueue the shared JS + CSS once per page load.
	static $gdt_device_modal_assets_registered = false;
	if ( ! $gdt_device_modal_assets_registered ) {
		$gdt_device_modal_assets_registered = true;
		add_action( 'wp_footer', 'gdt_device_modal_assets', 20 );
	}

	$modal_id   = 'device-modal-' . esc_attr( $term_slug ) . '-' . wp_unique_id();
	$heading    = ! empty( $atts['heading'] ) ? esc_html( $atts['heading'] ) : esc_html( $term->name );
	$link_class = ! empty( $atts['link_class'] ) ? ' ' . esc_attr( $atts['link_class'] ) : '';

	// Build device list items.
	$devices_html = '';
	while ( $query->have_posts() ) {
		$query->the_post();
		$post_id = get_the_ID();

		$title_override = function_exists( 'get_field' ) ? get_field( 'title_overide', $post_id ) : '';
		$part_number    = function_exists( 'get_field' ) ? get_field( 'part_number',   $post_id ) : '';
		$company_logo   = function_exists( 'get_field' ) ? get_field( 'company_logo',  $post_id ) : '';

		$display_title = ! empty( $title_override ) ? $title_override : get_the_title();
		$permalink     = get_permalink();
		$excerpt       = get_the_excerpt();

		$devices_html .= '<div class="c-device-modal__item">';

		// if ( has_post_thumbnail() ) {
		// 	$devices_html .= '<div class="c-device-modal__image">'
		// 		. '<a href="' . esc_url( $permalink ) . '" tabindex="-1" aria-hidden="true">'
		// 		. get_the_post_thumbnail( $post_id, 'medium', array( 'loading' => 'lazy' ) )
		// 		. '</a></div>';
		// }

		$devices_html .= '<div class="c-device-modal__content">';

		if ( $company_logo ) {
			// ACF 'company_logo' field returns an attachment ID.
			$logo_img = wp_get_attachment_image(
				(int) $company_logo,
				'medium',
				false,
				array( 'class' => 'c-device-modal__logo-img', 'loading' => 'lazy' )
			);
			if ( $logo_img ) {
				$devices_html .= '<div class="c-device-modal__logo">' . $logo_img . '</div>';
			}
		}

		$devices_html .= '<h3 class="c-device-modal__title"><a href="' . esc_url( $permalink ) . '">' . esc_html( $display_title ) . '</a></h3>';

		if ( $part_number ) {
			$devices_html .= '<p class="c-device-modal__part-number">' . esc_html( $part_number ) . '</p>';
		}

		if ( $excerpt ) {
			$devices_html .= '<p class="c-device-modal__excerpt">' . esc_html( $excerpt ) . '</p>';
		}

		// $devices_html .= '<a class="c-device-modal__link" href="' . esc_url( $permalink ) . '">Learn More</a>';
		$devices_html .= '</div>'; // .c-device-modal__content
		$devices_html .= '</div>'; // .c-device-modal__item
	}
	wp_reset_postdata();

	// Trigger link.
	$trigger = '<a href="#' . $modal_id . '"'
		. ' class="c-device-modal__trigger' . $link_class . '"'
		. ' data-modal-id="' . $modal_id . '"'
		. ' aria-haspopup="dialog">'
		. esc_html( $atts['link_text'] )
		. '<span class="gb-shape"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" style="fill:none !important;">
  <path d="M10.4492 2.94922L17.4999 9.99994L10.4492 17.0507" stroke="CurrentColor" stroke-width="2" stroke-linejoin="round"></path>
  <path d="M17.4999 10L2.5 10" stroke="CurrentColor" stroke-width="2" stroke-linejoin="round"></path>
</svg></span>'
		. '</a>';

	// Modal overlay — JS moves it to <body> on open and restores it on close.
	$overlay  = '<div class="c-popup-overlay c-device-modal" id="' . $modal_id . '"'
		. ' role="dialog" aria-modal="true" aria-hidden="true" aria-label="' . esc_attr( $heading ) . '">';
	$overlay .= '<div class="c-popup-form c-device-modal__dialog">';
	$overlay .= '<button class="c-popup-close c-device-modal__close" aria-label="' . esc_attr__( 'Close' ) . '">'
		. '<span aria-hidden="true">&times;</span></button>';
	$overlay .= '<div class="c-device-modal__header"><h2 class="is-style-eyebrow c-device-modal__heading">Devices for ' . $heading . '</h2></div>';
	$overlay .= '<div class="c-device-modal__list">' . $devices_html . '</div>';
	$overlay .= '</div>'; // .c-popup-form
	$overlay .= '</div>'; // .c-popup-overlay

	return $trigger . $overlay;
}
add_shortcode( 'device_type_modal', 'gdt_device_type_modal_shortcode' );


/**
 * Outputs the shared CSS and JS for the device-type modal.
 * Hooked to wp_footer once per page when the shortcode is used.
 */
function gdt_device_modal_assets() {
	?>
	<script>
	(function () {
		'use strict';

		var modals    = {};
		var lastFocus = null;

		function lockScroll() {
			var scrollY = window.scrollY || window.pageYOffset;
			var scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
			document.body.dataset.scrollLockY = scrollY;
			if ( scrollbarWidth > 0 ) {
				document.body.style.paddingRight = scrollbarWidth + 'px';
			}
			document.body.style.top    = '-' + scrollY + 'px';
			document.documentElement.classList.add( 'popup-form-open' );
			document.body.classList.add( 'popup-form-open' );
		}

		function unlockScroll() {
			var scrollY = parseInt( document.body.dataset.scrollLockY || '0', 10 );
			document.documentElement.classList.remove( 'popup-form-open' );
			document.body.classList.remove( 'popup-form-open' );
			document.body.style.top          = '';
			document.body.style.paddingRight = '';
			window.scrollTo( 0, scrollY );
		}

		function openModal( overlay ) {
			lastFocus = document.activeElement;
			lockScroll();
			document.body.appendChild( overlay );
			overlay.classList.add( 'is-active' );
			overlay.setAttribute( 'aria-hidden', 'false' );
			var closeBtn = overlay.querySelector( '.c-device-modal__close' );
			if ( closeBtn ) { closeBtn.focus(); }
		}

		function closeModal( overlay ) {
			overlay.classList.remove( 'is-active' );
			overlay.setAttribute( 'aria-hidden', 'true' );
			unlockScroll();
			var entry = modals[ overlay.id ];
			if ( entry && entry.placeholder && entry.placeholder.parentNode ) {
				entry.placeholder.parentNode.insertBefore( overlay, entry.placeholder );
				entry.placeholder.parentNode.removeChild( entry.placeholder );
			}
			delete modals[ overlay.id ];
			if ( lastFocus ) { lastFocus.focus(); lastFocus = null; }
		}

		document.addEventListener( 'click', function ( e ) {
			// Trigger link opens the modal.
			var trigger = e.target.closest( '.c-device-modal__trigger' );
			if ( trigger ) {
				e.preventDefault();
				var id      = trigger.getAttribute( 'data-modal-id' );
				var overlay = document.getElementById( id );
				if ( ! overlay ) { return; }
				// Place a comment node so we can restore the overlay's original
				// DOM position when it closes.
				var placeholder = document.createComment( 'device-modal:' + id );
				overlay.parentNode.insertBefore( placeholder, overlay );
				modals[ id ] = { placeholder: placeholder };
				openModal( overlay );
				return;
			}

			// Close button.
			var closeBtn = e.target.closest( '.c-device-modal .c-device-modal__close' );
			if ( closeBtn ) {
				closeModal( closeBtn.closest( '.c-popup-overlay' ) );
				return;
			}

			// Backdrop click (click directly on the overlay, not the inner dialog).
			if (
				e.target.classList.contains( 'c-popup-overlay' ) &&
				e.target.classList.contains( 'c-device-modal' )
			) {
				closeModal( e.target );
			}
		} );

		// Keyboard: Escape to close, Tab to trap focus.
		document.addEventListener( 'keydown', function ( e ) {
			var active = document.querySelector( '.c-device-modal.is-active' );
			if ( ! active ) { return; }

			if ( e.key === 'Escape' ) {
				closeModal( active );
				return;
			}

			if ( e.key === 'Tab' ) {
				var focusable = active.querySelectorAll(
					'a[href], button:not([disabled]), input, textarea, select, [tabindex]:not([tabindex="-1"])'
				);
				if ( ! focusable.length ) { return; }
				var first = focusable[ 0 ];
				var last  = focusable[ focusable.length - 1 ];
				if ( e.shiftKey ) {
					if ( document.activeElement === first ) { e.preventDefault(); last.focus(); }
				} else {
					if ( document.activeElement === last )  { e.preventDefault(); first.focus(); }
				}
			}
		} );
	}());
	</script>
	<?php
}

/** 
 * Get Related Posts for Single Post
 * 
 * Returns 2 posts with fallback logic:
 * - 2+ posts in same category: return those from the category
 * - 1 post in same category: return it + 1 latest post
 * - 0 posts in same category: return 2 latest posts (excluding current)
 */
function gdt_get_related_posts( $post_id = 0, $count = 2 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	if ( ! $post_id ) {
		return array();
	}

	// Get the first category of the current post
	$categories = get_the_category( $post_id );
	$related_posts = array();

	if ( ! empty( $categories ) ) {
		$first_category_id = $categories[0]->term_id;

		// Query posts in the same category
		$args = array(
			'posts_per_page' => $count,
			'post__not_in'   => array( $post_id ),
			'tax_query'      => array(
				array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $first_category_id,
				),
			),
		);

		$category_posts = get_posts( $args );

		// Logic: If we have 2+ posts from category, use those
		if ( count( $category_posts ) >= $count ) {
			return array_slice( $category_posts, 0, $count );
		}

		// If we have 1 post from category, use it + latest posts
		if ( count( $category_posts ) === 1 ) {
			$related_posts = $category_posts;
			$needed = $count - 1;

			// Get latest posts excluding current and category ones
			$latest_args = array(
				'posts_per_page' => $needed,
				'post__not_in'   => array_merge( array( $post_id ), wp_list_pluck( $category_posts, 'ID' ) ),
				'orderby'        => 'date',
				'order'          => 'DESC',
			);

			$latest_posts = get_posts( $latest_args );
			$related_posts = array_merge( $related_posts, $latest_posts );

			return $related_posts;
		}
	}

	// If no category posts or no categories, get latest posts
	$latest_args = array(
		'posts_per_page' => $count,
		'post__not_in'   => array( $post_id ),
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	return get_posts( $latest_args );
}

?>

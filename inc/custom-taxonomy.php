<?php
/**
 * GutenDev Custom Taxonomy Registration Party
 * REMBEMBER -> Uncomment / add this file to functions.php
 */




// register Device Type custom taxonomy
add_action( 'init', 'gdt_device_type_reg', 0 );

function gdt_device_type_reg() {
  $singular = 'Device Type';
  $plural = 'Device Types';
  $labels = array(
    'name'              => "$plural",
    'singular_name'     => "$singular",
    'search_items'      => "Search $plural",
    'all_items'         => "All $plural",
    'parent_item'       => "Parent $singular",
    'parent_item_colon' => "Parent $singular:",
    'edit_item'         => "Edit $singular",
    'update_item'       => "Update $singular",
    'add_new_item'      => "Add New $singular",
    'new_item_name'     => "New $singular Name",
    'menu_name'         => "$plural"
  );
  $args = array(
    'public'            => true,
    'show_in_rest'      => true,
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'device-type', 'with_front' => false )
  );
  register_taxonomy( 'device_type', array( 'device' ), $args );
}

// register Partner Type custom taxonomy
add_action( 'init', 'gdt_partner_type_reg', 0 );

function gdt_partner_type_reg() {
  $singular = 'Partner Type';
  $plural = 'Partner Types';
  $labels = array(
    'name'              => "$plural",
    'singular_name'     => "$singular",
    'search_items'      => "Search $plural",
    'all_items'         => "All $plural",
    'parent_item'       => "Parent $singular",
    'parent_item_colon' => "Parent $singular:",
    'edit_item'         => "Edit $singular",
    'update_item'       => "Update $singular",
    'add_new_item'      => "Add New $singular",
    'new_item_name'     => "New $singular Name",
    'menu_name'         => "$plural"
  );
  $args = array(
    'public'            => true,
    'show_in_rest'      => true,
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'partner-type', 'with_front' => false )
  );
  register_taxonomy( 'partner_type', array( 'partner' ), $args );
}

// register Focus Areas custom taxonomy
add_action( 'init', 'gdt_focus_areas_reg', 0 );

function gdt_focus_areas_reg() {
  $singular = 'Focus Area';
  $plural = 'Focus Areas'; 
  $labels = array(
    'name'              => "$plural",
    'singular_name'     => "$singular",
    'search_items'      => "Search $plural",
    'all_items'         => "All $plural",
    'edit_item'         => "Edit $singular",
    'update_item'       => "Update $singular",
    'add_new_item'      => "Add New $singular",
    'new_item_name'     => "New $singular Name",
    'menu_name'         => "$plural"
  );
  $args = array(
    'public'            => true,
    'show_in_rest'      => true,
    'hierarchical'      => false,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'focus-area', 'with_front' => false )
  );
  register_taxonomy( 'focus_area', array( 'partner' ), $args );
}

// register Specialty custom taxonomy
add_action( 'init', 'gdt_specialty_reg', 0 ); 

function gdt_specialty_reg() {
  $singular = 'Specialty';
  $plural = 'Specialties';
  $labels = array(
    'name'              => "$plural",
    'singular_name'     => "$singular",
    'search_items'      => "Search $plural",
    'all_items'         => "All $plural",
    'edit_item'         => "Edit $singular",
    'update_item'       => "Update $singular",
    'add_new_item'      => "Add New $singular",
    'new_item_name'     => "New $singular Name",
    'menu_name'         => "$plural"
  );
  $args = array(
    'public'            => true,
    'show_in_rest'      => true,
    'hierarchical'      => false,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'specialty', 'with_front' => false )
  );
  register_taxonomy( 'specialty', array( 'partner' ), $args );
}


?>

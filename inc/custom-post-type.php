<?php
/**
 * GutenDev Custom Post Type Registration Party
 * REMBEMBER -> Uncomment / add this file to functions.php
 */


// register Devices custom post type
add_action( 'init', 'gdt_device_reg' );

function gdt_device_reg() {
  $singular = 'Device';
  $plural = 'Devices';
  $labels = array(
    'name'                 => "$plural",
    'singular_name'        => "$singular",
    'menu_name'            => "$plural",
    'name_admin_bar'       => "$singular",
    'add_new'              => 'Add New',
    'add_new_item'         => "Add New $singular",
    'new_item'             => "New $singular",
    'edit_item'            => "Edit $singular",
    'view_item'            => "View $singular",
    'all_items'            => "All $plural",
    'search_items'         => "Search $plural",
    'parent_item_colon'    => "Parent $plural:",
    'not_found'            => "No $plural Found",
    'not_found_in_trash'   => "No $plural Found in Trash",
  );
  $args = array(
    'labels'               => $labels,
    'public'               => true,
    'show_in_rest'         => true,
    'publicly_queryable'   => true,
    'exclude_from_search'  => false,
    'show_ui'              => true,
    'show_in_menu'         => true,
    'query_var'            => true,
    'menu_position'        => 22,
    'menu_icon'            => 'dashicons-smartphone',
    'rewrite'              => array( 'slug' => 'devices', 'with_front' => false ),
    'capability_type'      => 'post',
    'has_archive'          => true,
    'hierarchical'         => false,
    'supports'             => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions' )
  );
  register_post_type( 'device', $args );
}

// register Partners custom post type
add_action( 'init', 'gdt_partner_reg' );

function gdt_partner_reg() {
  $singular = 'Partner';
  $plural = 'Partners';
  $labels = array(
    'name'                 => "$plural",
    'singular_name'        => "$singular",
    'menu_name'            => "$plural",
    'name_admin_bar'       => "$singular",
    'add_new'              => 'Add New',
    'add_new_item'         => "Add New $singular",
    'new_item'             => "New $singular",
    'edit_item'            => "Edit $singular",
    'view_item'            => "View $singular",
    'all_items'            => "All $plural",
    'search_items'         => "Search $plural",
    'parent_item_colon'    => "Parent $plural:",
    'not_found'            => "No $plural Found",
    'not_found_in_trash'   => "No $plural Found in Trash",
  );
  $args = array(
    'labels'               => $labels,
    'public'               => true,
    'show_in_rest'         => true,
    'publicly_queryable'   => true,
    'exclude_from_search'  => false,
    'show_ui'              => true,
    'show_in_menu'         => true,
    'query_var'            => true,
    'menu_position'        => 23,
    'menu_icon'            => 'dashicons-groups',
    'rewrite'              => array( 'slug' => 'partners', 'with_front' => false ),
    'capability_type'      => 'post',
    'has_archive'          => true,
    'hierarchical'         => false,
    'supports'             => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions' )
  );
  register_post_type( 'partner', $args );
}


?>

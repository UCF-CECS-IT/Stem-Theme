<?php
namespace STEM\Theme;

define( 'STEM_THEME_DIR', trailingslashit( get_stylesheet_directory() ) );


// Theme foundation
include_once STEM_THEME_DIR . 'includes/config.php';
include_once STEM_THEME_DIR . 'includes/meta.php';

// Add other includes to this file as needed.
include_once STEM_THEME_DIR . 'includes/cos-legacy-admin.php';
include_once STEM_THEME_DIR . 'includes/cos-legacy-functions.php';
include_once STEM_THEME_DIR . 'includes/cos-legacy-post-types.php';
include_once STEM_THEME_DIR . 'includes/cos-legacy-shortcodes.php';
include_once STEM_THEME_DIR . 'includes/header-functions.php';
include_once STEM_THEME_DIR . 'includes/post-list-layout-people.php';
include_once STEM_THEME_DIR . 'includes/sidebar.php';

if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'Theme Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Sidebar Settings',
		'menu_title'	=> 'Sidebar',
		'parent_slug'	=> 'theme-settings',
	));

}

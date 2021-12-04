<?php

if ( ! function_exists( 'font_awesome_cpt_icons' ) ) {
	/**
	 * Adds custom WP Admin icons instead of using Dashicons
	 *
	 * @since 0.1.0
	 */
	function font_awesome_cpt_icons() {
		?>
		<style type="text/css">
			#adminmenu #menu-posts-people .wp-menu-image:before {
				content: "\f0c0";
				font-family: 'FontAwesome' !important;
				font-size: 18px !important;
			}
			#adminmenu #menu-posts-mainlink .wp-menu-image:before {
				content: "\f0c1";
				font-family: 'FontAwesome' !important;
				font-size: 18px !important;
			}
			#adminmenu #menu-posts-social_media .wp-menu-image:before {
				content: "\f099";
				font-family: 'FontAwesome' !important;
				font-size: 18px !important;
			}
			#adminmenu #menu-posts-slider .wp-menu-image:before {
				content: "\f152";
				font-family: 'FontAwesome' !important;
				font-size: 18px !important;
			}
		#adminmenu #menu-posts-cos_course .wp-menu-image:before {
			content: "\f02d";
			font-family: 'FontAwesome' !important;
			font-size: 18px !important;
		}
		#adminmenu #menu-posts-cos_smd_cpt .wp-menu-image:before {
			content: "\f045";
			font-family: 'FontAwesome' !important;
			font-size: 18px !important;
		}
		</style>
		<?php
	}
	add_action('admin_head', 'font_awesome_cpt_icons');
}

if ( ! function_exists( 'custom_titles' ) ) {

	/**
	 * Replaces default post title with individual ACF values
	 *
	 * @param string $title
	 * @since 0.1.0
	 * @return string
	 */
	function custom_titles($title) {
		$postID = get_the_ID();
		$postType = get_post_type( $postID );

		/* Note that the second field in the $_POST['fields'][***] item will vary from installation to installation */
		if( $postType == 'people' ){
		  $title = $_POST['acf']['field_4f68940783612'].', '.$_POST['acf']['field_4f6893f51db13'];
		} elseif( $postType == 'slider' ){
		  $title = $_POST['acf']['field_4f7b0930604a6'];
		} elseif( $postType == 'mainlink') {
		  $title = $_POST['acf']['field_4f7b4058c25a0'];
		} elseif( $postType == 'social_media') {
		  $title = $_POST['acf']['field_4f873078a9151'];
		} elseif( $postType == 'cos_smd_cpt') {
		  $title = $_POST['acf']['field_5554da92ac337'];
		} elseif( $postType == 'cos_course') {
		  $title = $_POST['acf']['field_53f388768023d'].' '.$_POST['acf']['field_53f388a78023e'].': '.$_POST['acf']['field_53f389713711c'];
		}

		return $title;
	  }
	  add_filter('title_save_pre','custom_titles');
}

if ( ! function_exists( 'cos_slider' ) ) {
	/**
	 * Adds Slider CPT
	 *
	 * @since 0.1.0
	 */
	function cos_slider() {
		$labels = array(
			'name'                => _x('Slides', 'post type general name'),
			'singular_name'       => _x('Slider Item', 'post type singular name'),
			'add_new'             => _x('Add New', 'slider'),
			'add_new_item'        => __('Add New Slider Item'),
			'edit_item'           => __('Edit Slider Item'),
			'new_item'            => __('New Slider Item'),
			'all_items'           => __('All Slides'),
			'view_item'           => __('View Slider Item'),
			'search_items'        => __('Search Slides'),
			'not_found'           => __('No slider items found.'),
			'not_found_in_trash'  => __('No slider items found in Trash.'),
			'parent_item_colon'   => '',
			'menu_name'           => __('Slides'),
		);

		$args = array(
			'labels'              => $labels,
			'singular_label'      => __('Slider Item'),
			'public'              => true,
			'show_ui'             => true,
			'capability_type'     => 'post',
			'hierarchical'        => true,
			'rewrite'             => true,
			'exclude_from_search' => true,
			'supports'            => array('custom-fields', 'page-attributes'),
		'taxonomies'          => array('cos_slider_cat'),
		);

		register_post_type( 'slider', $args );
	}
	add_action('init', 'cos_slider');
}


if ( ! function_exists( 'cos_slider_cat' ) ) {

	/**
	 * Adds Slider Locations taxonomy for Slider CPT
	 *
	 * @since 0.1.0
	 */
	function cos_slider_cat() {
		$labels = array(
		  'name'          => _x( 'Slide Locations', 'taxonomy general name' ),
		  'singular_name' => _x( 'Slide Location', 'taxonomy singular name' ),
		  'search_items'  => __( 'Search Slide Locations' ),
		  'all_items'     => __( 'All Slide Locations' ),
		  'edit_item'     => __( 'Edit Slide Location' ),
		  'update_item'   => __( 'Update Slide Location' ),
		  'add_new_item'  => __( 'Add New Slide Location' ),
		  'new_item_name' => __( 'New Slide Location' ),
		  'menu_name'     => __( 'Slide Locations' ),
		);

		$args = array(
		  'hierarchical'  => true,
		  'description'   => 'Designate the location that the slide should appear on.  Currently this theme supports "home" & "alumni" locations.',
		  'labels'        => $labels,
		  'show_ui'       => true,
		  'sort'          => true,
		  'args'          => array( 'orderby' => 'term_order' ),
		  'query_var'     => true,
		  'public'        => false,
		  'rewrite'       => false,//array( 'slug' => 'slide-category' ),
		);

		// create a new taxonomy
		register_taxonomy('cos_slider_cat','slider',$args);
	}
	add_action( 'init', 'cos_slider_cat' );
}

if ( ! function_exists( 'cos_social_media' ) ) {
	/**
	 * Adds Social Media CPT
	 *
	 * @since 0.1.0
	 */
	function cos_social_media() {
		$labels = array(
			'name'          => _x('Social Media', 'post type general name'),
			'singular_name' => _x('Social Media Item', 'post type singular name'),
			'add_new'       => _x('Add New', 'slider'),
			'add_new_item'  => __('Add New Social Media Item'),
			'edit_item'     => __('Edit Social Media Info'),
			'new_item'      => __('New Social Media Item'),
			'all_items'     => __('All Social Media'),
			'view_item'     => __('View Social Media Item'),
			'search_items'  => __('Search All Social Media'),
			'not_found'     => __('No social media found.'),
			'not_found_in_trash'  => __('No social media found in Trash.'),
			'parent_item_colon'   => '',
			'menu_name'           => __('Social Media'),
		);
		$args = array(
			'labels'              => $labels,
			'singular_label'      => __('Social Media Item'),
			'public'              => true,
			'show_ui'             => true,
			'capability_type'     => 'post',
			'hierarchical'        => true,
			'rewrite'             => true,
			'exclude_from_search' => true,
			'supports'            => array('custom-fields'),
		);

		register_post_type( 'social_media', $args );
	}
	add_action('init', 'cos_social_media');
}

if ( ! function_exists( 'cos_people' ) ) {
	/**
	 * Adds People CPT
	 *
	 * @since 0.1.0
	 */
	function cos_people() {

		$labels = array(
		  'name'          => _x('People', 'post type general name'),
		  'singular_name' => _x('Person', 'post type singular name'),
		  'add_new'       => _x('Add New', 'slider'),
		  'add_new_item'  => __('Add New Person'),
		  'edit_item'     => __('Edit Person Info'),
		  'new_item'      => __('New Person'),
		  'all_items'     => __('All People'),
		  'view_item'     => __('View Person'),
		  'search_items'  => __('Search All People'),
		  'not_found'     => __('No people found.'),
		  'not_found_in_trash'  => __('No people found in Trash.'),
		  'parent_item_colon'   => '',
		  'menu_name'           => __('People'),
		);

		$args = array(
		  'labels'          => $labels,
		  'singular_label'  => __('Person'),
		  'public'          => true,
		  'show_ui'         => true,
		  'capability_type' => 'post',
		  'hierarchical'    => true,
		  'rewrite'         => true,
		  'supports'        => array('custom-fields', 'revisions'),
		  'taxonomies'      => array('people_cat'),
		);

		register_post_type( 'people', $args );
	}
	add_action('init', 'cos_people');
}


if ( ! function_exists( 'people_cat' ) ) {
	/**
	 * Adds Classification taxonomy for People CPT
	 *
	 * @since 0.1.0
	 */
	function people_cat() {
		// create a new taxonomy
		register_taxonomy(
		'people_cat',
		'people',
		array(
			'label'         => __( 'Classifications' ),
			'sort'          => true,
			'hierarchical'  => true,
			'args'          => array( 'orderby' => 'term_order' ),
			'query_var'     => true,
			'rewrite'       => array( 'slug' => 'group' ),
		)
		);
	}
	add_action( 'init', 'people_cat' );
}

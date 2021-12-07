<?php
$obj              = ucfwp_get_queried_object();
$title            = ucfwp_get_header_title( $obj );
$subtitle         = ucfwp_get_header_subtitle( $obj );
$h1               = ucfwp_get_header_h1_option( $obj );
$h1_elem          = ( is_home() || is_front_page() ) ? 'h2' : 'h1'; // name is misleading but we need to override this elem on the homepage
$title_elem       = ( $h1 === 'title' ) ? $h1_elem : 'span';
$subtitle_elem    = ( $h1 === 'subtitle' ) ? $h1_elem : 'p';
$title_classes    = 'h1 d-block mt-3 mt-sm-4 mt-md-5 mb-2 mb-md-3';
$subtitle_classes = 'lead mb-2 mb-md-3';

// Display the site nav
if ( !$exclude_nav ) {
	echo ucfwp_get_nav_markup(false);
}

?>

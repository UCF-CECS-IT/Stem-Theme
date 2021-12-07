<?php

function add_person_header($header_type) {

	if ( get_post_type() == 'people' ) {
		return 'person';
	}

	return $header_type;
}

add_filter( 'ucfwp_get_header_type', 'add_person_header' );

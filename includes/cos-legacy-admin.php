<?php

function cos_people_columns_decaration( $columns )
{
	$columns = array(
		'cb'                    => '<input type="checkbox" />',
		'title'                 => 'Title',
		'person_thumbnail'      => 'Headshot (preview)',
		'person_position'       => 'Position',
		'person_classification' => 'Classifications',
		'author'                => 'Author',
		'date'                  => 'Date',
	);
	return $columns;
}

add_filter("manage_edit-people_columns", "cos_people_columns_decaration");

function cos_people_column_data($column)
{

	global $post;

	if ( $column == 'person_position') {
		$position = trim(get_field('position'));
		if (!empty($position))
			echo $position;
	} elseif ($column == 'person_thumbnail') {
		$image = wp_get_attachment_image_src(get_field('headshot'), 'thumbnail');
		if (!empty($image)) {
			echo "<img style='max-width:120px; max-height:120px;' src='" . $image[0] . "' />";
		}
	} elseif ($column == 'person_classification') {
		$terms = wp_get_post_terms($post->ID, 'people_cat', array("fields" => "names"));
		if (!empty($terms)) {
			$person_cats = "";
			foreach ($terms as $term => $value) {
				$person_cats .= " $value,";
			}
			echo rtrim($person_cats, ',');
		}
	}
}

add_action("manage_people_posts_custom_column", "cos_people_column_data");

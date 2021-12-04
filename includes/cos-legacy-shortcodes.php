<?php

/**
 * Shortcode for showing the CoS slider
 *
 * @param array $atts
 * @return void
 */
if ( ! function_exists( 'cos_show_slider_items' ) ) {
	function cos_show_slider_items( $atts ) {

		$attributes = shortcode_atts( array(
				'slider_cat' => 'home',
			), $atts );
		// Checks to see if passed in $slider_cat is a valid taxonomy
		// term (Slide Category). If so, display it's slides, if not
		// display all slides
		$term = term_exists( $attributes['slider_cat'], 'cos_slider_cat' );

		if( $term !== 0 && $term !== null ){
		  $sliderArgs = array(
			'post_type'       => 'slider',
			'tax_query'         => array(
			  array(
				'taxonomy'      => 'cos_slider_cat',
				'field'         => 'slug',
				'terms'         => $attributes['slider_cat'],
			  )
			),
			'orderby'         => 'menu_order',
			'order'           => 'ASC',
			'posts_per_page'  =>  -1,
		  );
		} else {
		  $sliderArgs = array(
			'post_type'       => 'slider',
			'orderby'         => 'menu_order',
			'order'           => 'ASC',
			'posts_per_page'  =>  -1,
		  );
		}

		  $myQuery = new WP_Query($sliderArgs);

		  if($myQuery->have_posts()) :

		  echo '<section id="sliders">';
		  echo '  <div class="flexslider">';
		  echo '   <ul class="slides">';

		  while ($myQuery->have_posts()) : $myQuery->the_post();

			  $thisID = get_the_ID();
			  $hideTitle = get_field('hide_title');

			  $expires = trim(get_field('expires'));
			  $isExpired = $expires != '' ?
				  ( $expires < date('Ymd') ? true : false )
				  : false;

		  $sliderLinkTarget = "_self";

			  $slide = array(
				  'title'          => get_field('title'),
			'title_bg_color' => get_field('title_bg_color'),
			'title_font_color' => get_field('title_font_color'),
				  'content'        => '',
				  'image'          => get_field('image'),
			'link_type'      => get_field('link_type'),
				  'page'           => get_field('page'),
				  'file_link'      => get_field('file_link'),
				  'external_link'  => get_field('external_link'),
				  'position'       => ucwords( get_field('position') ),
				  'is_disabled'    => get_field('disabled') ,
				  'is_expired'     => $isExpired, // TRUE if expired
				  'edit'           => get_edit_post_link( $thisID ),
			  );

		  // Assign the slide image to another variable for easier use.
		  // Requires the field image to be an Image Object and not a URL in ACF
		  $slide_image = $slide['image'];

		  // Skip slider item if it's expired or disabled
		  if( $slide['is_expired'] || $slide['is_disabled'] ) continue;


		  $slide_link_open  = "";
		  $slide_link_close = "";

		  // Slide should not link
			  if($slide['link_type'] === "none"){}
		  // Links to an Internal Page
		  elseif($slide['link_type'] === "internal"){
			$slide_link_open = '<a href="'.$slide['page'].'">';
			$slide_link_close = '</a>';
		  }
		  // Links to an Internal File
		  elseif(($slide['link_type'] === 'file') && !empty($slide['file_link'])){
			$slide_link_open = '<a href="'.$slide['file_link'].'" target="_blank" >';
			$slide_link_close = '</a>';
		  }
		  // Link to an External Site
		  elseif(($slide['link_type'] === 'external') && !empty($slide['external_link'])){
			if(!preg_match("^(http|https)\:\/\/^", $slide['external_link'])){
			  $slide_link_open = '<a href="http://'.$slide['external_link'].'" target="_blank" >';
			}
			else
			  $slide_link_open = '<a href="'.$slide['external_link'].'" target="_blank" >';
			$slide_link_close = '</a>';
		  }

			  // Define 'content' here to it's link at the end matches the title link
			  $slide['content'] = excerpt( get_field('content'), 150, $slide['page']);

			  // Print the Slider Information
		  echo '<li class="slide'.$slide['position'].'">'.$slide_link_open.'<img src="'.$slide_image['sizes']['large'].'" alt="'.$slide['title'].'"/>'.$slide_link_close;
		  if($hideTitle !== 'yes'){
			echo  '<div class="slideTitleContent"><h2'.(!empty($slide['title_bg_color']) ? ' style="background-color:'.$slide['title_bg_color'].';"' : '' ).'><a href="'.$slide['page'].'"'.(!empty($slide['title_font_color']) ? 'style="color:'.$slide['title_font_color'].';"' : '' ).'>'.$slide['title'].'</a></h2>';
			if(get_field('content'))
			  echo  '<p>'.$slide['content'].'</p></div>';
		  }
		  echo '</li>';

		  endwhile;

		echo '</ul>';
		echo '  </div>';
		echo '    </section>';

		endif;
		wp_reset_query();

	}
}

add_shortcode('cos_slider', 'cos_show_slider_items');

/**
 * Shortcode for showing the main links
 *
 * @param array $atts
 * @return void
 */
if ( ! function_exists( 'cos_show_main_links' ) ) {
	function cos_show_main_links( $atts ) {

		$attributes = shortcode_atts( array(
			'location' => 'home',
		), $atts );
		// Checks to see if passed in $location is a valid taxonomy
		// term (Link Location). If so, display it's Main Links, if not
		// display all Main Links
		$term = term_exists( $attributes['location'], 'cos_mainlinks_cat' );

		if( $term !== 0 && $term !== null ){
		  $mainLinkArgs = array(
			'post_type'         => 'mainlink',
			'posts_per_page'    => -1,
			'tax_query'         => array(
			  array(
				'taxonomy'      => 'cos_mainlinks_cat',
				'field'         => 'slug',
				'terms'         => $attributes['location'],
			  )
			),
			'orderby'           => 'menu_order',
			'order'             => 'ASC',
		  );
		} else {
			$mainLinkArgs = array(
				'post_type'         => 'mainlink',
				'posts_per_page'    => -1,
			'orderby'           => 'menu_order',
			'order'             => 'ASC',
			);
		}

		  $my_query = new WP_Query($mainLinkArgs);

		  if($my_query->have_posts()) :

		  echo '<section id="main_links">';
		  echo '  <div class="wrap">';

			  $numLinks = $my_query->found_posts;
			  if($numLinks > 5) $numLinks = 5;

			  while ($my_query->have_posts()) : $my_query->the_post();

			// Grab the Post ID for the Custom Fields Function
				$thisID = get_the_ID();

				$mainLink = array(
					'title'          => get_field('title'),
			  'link_type'      => get_field('link_type'),
					'link'           => get_field('link'),
					'file_link'      => get_field('file_link'),
					'external_link'  => get_field('external_link'),
					'image'          => get_field('image'),
					'slice'          => ucwords(get_field('slice')),
					'open'           => get_field('open'),
				);

			// Link to an Internal File
			if($mainLink['link_type'] === 'file')
				  $mainLink['link'] = $mainLink['file_link'];

			// Link to External File
			if( ($mainLink['link_type'] === 'external') && !preg_match("^(http|https)\:\/\/^", $mainLink['external_link']) )
			  $mainLink['link'] = "http://".$mainLink['external_link'];
			elseif( ($mainLink['link_type'] === 'external') )
			  $mainLink['link'] = $mainLink['external_link'];


					  echo '<div class="mainlink-item-'.$numLinks.'">
								  <div style="background-image: url('.$mainLink['image'].')" class="slice'.$mainLink['slice'].'"><a href="'.$mainLink['link'].'" target="_'.$mainLink['open'].'" >
									   <h2>'.$mainLink['title'].'</h2></a>
								  </div>
							  </div>';

			 endwhile;

		echo '    </div>
				  <div style="clear:both;"></div>
				</section>';

		endif; wp_reset_query();
	}
}

add_shortcode('cos_main_links', 'cos_show_main_links');

if ( ! function_exists( 'cos_show_social' ) ) {
	function cos_show_social() {

		$socialMediaArgs = array(
				'post_type' => 'social_media',
		);
		$myQuery = new WP_Query($socialMediaArgs);

		if ( $myQuery->have_posts() ) :

			echo '<ul id="socialMedia">';

			while ($myQuery->have_posts()) : $myQuery->the_post();

				$is_disabled = get_field('disabled');
				if($is_disabled === true) continue;

				// Grab the Post ID for the Custom Fields Function
				$thisID = get_the_ID();

				$social = array(
					'label'     => get_field('label'),
					'type'      => get_field('type'),
					'link'      => get_field('link'),
					'disabled'  => get_field('disabled'),
				);

				echo <<<SOCIAL
					<li>
						<a href="{$social['link']}" title="{$social['label']}" class="{$social['type']}" target="_blank"></a>
					</li>
SOCIAL;
			endwhile;

			echo '</ul>';

	  	endif;

		wp_reset_query();
	}
}

add_shortcode('cos_social', 'cos_show_social');

function show_people($atts) {
	// Theme Option: How to order people. Defaults to 'title'
	$order_people_by = get_field('cos_order_people_by', 'option');
	if (empty($order_people_by))
		$order_people_by = 'title';

	$attributes = shortcode_atts(array(
		'group'    => null,
		'hide-photos' => 'no',
	), $atts);

	if ($attributes['group'] != null) {
		$facultyArgs = array(
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'people_cat',
					'field'    => 'slug',
					'terms'    => $attributes['group'],
					//Only shows current Cat, doesn't show people in its sub cats
					'include_children' => FALSE,
				)
			),
			'orderby'  => $order_people_by,
			'order'    => 'ASC',
		);
	} else {
		$facultyArgs = array(
			'post_type'      => 'people',
			'posts_per_page' => -1,
			'orderby'        => $order_people_by,
			'order'          => 'ASC',
		);
	}

	$my_query = new WP_Query($facultyArgs);

	ob_start();
	?>
	<?php if ( $my_query->have_posts() ): ?>
		<div class="row">
			<?php while($my_query->have_posts()):
				$my_query->the_post();

				// Grab the Post ID for the Custom Fields Function
				$thisID = get_the_ID();

				// Custom Post Type image must be set to "Image ID"
				$personPhoto = wp_get_attachment_image_src(get_field('headshot'), "people-custom-image");
				// If there isn't a "people-custom-image" version graph the 'Small' size
				if (empty($personPhoto))
					$personPhoto = wp_get_attachment_image_src(get_field('headshot'), 'small');
				// If there is no photo, use the theme default
				if (!$personPhoto[0]) {
					$personPhoto[0] = get_template_directory_uri() . '/static/img/NoImageAvailable.jpg';
				}

				$personLink       = get_permalink();
				$personFirstName  = get_field('first_name');
				$personLastName   = get_field('last_name');

				$person = array(
					'position'    => get_field('position'),
					'location'    => get_field('location'),
					'phone'       => get_field('phone'),
					'email'       => antispambot(get_field('email')),
				);

				if (get_field('email') === "N/A")
					$person['email'] = "";
				?>
				<div class="col-xl-3 col-md-4 mb-3">
					<div class="card">
						<?php if ( $attributes['hide-photos'] != 'yes' ): ?>
							<a href="<?php echo $personLink; ?>"><img class="card-img-top" src="<?php echo $personPhoto[0]; ?>" alt="<?php echo $personLastName; ?>"></a>
						<?php endif; ?>

						<div class="card-body">
							<h5 class="heading-underline text-transform-none">
								<a class="text-primary" href="<?php echo $personLink; ?>">
									<?php echo $personLastName; ?>, <?php echo $personFirstName; ?>
								</a>
							</h5>

							<?php if ( array_filter( $person ) ): // display full set of personal info ?>
								<i class="icon-user"></i> <?php echo $person['position']; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endwhile; ?>
		</div>
	<?php endif; ?>

								<?php if ( ! empty( $person['position'] ) ): ?>
									<li class="person_position"></li>
								<?php endif; ?>
								<?php if ( ! empty( $person['location'] ) ): ?>
									<li class="person_location"><i class="icon-map-marker"></i> <?php echo $person['location']; ?></li>
								<?php endif; ?>
								<?php if ( ! empty( $person['phone'] ) ): ?>
									<li class="person_phone"><i class="icon-mobile-phone"></i> <?php echo $person['phone']; ?></li>
								<?php endif; ?>
								<?php if ( ! empty( $person['email'] ) ): ?>
									<li class="person_email"><i class="icon-envelope-alt"></i> <a href="mailto:<?php echo $person['email']; ?>"><?php echo $person['email']; ?></a></li>
								<?php endif; ?>

	<?php
	wp_reset_query();
	return ob_get_clean();

}
add_shortcode('show_people', 'show_people');

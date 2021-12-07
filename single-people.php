<?php

get_header();
the_post();

$args = array(
	'taxonomy' => 'people_cat',
	'orderby' => 'name',
	'order'   => 'ASC'
);

$cats = get_categories($args);

$navigationSidebar = count($cats ?? []);

//PersonBasics array used to only show info if entered
$personBasics = array(
	'person_position' => get_field('position'),
	'person_specialty'=> get_field('specialty'),
	'person_location' => get_field('location'),
	'person_phone'    => get_field('phone'),
	'person_email'    => antispambot(get_field('email')),
	'person_cv'       => get_field('curriculum_vitae'),
	'person_website'  => get_field('website_link'),
);
$custom_news_tag = get_field('cos_custom_news_tag');

// Custom Post Type image must be set to "Image ID"
$personPhoto = wp_get_attachment_image_src(get_field('headshot'), "people-custom-image");

if(empty($personPhoto))
$personPhoto = wp_get_attachment_image_src(get_field('headshot'), 'medium' );

// Person's Full Name.  Replacing any spaces with dashes and concatinating first and last name together
$fullName = str_replace(" ","-",trim(get_field('first_name')))."-".str_replace(" ","-",trim(get_field('last_name')));
// If there is a Custom News Tag, assign that to be used for the full name which is used to grab news stories
if( !empty( $custom_news_tag ) )
$fullName = str_replace( " ", "-", trim( strtolower( get_field('cos_custom_news_tag') ) ) );

// All fields beginning with 'p_' are default fields that don't appear as tabular data
$person = array(
'p_first_name'  => get_field('first_name'),
'p_last_name'   => get_field('last_name'),
'p_photo'       => $personPhoto[0],
'p_link'        => get_permalink(),
'news'          => cos_person_tag($fullName),

'p_office_hours_private' => get_field('office_hours_private'),

);

// If person doesn't have private office hours
if(($person['p_office_hours_private'] !== 'yes')&&($person['p_office_hours_private'] !== true)){

// Office Hours
$person['p_office_hours_mon'] = parse_hrs(get_field('office_hours_mon'));
$person['p_office_hours_tue'] = parse_hrs(get_field('office_hours_tue'));
$person['p_office_hours_wed'] = parse_hrs(get_field('office_hours_wed'));
$person['p_office_hours_thu'] = parse_hrs(get_field('office_hours_thu'));
$person['p_office_hours_fri'] = parse_hrs(get_field('office_hours_fri'));

$office_hours = get_hrs( $person );

}elseif( $person['p_office_hours_private'] === 'yes' ){
$office_hours = '<p class="aligncenter">Office hours are available by appointment only.  Please contact to schedule an appointment.</p>';
}

$contentTabHeadings = '';
$contentTabBody = '';

$contentCounter = '0';
$personBasicsContent = '';
$showNews = get_field('cos_person_news_feed');

if( have_rows('tabs')):
	while(have_rows('tabs')) : the_row();
		$tab_title = preg_replace('/[^A-Za-z0-9\-]/', '', get_sub_field('tab_title'));
		$active = ($tab_title == 'Biography') ? 'active' : '';

		$contentTabHeadings .= '<li class="nav-item"><a class="nav-link ' . $active. '" id="contact-tab" data-toggle="tab" href="#' . $tab_title . '" role="tab" aria-controls="' . $tab_title . '" aria-selected="false">' . get_sub_field('tab_title') . '</a></li>';

		$contentTabBody .= '<div class="tab-pane fade show px-1 py-2 ' . $active . '" id="' . $tab_title . '" role="tabpanel" aria-labelledby="home-tab">' . get_sub_field('tab_content') . '</div>';
	endwhile;
endif;

?>
<article class="<?php echo $post->post_status; ?> post-list-item">
	<div class="container mt-4 mt-sm-5 mb-5 pb-sm-4">
		<div class="row">
			<!-- Content column -->
			<div class="col">
				<!-- Header -->
				<div class="row mb-4 justify-content-center">
					<?php if ( !empty($person['p_photo']) ): ?>
						<div class=" col-md-4 col-11 text-center">
							<!-- <div class="aspect-ratio-square"> -->
								<img class="img-fluid mb-3" src="<?php echo $person['p_photo']; ?>" alt="<?php echo $peron['p_last_name']; ?>">
							<!-- </div> -->
						</div>

					<?php endif; ?>

					<div class="col-md-8">
						<h3 class=""><?php echo $person['p_first_name']; ?> <?php echo $person['p_last_name']; ?></h3>
						<hr class="bg-primary my-2">
						<?php if ( $personBasics['person_position'] ): ?>
							<div class="d-flex flex-row">
								<i class="text-muted fas fa-user mt-1 mr-3"></i>
								<span><?php echo $personBasics['person_position']; ?></span>
							</div>
						<?php endif; ?>

						<?php if ( $personBasics['person_location'] ): ?>
							<div class="d-flex flex-row">
								<i class="text-muted fas fa-map-marker-alt mt-1 mr-3"></i>
								<span><?php echo $personBasics['person_location']; ?></span>
							</div>
						<?php endif; ?>

						<?php if ( $personBasics['person_phone'] ): ?>
							<div class="d-flex flex-row">
								<i class="text-muted fas fa-mobile-alt mt-1 mr-4"></i>
								<span><?php echo $personBasics['person_phone']; ?></span>
							</div>
						<?php endif; ?>

						<?php if ( $personBasics['person_email'] ): ?>
							<div class="d-flex flex-row">
								<i class="text-muted fas fa-envelope mt-1 mr-3"></i>
								<span><a href="mailto:<?php echo $personBasics['person_email']; ?>"><?php echo $personBasics['person_email']; ?></a></span>
							</div>
						<?php endif; ?>

						<?php if ( $personBasics['person_cv'] ): ?>
							<div class="d-flex flex-row">
								<i class="text-muted fas fa-file-alt mt-1 mr-3"></i>
								<span><?php echo $personBasics['person_cv']; ?></span>
							</div>
						<?php endif; ?>

						<?php if ( $personBasics['person_website'] ): ?>
							<div class="d-flex flex-row">
								<i class="text-muted fas fa-link mt-1 mr-3"></i>
								<span><?php echo $personBasics['person_website']; ?></span>
							</div>

						<?php endif; ?>
					</div>
				</div>

				<!-- Content -->
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<?php echo $contentTabHeadings; ?>
				</ul>
				<div class="tab-content" id="myTabContent">
					<?php echo $contentTabBody; ?>
				</div>
			</div>

			<?php if ( $navigationSidebar ): ?>
				<!-- Sidebar Column -->
				<div class="col-md-3">

					<!-- Navigation -->
					<?php if ( $navigationSidebar ): ?>
						<div class="list-group mb-4">
							<a class="list-group-item font-weight-bold text-primary bg-inverse-t-3" href="#">People</a>
							<?php foreach( $cats as $cat ): ?>
								<a class="list-group-item list-group-item-action pl-4 bg-faded font-size-sm py-2" href="<?php echo get_bloginfo('url') . "/group/{$cat->slug}/"; ?>"><?php echo $cat->name; ?></a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</article>

<?php get_footer(); ?>

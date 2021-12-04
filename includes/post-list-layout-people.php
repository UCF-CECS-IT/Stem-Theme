<?php

if ( ! function_exists( 'ucfstem_post_list_layouts' ) ) {
	function ucfstem_post_list_layouts( $layouts ) {
		$layouts['stem_people'] = 'Layout for legacy STEM People CPT';

		return $layouts;
	}
}

add_filter( 'ucf_post_list_get_layouts', 'ucfstem_post_list_layouts' );

function ucfstem_post_list_display_stem_people_before($content, $posts, $atts)
{
	ob_start();
	?>
	<div class="ucf-post-list ucfwp-post-list-people stem-people-layout" id="post-list-<?php echo $atts['list_id']; ?>">
	<?php
	return ob_get_clean();
}

add_filter('ucf_post_list_display_stem_people_before', 'ucfstem_post_list_display_stem_people_before', 10, 3);


function ucfstem_post_list_display_stem_people_title($content, $posts, $atts)
{
	$formatted_title = '';

	if ($list_title = $atts['list_title']) {
		$formatted_title = '<h2 class="ucf-post-list-title">' . $list_title . '</h2>';
	}

	return $formatted_title;
}

add_filter('ucf_post_list_display_stem_people_title', 'ucfstem_post_list_display_stem_people_title', 10, 3);

function ucfstem_post_list_display_stem_people($content, $posts, $atts)
{
	if ($posts && !is_array($posts)) {
		$posts = array($posts);
	}
	ob_start();
	?>
	<?php if ($posts) : ?>

		<ul class="list-unstyled row ucf-post-list-items">
			<?php foreach ( $posts as $item ) : ?>
				<li class="col-6 col-sm-4 col-md-3 col-xl-2 mt-3 mb-2 ucf-post-list-item">
					<a class="person-link" href="<?php echo get_permalink($item->ID); ?>">
						<div class="media-background-container person-photo mx-auto">
							<img src="<?php echo wp_get_attachment_url( get_field( 'headshot', $item->ID) ); ?>" alt="" class="media-background object-fit-cover">
						</div>
						<strong class="mt-2 mb-1 d-block person-name"><?php echo get_field( 'first_name', $item->ID );?> <?php echo get_field( 'last_name', $item->ID ); ?></strong>
						<?php if ( $job_title = get_field( 'position', $item->ID ) ) : ?>
							<div class="font-italic person-job-title">
								<?php echo $job_title; ?>
							</div>
						<?php endif; ?>
						<?php if ( $email = get_field( 'email', $item->ID ) ) : ?>
							<div class="person-email">
								<a href="mailto:<?php echo $email; ?>">
									<?php echo $email; ?>
								</a>
							</div>
						<?php endif; ?>
						<?php if ( $phone = get_field( 'phone' , $item->ID ) ) : ?>
							<div class="person-job-title">
								<?php echo $phone; ?>
							</div>
						<?php endif; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>

	<?php else : ?>
		<div class="ucf-post-list-error">No results found.</div>
	<?php endif;

		return ob_get_clean();
}

add_filter('ucf_post_list_display_stem_people', 'ucfstem_post_list_display_stem_people', 10, 3);

function ucfstem_post_list_display_stem_people_after($content, $posts, $atts)
{
	ob_start();
	?>
	</div>
	<?php
	return ob_get_clean();
}

add_filter('ucf_post_list_display_stem_people_after', 'ucfstem_post_list_display_stem_people_after', 10, 3);

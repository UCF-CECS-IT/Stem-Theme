<?php

get_header();
the_post();

$navigationSidebar = get_field( 'use_navigation_sidebar', $post->ID );
$feecSidebar = get_field( 'use_feec_sidebars', $post->ID );

if ( $navigationSidebar ) {
	$sidebarOption = cos_sidebar( get_field( 'navigation_sidebar', $post->ID ) );
}

?>

<article class="<?php echo $post->post_status; ?> post-list-item">
	<div class="container mt-4 mt-sm-5 mb-5 pb-sm-4">
		<?php if ( $navigationSidebar ): ?>
			<div class="row">
				<!-- Content column -->
				<div class="col-md-9">
					<?php the_content(); ?>
				</div>

				<!-- Sidebar Column -->
				<div class="col-md-3">

					<!-- Navigation -->
					<?php if ( $navigationSidebar ): ?>
						<div class="list-group mb-4">
							<a class="list-group-item font-weight-bold text-primary bg-inverse-t-3" href="#"><?php echo get_field( 'navigation_sidebar', $post->ID ); ?></a>
							<?php while( have_rows( $sidebarOption, 'option' ) ): the_row();
								$page = get_sub_field( 'page_name' );
								$link = get_sub_field( 'link' );

								if ( $link == get_permalink( $post ) ) {
									$bg = 'bg-default';
								} else {
									$bg = 'bg-faded';
								}
								?>
								<a class="list-group-item list-group-item-action pl-4 <?php echo $bg; ?>" href="<?php echo $link; ?>"><?php echo $page; ?></a>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>

					<!-- FEEC-specific -->
					<?php if ( $feecSidebar ): ?>
						<div class="list-group mb-4">
							<a class="list-group-item font-weight-bold text-primary bg-inverse-t-3" href="#">FEEC Conference</a>
							<?php while( have_rows( 'feec_conference_pages_sidebar', 'option' ) ): the_row();
								$page = get_sub_field( 'page_name' );
								$link = get_sub_field( 'link' );

								if ( $link == get_permalink( $post ) ) {
									$bg = 'bg-default';
								} else {
									$bg = 'bg-faded';
								}
								?>
								<a class="list-group-item list-group-item-action pl-4 <?php echo $bg; ?>" href="<?php echo $link; ?>"><?php echo $page; ?></a>
							<?php endwhile; ?>
						</div>

						<ul class="list-group mb-4">
							<li class="list-group-item font-weight-bold text-primary bg-inverse-t-3">FEEC Agenda</li>
							<?php while( have_rows( 'feec_agenda_sidebar', 'option' ) ): the_row();
								$content = get_sub_field( 'content' );
								?>
								<li class="list-group-item list-group-item-action pl-4 bg-faded"><?php echo $content; ?></li>
							<?php endwhile; ?>
						</ul>

					<?php endif; ?>
				</div>
			</div>
		<?php else: ?>
			<?php the_content(); ?>
		<?php endif; ?>
	</div>
</article>

<?php get_footer(); ?>

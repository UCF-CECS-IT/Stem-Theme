<?php

get_header();
the_post();

$navigationSidebar = get_field( 'use_navigation_sidebar', $post->ID );
$feecSidebar = get_field( 'use_feec_sidebars', $post->ID );
$secmeSidebar = get_field( 'use_secme_sidebars', $post->ID );

if ( $navigationSidebar ) {
	$sidebarOption = cos_sidebar( get_field( 'navigation_sidebar', $post->ID ) );
}

?>

<article class="<?php echo $post->post_status; ?> post-list-item">
	<div class="container mt-4 mt-sm-5 mb-5 pb-sm-4">
		<?php if ( $navigationSidebar ||  $feecSidebar || $secmeSidebar ): ?>
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
								<a class="list-group-item list-group-item-action font-size-sm py-2 pl-4 <?php echo $bg; ?>" href="<?php echo $link; ?>"><?php echo $page; ?></a>
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
								<li class="list-group-item list-group-item-action pl-4 bg-faded font-size-sm py-2"><?php echo $content; ?></li>
							<?php endwhile; ?>
						</ul>

					<?php endif; ?>

					<!-- SECME-specific -->
					<?php if ( $secmeSidebar ): ?>
						<div class="list-group mb-4">
							<a class="list-group-item font-weight-bold text-primary bg-inverse-t-3" href="#">SECME</a>
							<?php while( have_rows( 'secme_links', 'option' ) ): the_row();
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
						<?php
							$secmeThemeSidebar = get_field( 'secme_theme_sidebar', 'option' );
						?>
						<div class="list-group mb-4">
							<a class="list-group-item font-weight-bold text-primary bg-inverse-t-3" href="#">
								<?php if ( $secmeThemeSidebar['year'] ?? false ): ?>
									<?php echo $secmeThemeSidebar['year']; ?>
								<?php endif; ?>
								Competition Theme
							</a>
							<?php if ( $secmeThemeSidebar['message']): ?>
								<li class="list-group-item list-group-item-action pl-4 bg-faded font-size-sm py-2">
									<div><?php echo $secmeThemeSidebar['message']; ?></div>
								</li>
							<?php endif; ?>
						</div>

						<ul class="list-group mb-4">
							<li class="list-group-item font-weight-bold text-primary bg-inverse-t-3">Dates:</li>
							<?php while( have_rows( 'secme_dates_sidebar', 'option' ) ): the_row();
								$date = get_sub_field( 'date');
								$heading = get_sub_field( 'heading' );
								$content = get_sub_field( 'content' );
								?>
								<li class="list-group-item list-group-item-action d-flex flex-column align-items-start pl-4 bg-faded font-size-sm py-2">
									<?php if ( $date ): ?>
										<div class="font-weight-bold"><?php echo $date; ?></div>
									<?php endif; ?>
									<?php if ( $heading ): ?>
										<div class="font-italic"><?php echo $heading; ?></div>
									<?php endif; ?>
									<?php if ( $content ): ?>
										<div><?php echo $content; ?></div>
									<?php endif; ?>
								</li>
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

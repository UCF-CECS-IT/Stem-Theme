<?php
get_header();
$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

$args = array(
	'taxonomy' => 'people_cat',
	'orderby' => 'name',
	'order'   => 'ASC'
);

$cats = get_categories($args);

$navigationSidebar = count($cats ?? []);


?>

<section id="main_content">
	<div class="container mt-4 mt-sm-5 mb-5 pb-sm-4">
		<div class="row">
			<div class="col">
				<h1 class="heading-underline text-transform-none">
					People: <span class="text-muted"><?php echo $term->name; ?></span>
				</h1>

				<?php echo do_shortcode("[show_people group='". $term->slug."' hide-photos='yes']" ); ?>
			</div>

			<?php if ( $navigationSidebar ): ?>
				<div class="col-md-3">
					<div class="list-group mb-4">
						<a class="list-group-item font-weight-bold text-primary bg-inverse-t-3" href="#">People</a>
						<?php foreach( $cats as $cat ): ?>
							<a class="list-group-item list-group-item-action pl-4 bg-faded font-size-sm py-2" href="<?php echo get_bloginfo('url') . "/group/{$cat->slug}/"; ?>"><?php echo $cat->name; ?></a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>

	</div> <!-- End Wrap -->
</section>

<?php get_footer(); ?>

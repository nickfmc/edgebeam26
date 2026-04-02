<?php
/**
 * Template Part: Related Resources
 * 
 * Displays related posts from the same category with gradient background.
 */

$related_posts = gdt_get_related_posts( get_the_ID(), 2 );

if ( empty( $related_posts ) ) {
	return;
}

$post_category = get_the_category( get_the_ID() );
$section_title = ! empty( $post_category ) ? $post_category[0]->name : 'Related Resources';
?>
 
<section class="c-related-resources">
	<div class="c-related-resources__content">
		<!-- Header -->
		<div class="c-related-resources__header">
			<h2 class="is-style-eyebrow c-related-resources__eyebrow">Related Resources</h2>
			<h3 class="h4-style c-related-resources__title">Rethink Last-Mile Connectivity With Expert Insights From EdgeBeam's Resource Center</h3>
			<a href="/resources/" class="c-btn--secondary">
				<span>View All</span>
			</a>
		</div>

		<!-- Posts Grid -->
		<div class="c-related-resources__grid">
			<?php foreach ( $related_posts as $post ) : ?>
				<?php
				$post_id = $post->ID;
				$featured_img = get_the_post_thumbnail_url( $post_id, 'medium_large' );
				$post_categories = get_the_category( $post_id );
				$post_category_color = '';
				
				if ( ! empty( $post_categories ) ) {
					$post_category_color = get_field( 'category_color', 'category_' . $post_categories[0]->term_id );
				}
				?>
				<div class="c-related-resources__card u-stretch-link">
					<div class="c-related-resources__card-image-wrapper">
						<?php if ( $featured_img ) : ?>
							<img src="<?php echo esc_url( $featured_img ); ?>" alt="<?php echo esc_attr( $post->post_title ); ?>" class="c-related-resources__card-image" />
						<?php else : ?>
							<div class="c-related-resources__card-image-placeholder"></div>
						<?php endif; ?>
						
						<!-- Category Badge -->
						<?php if ( ! empty( $post_categories ) ) : ?>
							<div class="c-related-resources__badge-wrapper">
								<?php echo gdt_get_first_category_icon( $post_id ); ?>
							</div>
						<?php endif; ?>
					</div>

					<!-- Card Body -->
					<div class="c-related-resources__card-body">
						<h3 class="c-related-resources__card-title is-style-h6-style">
							<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="c-related-resources__card-link">
								<?php echo esc_html( $post->post_title ); ?>
							</a>
						</h3>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

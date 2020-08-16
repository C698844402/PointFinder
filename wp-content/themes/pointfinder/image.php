<?php
/**
 * The template for displaying image attachments
 */

get_header(); echo '<div class="pf-blogpage-spacing pfb-top"></div>';?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
				while ( have_posts() ) : the_post();
			?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="entry-content" style="text-align: center;">

						<div class="entry-attachment">
							<?php
								echo wp_get_attachment_image( get_the_ID(), 'full');
							?>

						</div><!-- .entry-attachment -->

						<?php
							the_content();
							
						?>
					</div><!-- .entry-content -->

					
				</article><!-- #post-## -->

				<?php
					
				endwhile;
			?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php echo '<div class="pf-blogpage-spacing pfb-bottom"></div>';get_footer(); ?>

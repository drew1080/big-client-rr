<?php 
	get_header('blog'); 
?>
	
	
	<!-- section -->
	<section role="blog">
		<div style="max-width:1080px;margin:0 auto;">
   			<div class="all-posts" style="width:730px;float:left;">
				<?php if (have_posts()): while (have_posts()) : the_post(); ?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>">
						<div class="date" style="float:left;">
							<span class="day" ><?php the_time('d'); ?></span>
							<span class="month"><?php the_time('M'); ?></span>
							<span class="year" ><?php the_time('Y'); ?></span>
						</div>
						<div class="post-content" style="float:left;width:450px;">
				          	<h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
							<span class="author"><?php _e( 'by', 'html5blank' ); ?> <?php the_author_posts_link(); ?></span>
				            <p>
				            	<?php the_content(); ?>
				            </p>
				            <?php the_tags( __( 'Tags: ', 'html5blank' ), ', ', '<br>'); // Separated by commas with a line break at the end ?>
						
							<p><?php _e( 'Categorised in: ', 'html5blank' ); the_category(', '); // Separated by commas ?></p>
							
							<p><?php _e( 'This post was written by ', 'html5blank' ); the_author(); ?></p>
							
							<?php edit_post_link(); // Always handy to have Edit Post Links available ?>
							
							<?php comments_template(); ?>
				        </div>
				    </article>
    
				<?php endwhile; ?>
				
				<?php else: ?>
				
					<!-- article -->
					<article>
						
						<h1><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h1>
						
					</article>
					<!-- /article -->
				
				<?php endif; ?>
			</div>
			<div class="posts-sidebar" style="float:left;width:300px;">
			   <?php get_sidebar(); ?>
			</div>
		</div>
	</section>
	<!-- /section -->
	<div class="clear" style="clear:both;"></div>

<?php get_footer(); ?>
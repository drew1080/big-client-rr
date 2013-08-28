<?php
/*
 * Template Name: All posts
 */
get_header();
 ?>
		
	<!-- section -->
	<section role="main">
   	<div style="max-width:1080px;margin:0 auto;">
   		<div class="all-posts" style="width:730px;float:left;">
	   		<?php $wp_query = new WP_Query('post_type=post&posts_per_page=5&paged='.$paged ); ?>
				<?php while ($wp_query->have_posts()) :$wp_query->the_post(); ?>
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
		                <a href="<?php the_permalink() ?>" class="view-article">read more</a>
	                </div>
	            </article>
	            <div class="post-separator" style="background:#e6e8ea;width:100%;height:1px;margin:25px 0;"></div>
	         <?php endwhile; ?>
	         
	         <nav class="oldernewer">
	            <div class="older">
						<?php next_posts_link('&laquo; Older Entries') ?>
	            </div><!--.older-->
	            <div class="newer">
						<?php previous_posts_link('Newer Entries &raquo;') ?>
	            </div><!--.newer-->
	         </nav><!--.oldernewer-->	
   		</div>
		<div class="posts-sidebar" style="float:left;width:300px;">
		   <?php // get_sidebar(); ?>
		   <img src="http://200.110.156.224/richrelevance/sidebar.png" />
		</div>
      	
         
		
	</div>      
   </section>
   <div class="clear" style="clear:both;"></div>
	<!-- /section -->
	
<?php get_footer(); ?>
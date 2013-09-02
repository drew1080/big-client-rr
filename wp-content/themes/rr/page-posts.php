<?php
/* * Template Name: All Blog Posts */
get_header(); 
?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
   		<?php the_content(); ?>
<?php endwhile ?>

<section role="blog">
	<div class="wrap">
		<div class="all-posts">	   		
		  <?php $wp_query = new WP_Query('post_type=post&posts_per_page=5&paged='.$paged ); ?>	
			<?php get_template_part('loop_blog'); ?>   		
		</div>		
		<div class="posts-sidebar">		   
			<?php get_sidebar(); ?>		
		</div>      	         			
	</div>         
</section>   
<div class="clear" style="clear:both;"></div>
<?php get_footer(); ?>
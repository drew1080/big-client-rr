<?php get_header(); ?>

<section role="blog">
	<div class="wrap archive">
		<div class="all-posts">
		  <?php if (have_posts()): the_post(); ?>
    		<h1><?php _e( 'Author Archives for ', 'html5blank' ); echo get_the_author(); ?></h1>
    	<?php endif; ?>
      <?php get_template_part('loop_blog'); ?>
		</div>		
		<div class="posts-sidebar">		   
			<?php get_sidebar(); ?>		
		</div>      	         			
	</div>         
</section>   
<div class="clear" style="clear:both;"></div>
<?php get_footer(); ?>
<?php get_header(); ?>

<section role="blog">
	<div class="wrap search">
		<div class="all-posts">
      <h2>Display search results for: <?php echo get_search_query(); ?></h2>
      <?php get_template_part('loop_blog'); ?>
		</div>		
		<div class="posts-sidebar">		   
			<?php get_sidebar(); ?>		
		</div>      	         			
	</div>         
</section>   
<div class="clear" style="clear:both;"></div>
<?php get_footer(); ?>
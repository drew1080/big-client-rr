<?php get_header(); ?>

<section role="blog">
	<div class="wrap category">
		<div class="all-posts">
      <?php get_template_part('loop_blog'); ?>
		</div>		
		<div class="posts-sidebar">		   
			<?php get_sidebar(); ?>		
		</div>      	         			
	</div>         
</section>   
<div class="clear" style="clear:both;"></div>
<?php get_footer(); ?>



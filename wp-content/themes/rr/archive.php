<?php 
	get_header('blog'); 
?>
	
	<!-- section -->
	<section role="blog">
	
		<div style="max-width:1080px;margin:0 auto;">
			<h1 style="margin-left:50px;margin-bottom:40px;"><?php _e( 'Archives', 'html5blank' ); ?></h1>
	   		
	   		<div class="all-posts" style="width:730px;float:left;">
		   		<?php get_template_part('loop_blog'); ?>
	   		</div>
			<div class="posts-sidebar" style="float:left;width:300px;">
			   <?php get_sidebar(); ?>
			</div>
	      	
	         
			
		</div>      
   </section>
   <div class="clear" style="clear:both;"></div>

<?php get_footer(); ?>
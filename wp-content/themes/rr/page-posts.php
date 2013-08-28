<?php
/* * Template Name: All posts */
get_header(); 
?>   
<section role="blog">   	
	<div style="max-width:1080px;margin:0 auto;">   		
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
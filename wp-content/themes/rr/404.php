<?php
get_header(); 
?>

<section role="blog">
	<div class="wrap">
		<div class="all-posts">	   		
			<h1><?php _e( 'Page not found', 'html5blank' ); ?></h1>
			<h2>
				<a href="<?php echo home_url(); ?>"><?php _e( 'Return home?', 'html5blank' ); ?></a>
			</h2>
		</div>		
		<div class="posts-sidebar">		   
			<?php get_sidebar(); ?>		
		</div>      	         			
	</div>         
</section>   
<div class="clear" style="clear:both;"></div>
<?php get_footer(); ?>
<?php
/* * Template Name: Internal */  
 get_header(); 
?>
		
	<!-- section -->

	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
     		<?php the_content(); ?>
	<?php endwhile ?>  
	
	<!-- /section -->
	
<?php// get_sidebar(); ?>

<?php get_footer(); ?>
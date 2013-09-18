<?php 
/*
 * Template Name: Home Page Template
 */
get_header(); 
?>
	<!-- section -->
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	  <?php the_content(); ?>
	<?php endwhile; else: ?>
	<?php endif; ?>
<?php get_footer(); ?>

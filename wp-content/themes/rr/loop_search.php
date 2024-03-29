<div class="wrap">

	<h3><?php echo sprintf( __( '%s Search Results for ', 'html5blank' ), $wp_query->found_posts ); echo get_search_query(); ?></h3>
<?php //$wp_query2 = new WP_Query('post_type=post'); ?>	
<?php //if (have_posts()): while (have_posts()) : $wp_query->the_post(); ?><?php while ($wp_query->have_posts()) :$wp_query->the_post(); ?>
	
	<!-- article -->
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<div class="date">

			<span class="day"><?php the_time('d'); ?></span>
			<span class="month"><?php the_time('M'); ?></span>
			<span class="year"><?php the_time('Y'); ?></span>

		</div>

		<div class="content">
	
		<!-- post thumbnail -->
		<?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				<?php the_post_thumbnail(array(120,120)); // Declare pixel size you need inside the array ?>
			</a>
		<?php endif; ?>
		<!-- /post thumbnail -->
		
		<!-- post title -->
		<h2>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		</h2>
		<!-- /post title -->
		
		<!-- post details -->
		<span class="author"><?php _e( 'by', 'html5blank' ); ?> <?php the_author_posts_link(); ?></span>
		<!--span class="comments"><?php comments_popup_link( __( 'Leave your thoughts', 'html5blank' ), __( '1 Comment', 'html5blank' ), __( '% Comments', 'html5blank' )); ?></span-->
		<!-- /post details -->
		
		<?php html5wp_excerpt('html5wp_index'); // Build your custom callback length in functions.php ?>
		
		<?php //edit_post_link(); ?>

		</div>
		
	</article>
	<!-- /article -->
	
<?php 
	//break; // just one post 	 
	endwhile; 
?>

<?php //else: ?>

	<!-- article 
	<article>
		<h2><?php //_e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
	</article>
	<!-- /article -->

<?php //endif; ?>

</div>
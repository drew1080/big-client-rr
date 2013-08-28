<div id="blog_carousel" class="jcarousel-skin-tango">
	<ul>
<?php 
	$wp_query = new WP_Query('post_type=post&posts_per_page=5&paged='.$paged ); 
	while ($wp_query->have_posts()) :$wp_query->the_post(); 
?>	
		<li>
			<article id="post-<?php the_ID(); ?>" class="<?php post_class(); ?>">		
				<div class="date" style="float:left;">
					<span class="day" ><?php the_time('d'); ?></span>			
					<span class="month"><?php the_time('M'); ?></span>			
					<span class="year" ><?php the_time('Y'); ?></span>		
				</div>		
				<div class="post-content" style="float:left;width:450px;">          	
					<h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
					<span class="author"><?php _e( 'by', 'html5blank' ); ?> <?php the_author_posts_link(); ?></span>            
					<p>            	<?php the_content(); ?>            </p>            
					<a href="<?php the_permalink() ?>" class="view-article">read more</a>        
				</div>    
			</article>    
		</li>
<?php endwhile; ?>  
	</ul>
 <div class="jcarousel-control" style="top:240px">
    	<?php
			for($i = 1; $i <= 5; $i++)
				echo '<a href="#">' . $i . '</a>'; 
		?>
	</div>
 </div>
<?php 
if (!is_single()) {
  $wp_query = new WP_Query('post_type=post&posts_per_page=5&paged='.$paged );
}
?>
<?php while ($wp_query->have_posts()) :$wp_query->the_post(); ?>	
  <article id="post-<?php the_ID(); ?>" "<?php post_class(); ?>">		
    <div class="date" style="float:left;">			
      <span class="day" ><?php the_time('d'); ?></span>			
      <span class="month"><?php the_time('M'); ?></span>			
      <span class="year" ><?php the_time('Y'); ?></span>		
    </div>	
    <div class="post-content">          	
      <h2>
        <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="bookmark">
          <?php the_title(); ?>
        </a>
      </h2>			
      <span class="author"><?php _e( 'by', 'html5blank' ); ?> <?php the_author_posts_link(); ?></span>            
      <p><?php the_content(); ?></p>            
      <p class="button"><a href="<?php the_permalink() ?>">Read More</a></p>        
    </div>    
  </article>   
  <div class="post-separator"></div>
<?php endwhile; ?>
<section role="circles" class="white ">
  <div class="wrap">
    <nav class="oldernewer">  
      <div class="newer">	
      <?php previous_posts_link('<img src="/wp-content/uploads/2013/09/nav-newer-entries.png">') ?>  
      </div><!--.newer-->
      <div class="older">		
      <?php next_posts_link('<img src="/wp-content/uploads/2013/09/nav-older-entries.png">') ?>   
      </div>
      <!--.older-->   
    </nav><!--.oldernewer-->
  </div>
</section>
</section>

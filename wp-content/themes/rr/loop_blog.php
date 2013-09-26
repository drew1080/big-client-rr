<?php 
if (!is_single() && !is_search() && !is_category() && !is_archive()) {
  $wp_query = new WP_Query('post_type=post&posts_per_page=5&paged='.$paged);
} else {
  wp_reset_query();
}
?>
<?php while ($wp_query->have_posts()) :$wp_query->the_post(); ?>	
  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>		
    <div class="date" style="float:left;">		
      <?php if (is_search() && get_post_type(get_the_ID()) != 'post') { ?>
        <span class="day">rr</span>
      <?php } else { ?>
        <span class="day" ><?php the_time('d'); ?></span>
        <span class="month"><?php the_time('M'); ?></span>	
        <span class="year" ><?php the_time('Y'); ?></span>
      <?php } ?>	
    </div>	
    <div class="post-content">          	
      <h2>
        <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="bookmark">
          <?php the_title(); ?>
        </a>
      </h2>			
      <span class="author"><?php _e( 'by', 'html5blank' ); ?> <?php the_author_posts_link(); ?></span>
      <?php if ( is_single() ) { ?>
        <p><?php the_content(); ?></p>
        <?php comments_template(); ?>  
        <?php do_action('oa_social_login'); ?>
      <?php } else { ?> 
        <p><?php the_excerpt(); ?></p>
        <p class="button"><a href="<?php the_permalink() ?>">Read More</a></p>
      <?php } ?>
    </div>    
  </article>   
  <div class="post-separator"></div>
<?php endwhile; ?>
<section role="circles" class="white ">
  <div class="wrap">
    <nav class="oldernewer">  
      <? if ( is_search() ) { ?>
      <div class="newer">	
      <?php previous_posts_link('<img src="' . get_template_directory_uri() . '/img/nav-previous_results.png">') ?>  
      </div><!--.newer-->
      <div class="older">		
      <?php next_posts_link('<img src="' . get_template_directory_uri() . '/img/nav-more_results.png">') ?>   
      </div>
      <? } else { ?>
      <div class="newer">	
      <?php previous_posts_link('<img src="' . get_template_directory_uri() . '/img/nav-newer-entries.png">') ?>
      </div><!--.newer-->
      <div class="older">		
      <?php next_posts_link('<img src="' . get_template_directory_uri() . '/img/nav-older-entries.png">') ?>  
      </div>
      <? } ?> 
      <!--.older-->   
    </nav><!--.oldernewer-->
  </div>
</section>
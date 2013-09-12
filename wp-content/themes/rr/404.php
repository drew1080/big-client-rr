<?php
get_header(); 
?>

<section role="blog">
	<div class="wrap">
		<div class="page-404">	   		
			<?php
			$page_id = get_ID_by_slug('404-2');
      $page_404 = get_post($page_id);
      $content = $page_404->post_content;
      echo apply_filters('the_content', $content);
      ?>
		</div>	         			
	</div>         
</section>   
<div class="clear" style="clear:both;"></div>
<?php get_footer(); ?>
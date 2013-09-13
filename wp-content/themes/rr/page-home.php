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
	<section role="trusted">
		<div class="wrap">
			<h3>Trusted By</h3>
				<?php
					
					$logos = get_trusted_by_logos();
					//print_r($logos);
					
					$content = '';
					$lis = '';
					$count = 0;
					$qty_bullets = 0;
					foreach ($logos as $key => $item ) {
						if (!empty($item)) {
							// if ($count >= 1) {
							//                 
							//                 $count = 0;
							//                 $qty_bullets++;
							//               }
							$lis .= '<li><img src="' . $item["coll_image"]  . '" /></li>';
							
							$count++;
						}
					}
					
					$content .= '<ul>' . $lis . '</ul>';
					//$qty_bullets++;
				?>
           <div id="mycarousel" class="jcarousel-skin-tango">
				    <div class="jcarousel-control">
				    	<?php
				    	  // $bullets = $count - 6;
				    	  //                 
				    	  //                 if ( $bullets > 2) {
				    	  //                   $bullets = 2;
				    	  //                 }
				    	  //                   
				    	  //                 for($i = 1; $i <= $bullets; $i++) {
				    	  //                   echo '<a href="#">' . $i . '</a>'; 
				    	  //                 }
				      	?>
				      	<a href="#" id="mycarousel-prev">Prev</a>
				      	<a href="#" id="mycarousel-next">Next</a>
				    </div>
				      <?= $content; ?>
			 	 </div>
		</div>
	</section>
  
<!-- jCarousel library -->
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.jcarousel.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/skins/tango/skin.css" />
<?php get_footer(); ?>

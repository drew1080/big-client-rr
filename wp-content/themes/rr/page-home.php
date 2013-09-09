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
	
				<!-- jCarousel library -->
		        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.jcarousel.min.js"></script>
		        <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/skins/tango/skin.css" />
				<style type="text/css">
                         /* Additional styles for the controls. */						 
                        
                        .jcarousel-scroll {
                            margin-top: 10px;
                            text-align: center;
                        }
                        
                        .jcarousel-scroll form {
                            margin: 0;
                            padding: 0;
                        }
                        
                        .jcarousel-scroll select {
                            font-size: 75%;
                        }
                        
                        #mycarousel-next,
                        #mycarousel-prev {
                            cursor: pointer;
                            margin-bottom: -10px;
                            text-decoration: underline;
                            font-size: 11px;
                        }
                
                </style>	

		        <script type="text/javascript">
					function mycarousel_initCallback(carousel) {
						jQuery('.jcarousel-control a').bind('click', function() {
							carousel.scroll(jQuery.jcarousel.intval(jQuery(this).text()));
							return false;
						});
					}
					jQuery(document).ready(function() {
						jQuery("#mycarousel").jcarousel({
							scroll: 1,
							initCallback: mycarousel_initCallback,
							buttonNextHTML: null,
							buttonPrevHTML: null
						});
					});
					
					jQuery(document).ready(function() {
						jQuery("#blog_carousel").jcarousel({
							scroll: 1,
							visible: 1,
							initCallback: mycarousel_initCallback,
							buttonNextHTML: null,
							buttonPrevHTML: null
						});
					});
				</script>
				<?php
					
					$logos = get_trusted_by_logos();
					//print_r($logos);
					
					
					$content = '';
					$lis = '';
					$count = 0;
					$qty_bullets = 0;
					foreach ($logos as $logo) {
						if (!empty($logo)) {
							if ($count >= 6) {
								
								$count = 0;
								$qty_bullets++;
							}
							$lis .= '<li><img src="' . $logo  . '" /></li>';
							
							$count++;
						}
					}
					
					$content .= '<ul>' . $lis . '</ul>';
					$qty_bullets++;
				?>
                <div id="mycarousel" class="jcarousel-skin-tango">
				    <div class="jcarousel-control">
				    	<?php
				    		for($i = 1; $i <= $qty_bullets; $i++)
								echo '<a href="#">' . $i . '</a>'; 
				      	?>
				    </div>
			
				      <?= $content; ?>
			
			 	 </div>
			
				


		</div>



	</section>
	<!--
	<section role="main">
		<?php //get_template_part('loop_home'); ?>
	</section>
	-->
	<!-- /section -->

<?php get_footer(); ?>
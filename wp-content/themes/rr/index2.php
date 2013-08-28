<?php get_header(); ?>



		



	<!-- section -->

	<section role="about">



		<h3>Who We Are</h3>



		<p>Ut audi doluptatet in conestia ditas at. Iti tet quiae hocorerchicti acearum harchic tecatur estempo rporeptate atur? Din culparum que verorro et aut labor mosae dolorento est, omnimus solor aciis maionseque natures laccatquiam quatemq venitest.</p>



	</section>



	<section role="trusted">



		<div class="wrap">



			<h3>Trusted By</h3>

			

				<?php
					/*
					$logos = get_trusted_by_logos();
					$text = '';
					$vuelta = 0;
					echo count($logos);
					foreach ( $logos as $logo) {
						$vuelta++;
						if ($vuelta < count($logos)){							
							echo $vuelta;
							//echo "<br />";
							$text .= '<img src="' . $logo  . '" />/!';
							//echo "<br />";
							//echo $text;
						}
						if ($vuelta == count($logos)){
							$text .= '<img src="' . $logo  . '" />';
						}
					}
					
					 */
				?>
                
                <!--[wpic color="blue" visible="6" width="139" height="77" speed="1000" auto="1000" ][/wpic]-->
                
                
                <?php
				/*$text = '<img src="'.get_template_directory_uri().'/img/mark/bestbuy.png" />/!<img src="'.get_template_directory_uri().'/img/mark/officedepot.png" />/!<img src="'.get_template_directory_uri().'/img/mark/overstock.png" />/!<img src="'.get_template_directory_uri().'/img/mark/patagonia.png" />/!<img src="'.get_template_directory_uri().'/img/mark/target.png" />/!<img src="'.get_template_directory_uri().'img/mark/walmart.png" />/!';*/
				//echo do_shortcode('[wpic color="blue" visible="3" width="139" height="77" speed="1000" auto="1000"]'.$text.'[/wpic]');
				?>
				
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
							scroll: 2,
							initCallback: mycarousel_initCallback,
							buttonNextHTML: null,
							buttonPrevHTML: null
						});
					});
				</script>
				<?php
					
					$logos = get_trusted_by_logos();
					$content = '';
					$qty_bullets = ceil(count($logos) / 2);
					foreach ( $logos as $logo) {
						if (!empty($logo))
							$content .= '<li><img src="' . $logo  . '" /></li>';
					}
				?>
                <div id="mycarousel" class="jcarousel-skin-tango">
				    <div class="jcarousel-control">
				    	<?php
				    		for($i = 1; $i <= $qty_bullets; $i++)
								echo '<a href="#">' . $i . '</a>'; 
				      	?>
				    </div>
			
				    <ul>
				      <?= $content; ?>
				    </ul>
			
			 	 </div>
			



		</div>



	</section>

			

	<section role="main">



	



		<?php get_template_part('loop'); ?>



		



		<?php //get_template_part('pagination'); ?>



	



	</section>



	<!-- /section -->



	



<?php// get_sidebar(); ?>







<?php get_footer(); ?>
<?php get_header(); ?>



		



	<!-- section -->

	<section role="about">



		<h3>Who We Are</h3>



		<p>Ut audi doluptatet in conestia ditas at. Iti tet quiae hocorerchicti acearum harchic tecatur estempo rporeptate atur? Din culparum que verorro et aut labor mosae dolorento est, omnimus solor aciis maionseque natures laccatquiam quatemq venitest.</p>



	</section>



	<section role="trusted">



		<div class="wrap">



			<h3>Trusted By</h3>

			<ul>	

				<?php

					$logos = get_trusted_by_logos();
					$count = 0;
					$text = '';
					foreach ( $logos as $logo) {
						if ($count != 0)
							$text .= '/!';
						$text .= '<img src="' . $logo  . '" />';
					}
					
					echo do_shortcode('[wpic color="blue" visible="6" width="139" height="77" speed="1000" auto="1000"]'.$text.'[/wpic]');
				?>
                
                

			</ul>



		</div>



	</section>

			

	<section role="main">



	



		<?php get_template_part('loop'); ?>



		



		<?php //get_template_part('pagination'); ?>



	



	</section>



	<!-- /section -->



	



<?php// get_sidebar(); ?>







<?php get_footer(); ?>
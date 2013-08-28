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
					foreach ( $logos as $logo) {
						echo '<li><img src="' . $logo  . '" /></li>';
					}
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
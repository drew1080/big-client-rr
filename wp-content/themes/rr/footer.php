			<!-- footer -->
			<footer class="footer" role="contentinfo">

				<div class="wrap">
				
					<ul class="social">

						<li class="fb"><a href="http://www.facebook.com/richrelevance" target="_blank" title="Facebook">Facebook</a></li>
						<li class="gplus"><a href="http://plus.google.com/100318823509149944458/posts" target="_blank"        title="Google+">Google+</a></li>
						<li class="tw"><a href="http://www.twitter.com/richrelevance" target="_blank" title="Twitter">Twitter</a></li>
						<li class="li"><a href="http://www.linkedin.com/company/richrelevance" target="_blank" title="Linkedin">Linkedin</a></li>
						<li class="yt"><a href="http://www.youtube.com/user/richrelevance" target="_blank" title="Youtube">Youtube</a></li>

					</ul>

				  <!-- <div class="newsletter">
				            Sign up for our newsletter
				          </div> -->

					<div class="sections">
						<?php wp_nav_menu( array('menu'=>'FooterNav') ); ?>
						
					</div>

					<div class="clear"></div>

					<div class="icons">
              
						  <div class="search-form">Search
                <?php get_search_form(); ?>
              </div>

						<a href="<?php echo get_permalink( get_ID_by_slug( 'company/contact-us')); ?>" title="Chat" class="chat">Chat</a>

						<ul class="langs">
							<li class="padre"><a href="#" title="USA">USA</a>
								<ul>
									<li><a href="#" title="USA">USA</a></li>
									<li><a href="#" title="UK">UK</a></li>
									<li><a href="#" title="France">France</a></li>
									<li><a href="#" title="Deutschland">Deutschland</a></li>
								</ul>
							</li>
						</ul>

					</div>

					<div class="copy">

						<p><a href="<?php echo site_url(); ?>/privacy/" title="Privacy">Privacy (Updated July 24, 2012)</a></p>
						<p><a href="<?php echo site_url(); ?>/privacy/opt-out/" title="Opt-out">Retargeting Opt-out</a></p>
						<p>Copyright © 2007-2013 RichRelevance, Inc. <span>All Rights Reserved.</span></p>

					</div>

				</div>
				
			</footer>
			<!-- /footer -->
		
		</div>
		<!-- /wrapper -->

		<?php wp_footer(); ?>
		
		<!-- analytics -->
		<script>
			var _gaq=[['_setAccount','UA-XXXXXXXX-XX'],['_trackPageview']];
			(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
			g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
			s.parentNode.insertBefore(g,s)})(document,'script');
		</script>
	  <script type="text/javascript" src="<?php echo plugins_url(); ?>/fancybox-for-wordpress/fancybox/helpers/jquery.fancybox-media.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.isotope.min.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/response.min.js"></script>
    
	</body>
</html>
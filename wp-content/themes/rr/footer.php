			<!-- footer -->
			<footer class="footer" role="contentinfo">

				<div class="wrap">
				
					<ul class="social">

						<li class="fb"><a href="#" title="Facebook">Facebook</a></li>
						<li class="gplus"><a href="#" title="Google+">Google+</a></li>
						<li class="tw"><a href="#" title="Twitter">Twitter</a></li>
						<li class="li"><a href="#" title="Linkedin">Linkedin</a></li>
						<li class="yt"><a href="#" title="Youtube">Youtube</a></li>

					</ul>

					<div class="newsletter">
						Sign up for our newsletter
					</div>

					<div class="sections">
						<?php wp_nav_menu( array('menu'=>'FooterNav') ); ?>
						
					</div>

					<div class="clear"></div>

					<div class="icons">

						<div class="search">Search
							<form action="" method="">
								<input type="text" id="search" name="search" />
							</form>
						</div>

						<a href="#" title="Chat" class="chat">Chat</a>

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

						<p>Privacy (Updated July 24, 2012)<br />Retargeting Opt-out<br />Copyright © 2007-2013 RichRelevance, Inc. <span>All Rights Reserved.</span></p>

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
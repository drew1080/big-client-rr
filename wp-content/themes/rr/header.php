<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>

		<!-- dns prefetch -->
		<link href="//www.google-analytics.com" rel="dns-prefetch">

		<!-- meta -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<!-- icons -->
		<link href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico" rel="shortcut icon">
		<link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">

		<!-- css + javascript -->		
		<?php wp_head(); ?>		
		<!--script type='text/javascript' src='http://easingslider.com/wordpress/wp-includes/js/jquery/jquery.js?ver=1.8.3'></script-->
		<script>

		!function(){
			// configure legacy, retina, touch requirements @ conditionizr.com
			conditionizr()
		}()
		</script>				<!-- JS ADD -->				
		<script type="text/javascript">
			jQuery(document).ready(function(){
					//alert(jQuery('body').width());
					jQuery(".desplegar-menu").click(function(){		
									$(".nav > ul > li > .sub-menu").removeClass("visible-sm");	
  $(".nav > ul > li > .sub-menu").addClass("oculto-sm");						 jQuery("nav").toggle();
														 });			
					
					
					if($('body').width() <= 768){
						//alert($('#menu-item-6 > a').text());
  $(".nav > ul > li > .sub-menu").removeClass("visible-sm");	
  $(".nav > ul > li > .sub-menu").addClass("oculto-sm");

	$('#menu-item-6 > a').click(function(e){	

		if($(".nav > ul > li > .sub-menu").hasClass("oculto-sm")){
e.preventDefault();
//alert("entre 1");
$(".nav > ul > li > .sub-menu").removeClass("oculto-sm");	   
$(".nav > ul > li > .sub-menu").addClass("visible-sm");	
}
else if($(".nav > ul > li > .sub-menu").hasClass("visible-sm")){
//alert("entre 2");
$(".nav > ul > li > .sub-menu").removeClass("visible-sm");

}
})



					
					}
							
														
					});
			
			
			
			
        </script>	


        
        
	</head>



	<body <?php body_class(); ?>>
		<!-- wrapper -->

		<div class="wrapper">

			<!-- header -->

			<header class="header clear" role="banner">
					<!-- logo -->
					<div class="logo">



						<a href="<?php echo home_url(); ?>" class="a-logo">



							<!-- svg logo - toddmotto.com/mastering-svg-use-for-a-retina-web-fallbacks-with-png-script -->



							<img src="<?php echo get_template_directory_uri(); ?>/img/logo_new.png" alt="Rich Relevance" class="logo-img">



						</a>



					</div>



					<!-- /logo -->







					<div class="icons">







						<div class="search-form">Search
              <?php get_search_form(); ?>
						</div>







						<a href="#" title="Chat" class="chat">Chat</a>







						<!--ul class="langs">



							<li class="padre"><a href="#" title="USA">USA</a>



								<ul>



									<li><a href="#" title="USA">USA</a></li>



									<li><a href="#" title="UK">UK</a></li>



									<li><a href="#" title="France">France</a></li>



									<li><a href="#" title="Deutschland">Deutschland</a></li>



								</ul>



							</li>



						</ul-->







					</div>					<div class="desplegar-menu"></div>



					



					<!-- nav -->



					<nav class="nav" role="navigation">



						<?php html5blank_nav(); ?>



					</nav>



					<!-- /nav -->



			



			</header>



			<!-- /header -->



			<!-- SubNav -->



			<?php



                if ($post->post_parent == 0) {



                	$children = wp_list_pages("title_li=&child_of=".$post->ID."&echo=0");



                	$parentpage = $wpdb->get_row("SELECT ID, post_title, post_name FROM $wpdb->posts WHERE ID = '".$post->ID."'");



                } else if ($post->post_parent != 0) {                	



                	$next_post_parent = $post->post_parent;



                	while ($next_post_parent != 0) {



                		$children = wp_list_pages("title_li=&child_of=".$next_post_parent."&echo=0");



                		$parentpage = $wpdb->get_row("SELECT ID, post_title, post_parent, post_name FROM $wpdb->posts WHERE ID = '".$next_post_parent."'");



                		$next_post_parent = $parentpage->post_parent;



                	}



            	}



			?>



			<?php //if(!empty($children)) : ?>



			<!--
<div class="subnav">



				<div class="subnav-wrapper">



					<?php echo $children; ?>



				</div>



			</div>
-->



			<?php //endif;?>



			<!-- /SubNav -->



			<!-- Slider -->



			<?php if( is_single() || is_archive() || is_page( 'rr-blog' ) || is_search()) { 
			  $blog_page_id = get_ID_by_slug('blog/rr-blog');
        $page_rr_blog = get_page($blog_page_id);
        $content = $page_rr_blog->post_content;
        $content = apply_filters('the_content', $page_rr_blog->post_content); 
        
        if ( is_search() ) {
          // $title = get_search_query();
          $content = str_replace('Blog', 'Search', $content);
          echo $content;
        } else if ( is_category() ) {
          $current_category = single_cat_title("", false);
          $content = str_replace('Blog', $current_category, $content);
          echo $content;
        } else if ( is_archive() ) {
          $current_archive = wp_title('', false);
          $content = str_replace('Blog', $current_archive, $content);
          echo $content;
        } else {
          echo $content;
        }
        
			}?>
			
      <?php if ( !is_single() && !is_archive() && !is_search() && !get_post_meta($post->ID, 'banner', true) && function_exists('easingsliderpro') ) { 
          echo '<div class="slider-home">';
          easingsliderpro( 1 ); 
          echo '</div>';
      } ?>
      
			
			

			<!-- /Slider -->



			

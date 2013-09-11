<?php 

/*------------------------------------*\
	Shortcodes
\*------------------------------------*/

add_shortcode('section_divs', 'section_divs_func');
add_shortcode('columns', 'create_columns_func');
add_shortcode('newsroom', 'section_feed_func');


/*------------------------------------*\
	Methods Section Shortcode
\*------------------------------------*/

function section_divs_func($atts, $content = null) {
		
	extract( shortcode_atts( array( 'role' => 'main','class' => '','bg' => 'white','align' => ''), $atts ) );
	
	$html = '<section role="'.$atts['role'].'" class="' . esc_attr($bg) . ' ' . esc_attr($align) . '" ><div class="wrap" >'.do_shortcode($content).'</div><div class="' . esc_attr($class) . '"></div></section>';
	
	return $html; 
}

/*------------------------------------*\
	Methods Section Columns Shortcode
\*------------------------------------*/

function create_columns_func($atts, $content = null) {
		
	extract( shortcode_atts( array( 'count' => '1','class' => '',), $atts ) );
	
	if($count=="2"){
		$counter = "columns2";
	}
	else if($count=="3"){
		$counter = "columns3";
	}
	
	if($class=="last"){
		$last = '<div class="clear"></div>';
	}
	
	$html = '<div class="'.$counter.' '.esc_attr($class).'">'.$content.'</div>'.$last.'';
	
	return $html; 
}
/*------------------------------------*\
	Methods Section FEEDS Shortcode
\*------------------------------------*/

function section_feed_func($atts, $content = null) {
		
	extract( shortcode_atts( array( 'cat' => 'in-the-news','class' => '','bg' => 'white','align' => ''), $atts ) );
	
	$query = new WP_Query( 'category_name='. esc_attr($cat) . '' );
	
	/* $html = '<section role="'.$atts['role'].'" class="' . esc_attr($bg) . ' ' . esc_attr($align) . '" ><div class="wrap" >'.do_shortcode($content).'</div><div class="' . esc_attr($class) . '"></div></section>'; */

	// The Loop
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$lis .= '<li><strong>' . get_the_date('m/d/y') . '</strong><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
		}
	} else {
		// no posts found
	}
	/* Restore original Post Data */
	wp_reset_postdata();
	
	$html = '<ul class="newsroom">'.$lis.'</ul>';

	return $html; 
}


/*------------------------------------*\
	Customize TinyMce
\*------------------------------------*/

// Add custom font size and family to rich text editor (TinyMCE)

function customize_text_sizes($initArray){
   $initArray['theme_advanced_font_sizes'] = "12px,14px,16px,18px,20px,22px,24px,28px,40px,44px";
   $initArray['theme_advanced_fonts'] =
   		'Roboto Condensed=robotocondensed, sans-serif;'.
   		'Roboto Light=robotolight, sans-serif;'.
   		'Roboto Regular=robotoregular, sans-serif;'.
   		'Roboto Bold=robotobold, sans-serif;'.
   		'Oswald Bold=oswaldbold, sans-serif;'.
        'Helvetica=helvetica;'.
        '';
   $initArray['theme_advanced_text_colors'] = '990101,667280,F5F7FA,FFFFFF';   
   $initArray['theme_advanced_buttons2_add_before'] = 'styleselect';
   $initArray['theme_advanced_styles'] = 'Button=button,Image Services=imgservices,,Image Partner=imgpartners';
	
   return $initArray;
}
// Assigns customize_text_sizes() to "tiny_mce_before_init" filter
add_filter('tiny_mce_before_init', 'customize_text_sizes');
add_action( 'admin_init', 'add_my_editor_style' );

function add_my_editor_style() 
{
	add_editor_style();
}
/*
 *  Adds a filter to append the default stylesheet to the tinymce editor.
 */

/*
if ( ! function_exists('tdav_css') ) {
	function tdav_css($wp) {
		$wp .= ',' . get_bloginfo('stylesheet_url');
	return $wp;
	}
}
add_filter( 'mce_css', 'tdav_css' );
*/



/*------------------------------------*\
	Add columns to Posttypes.
\*------------------------------------*/
function bfm_columns_head($defaults) {
	
	$new_columns['cb'] = '<input type="checkbox" />';
	$new_columns['icon_image']  = 'Icon';
	$new_columns['title'] = 'Title';


	/* REMOVE DEFAULT CATEGORY COLUMN (OPTIONAL) */
	unset($defaults['categories']);

	return $new_columns;
}

// GENERAL PURPOSE  
function bfm_columns_content($column_name, $post_ID) {
	if ($column_name == 'icon_image') {
		
		switch(get_post_type($post_ID))
		{
			case 'technology_item':
				$bfm_icon_image = get_post_meta($post_ID,'tech_icon',TRUE);
			break;
			
			case 'capability_item':
				$bfm_icon_image = get_post_meta($post_ID,'capability_icon',TRUE);
			break;
			
			case 'core_values_item':
				$bfm_icon_image = get_post_meta($post_ID,'core_icon',TRUE);
			break;						
		}
		
		if ($bfm_icon_image) {
			// HAS A FEATURED IMAGE
			echo '<img src="' . $bfm_icon_image . '" />';
		}
		else {
			// NO FEATURED IMAGE, SHOW THE DEFAULT ONE
			echo 'No Image';
		}
	}
}

/*------------------------------------*\
	Hooks Columns
\*------------------------------------*/

// Technology
add_filter('manage_technology_item_posts_columns', 'bfm_columns_head');
add_filter('manage_technology_item_posts_custom_column', 'bfm_columns_content', 10, 2);

// Capability
add_filter('manage_capability_item_posts_columns', 'bfm_columns_head');
add_filter('manage_capability_item_posts_custom_column', 'bfm_columns_content', 10, 2);

// Core Value
add_filter('manage_core_values_item_posts_columns', 'bfm_columns_head');
add_filter('manage_core_values_item_posts_custom_column', 'bfm_columns_content', 10, 2);

// Styling for the custom post type icon


/*------------------------------------*\
	Post Types Icon Admin Head
\*------------------------------------*/

add_action('admin_head', 'posttypes_icons');

function posttypes_icons() {
    	global $post_type;
    ?>
    <style>
    <?php if (($_GET['post_type'] == 'technology_item') || ($post_type == 'technology_item')) : ?>
    	#icon-edit { background:transparent url('<?php echo get_bloginfo('template_directory').'/img/ico-admin-32-technology.png';?>') no-repeat; }     
    <?php endif; ?>
    <?php if (($_GET['post_type'] == 'capability_item') || ($post_type == 'capability_item')) : ?>
    	#icon-edit { background:transparent url('<?php echo get_bloginfo('template_directory').'/img/ico-admin-32-capability.png';?>') no-repeat; }     
    <?php endif; ?>
    
    <?php if (($_GET['post_type'] == 'core_values_item') || ($post_type == 'core_values_item')) : ?>
    	#icon-edit { background:transparent url('<?php echo get_bloginfo('template_directory').'/img/ico-admin-32-core.png';?>') no-repeat; }     
    <?php endif; ?>
    
     <?php if (($_GET['post_type'] == 'leadership_gallery') || ($post_type == 'leadership_gallery')) : ?>
    	#icon-edit { background:transparent url('<?php echo get_bloginfo('template_directory').'/img/ico-admin-32-leadership.png';?>') no-repeat; }     
    <?php endif; ?>
    
        </style>
    <?php
}


/*------------------------------------*\
	Customize TinyMce
\*------------------------------------*/

function custom_admin_css() {
	echo '<style>
		.column-icon_image { width: 50px; }
		.column-icon_image img{ width: 30px; }
	</style>';
 }
add_action('admin_head', 'custom_admin_css');


?>
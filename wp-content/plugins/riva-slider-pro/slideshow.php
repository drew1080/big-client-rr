<?php

/*
  This function contains the slideshow's physical output.
  It is crucial to the plugin and advisable that you do not edit it (unless you know what you're doing)
*/

function riva_slider_pro( $id = 1, $echo = true ) {
  
  global $rs_pro_loaded, $rs_pro_scripts;
  
  // Change the ID into an integer
  $id = intval( $id );

  // Get some plugin info  
  $info = riva_slider_pro_info();
  
  // Initiate HTML variable
  $html = null;
  
  // And the slideshow(s) and global settings
  $slideshows = get_option( $info[ 'shortname' ] .'_slideshows' );
  $settings = get_option( $info[ 'shortname' ] .'_global_settings' );
  
  // Firstly check to see if the slideshow exists
  $slideshow_exists = riva_slider_pro_search( $slideshows, 'index', $id, 'boolean' );
  
  if ( $slideshow_exists == true || $id == -1 ) {
    
    $slideshow = riva_slider_pro_search( $slideshows, 'index', $id, 'array' );
    
    // If user is using images from posts
    if ( $slideshow[ 'image-source' ] == 'images_from_posts' ) {
      
      $i = 0;
      
      $slideshow[ 'images' ] = array();
      $image_category = explode( ', ', $slideshow[ 'image-category' ] );
      
      if ( $image_category == '0' ) {
		$query = array(
		  'post_type' => 'post'
		);
	      } else {
		$query = array(
		  'post_type' => 'post',
		  'cat' => $image_category[ 0 ]
		);
      }
      
      $posts = new WP_Query( $query );
      
      while ( $posts -> have_posts() ) : $posts -> the_post();
		global $post;
		$image = get_post_meta( $post -> ID, '_rivasliderpro', true );
		$i++;
		if ( $i <= $slideshow[ 'max-images' ] ) {
		  if ( isset( $image[ 'get_post_info' ] ) && $image[ 'get_post_info' ] == 'true' ) {
		    $image[ 'content-title' ] = get_the_title();
		    $image[ 'content-text' ] = get_the_excerpt();
		    $image[ 'image-link' ] = 'webpage';
		    $image[ 'webpage-url' ] = get_permalink();
		  }
		  if ( $image[ 'image-url' ] != '' )
		    $slideshow[ 'images' ] = array_merge( $slideshow[ 'images' ], array( $image ) );
		}
      endwhile;
      wp_reset_query();
    }
  
    // Checking if user wants images to be randomized
    if ( isset( $slideshow ) && $slideshow[ 'random_order' ] == 'true' )
      shuffle( $slideshow[ 'images' ] );
  
    // Display the slideshow
    if ( count( $slideshow[ 'images' ] ) > 0 ) {
	
      // Icon navigation index
      if ( $slideshow[ 'control_nav' ] == 'enable' )
		$index = 0;
      
      // Random number variable so slideshow don't get mixed up
      $slideshow[ 'rand' ] = rand( 0, 10000 );
      
      // Maths for cubes and blinds
      $cubesheight = ceil( $slideshow[ 'height' ] / $slideshow[ 'cubes_rows' ] );
      $cubeswidth = ceil( $slideshow[ 'width' ] / $slideshow[ 'cubes_cols' ] );
      $blindwidth = ceil( $slideshow[ 'width' ] / $slideshow[ 'blinds_cols' ] );
      
      // Load plugin scripts is not already loaded
      if ( function_exists( 'riva_slider_pro_scripts' ) )
		riva_slider_pro_scripts();
      
      // Load this slideshows function onto the page
      if ( function_exists( 'riva_slider_pro_slideshow_script' ) )
		$rs_pro_scripts .= riva_slider_pro_slideshow_script( $id, $slideshow, $slideshow[ 'rand' ] );
      
      $html .= "\n <div id=\"riva-slider-". esc_attr( $id ). "-shell\">";
      
      $html .= "<!--[if lte IE 7]>";
      $html .= "<div class=\"slider-id-". esc_attr( $id ) ." rs-ie7 riva-slider-holder\" id=\"riva-slider-". esc_attr( $id ) ."-". esc_attr( $slideshow[ 'rand' ] ) ."\" style=\"width: ". $slideshow[ 'width' ] ."px; height: ". $slideshow[ 'height' ] ."px;\">";
      $html .= "<![endif]-->";
      $html .= "<!--[if IE 8]>";
      $html .= "<div class=\"slider-id-". esc_attr( $id ) ." rs-ie8 riva-slider-holder\" id=\"riva-slider-". esc_attr( $id ) ."-". esc_attr( $slideshow[ 'rand' ] ) ."\" style=\"width: ". $slideshow[ 'width' ] ."px; height: ". $slideshow[ 'height' ] ."px;\">";
      $html .= "<![endif]-->";
      $html .= "<!--[if IE 9]>";
      $html .= "<div class=\"slider-id-". esc_attr( $id ) ." rs-ie9 riva-slider-holder\" id=\"riva-slider-". esc_attr( $id ) ."-". esc_attr( $slideshow[ 'rand' ] ) ."\" style=\"width: ". $slideshow[ 'width' ] ."px; height: ". $slideshow[ 'height' ] ."px;\">";
      $html .= "<![endif]-->";
      $html .= "<!--[if !IE]><!-->";
      $html .= "<div class=\"slider-id-". esc_attr( $id ) ." riva-slider-holder\" id=\"riva-slider-". esc_attr( $id ) ."-". esc_attr( $slideshow[ 'rand' ] ) ."\" style=\"width: ". $slideshow[ 'width' ] ."px; height: ". $slideshow[ 'height' ] ."px;\">";
      $html .= "<!--<![endif]-->";
      
      if ( $slideshow[ 'preload' ] != 'false' ) {
        if ( isset( $slideshow[ 'transparent_preloader' ] ) && $slideshow[ 'transparent_preloader' ] == true )
            $preload_colour = 'transparent';
        else
            $preload_colour = '#'. $slideshow[ 'preload_colour' ];
		$html .= "<div class=\"riva-slider-preload\" style=\"width: ". $slideshow[ 'width' ] ."px; height: ". $slideshow[ 'height' ] ."px; background: url(". riva_slider_pro_path() ."images/loading/". $slideshow[ 'preload-icon' ] .") no-repeat center center ". $preload_colour ."\"></div>";
      }
	
      $html .= "<ul class=\"riva-slider\" style=\"width: ". $slideshow[ 'width' ] ."px; height: ". $slideshow[ 'height' ] ."px;\">";
      
      foreach ( $slideshow[ 'images' ] as $image ) {
	
		// Text & Title container class
		if ( $image[ 'content-title' ] && $image[ 'content-text' ] )
		  $text_class = 'both';
		elseif ( $image[ 'content-title' ] && empty( $image[ 'content-text' ] ) )
		  $text_class = 'title-only';
		elseif ( empty( $image[ 'content-title' ] ) && $image[ 'content-text' ] )
		  $text_class = 'text-only';
		  
		$li_classes = "rs-". esc_attr( str_replace( '_', '-', $image[ 'play-button' ] ) );
		  
		// Determine video type and URL
		if ( $image[ 'image-link' ] == 'video' && $image[ 'video-url' ] != '' ) {
		  $image[ 'video-url' ] = riva_slider_pro_video_url( $image[ 'video-url' ], $image[ 'related-videos' ], $image[ 'autoplay-video' ] );
		  $li_classes .= " rs-video ";
		}
		  
		$html .= "\n     <li class=\"". $li_classes ."\" style=\"width: ". $slideshow[ 'width' ] ."px; height: ". $slideshow[ 'height' ] ."px;\">";
		
		if ( ( isset( $image[ 'image-link' ] ) && $image[ 'image-link' ] == 'webpage' && isset( $image[ 'webpage-url' ] ) && $image[ 'webpage-url' ] != '' ) || ( isset( $image[ 'image-link' ] ) && $image[ 'image-link' ] == 'video' && isset( $image[ 'video-url' ] ) && $image[ 'video-url' ] != '' ) ) {
		  if ( $image[ 'image-link' ] == 'webpage' )
		    $link_classes = esc_attr( $image[ 'webpage-url' ] );
		  elseif ( $image[ 'image-link' ] == 'video' )
		    $link_classes = esc_attr( $image[ 'video-url' ] );
		  $html .= "\n      <a href=\"". $link_classes ."\" target=\"_". esc_attr( $image[ 'link-target' ] ) ."\">";
		}
		
		$dimensions = '';
		if ( $settings[ 'resize' ] == true )
		  $dimensions .= "width=\"". $slideshow[ 'width' ] ."\" height=\"". $slideshow[ 'height' ] ."\"";
		$html .= "<img src=\"". esc_url_raw( riva_slider_pro_image( null, $image[ 'image-url' ], $slideshow[ 'width' ], $slideshow[ 'height' ] ) ) ."\" alt=\"". esc_attr( $image[ 'image-alt' ] ) ."\" title=\"". esc_attr( $image[ 'image-title' ] ) ."\" class=\"rs-image\" ". $dimensions ." />";
		
		if ( ( isset( $image[ 'image-link' ] ) && $image[ 'image-link' ] == 'webpage' && isset( $image[ 'webpage-url' ] ) && $image[ 'webpage-url' ] != '' ) || ( isset( $image[ 'image-link' ] ) && $image[ 'image-link' ] == 'video' && isset( $image[ 'video-url' ] ) && $image[ 'video-url' ] != '' ) )
		  $html .= "\n      </a>";
		  
		if ( ( isset( $image[ 'content-title' ] ) && $image[ 'content-title' ] != '' ) || ( isset( $image[ 'content-text' ] ) &&  $image[ 'content-text' ] != '' ) ) {
		  
		  $html .= "<div class=\"rs-content rs-". esc_attr( $text_class ) ." rs-text-". esc_attr( $image[ 'content-position' ] ) ."\">";
		  
		  $content_styles = " style=\"background-color: #". esc_attr( $image[ 'content-bg-colour' ] ) .";";
		  if ( $image[ 'content-bg-opacity' ] == '100' )
		    $content_styles .= " opacity: 1;";
		  else
		    $content_styles .= " opacity: 0.". $image[ 'content-bg-opacity' ] ."; filter: alpha(opacity=". $image[ 'content-bg-opacity' ] .");";
		  $content_styles .= "\"";
		  
		  $html .= "<div class=\"rs-content-holder\"". $content_styles .">";
		  if ( isset( $image[ 'content-title' ] ) && $image[ 'content-title' ] != '' )
		    $html .= "<h2 style=\"color: #". esc_attr( $image[ 'content-text-colour' ] ) .";\">". esc_html( $image[ 'content-title' ] ) ."</h2>";
		  if ( isset( $image[ 'content-text' ] ) && $image[ 'content-text' ] != '' )
		    $html .= "<span style=\"color: #". esc_attr( $image[ 'content-text-colour' ] ) .";\">". str_replace( '\\', '', $image[ 'content-text' ] ) ."</span>";
		  $html .= "</div>";
		  
		  $html .= "</div>";
		
		}
		
		$html .= "\n     </li>";
	  
      }
      
      $html .= "\n    </ul>";
	
      if ( $slideshow[ 'control_nav' ] == 'enable' ) {
	
		$html .= "\n    <ul class=\"rs-control-nav\">";
	      
		foreach ( $slideshow[ 'images' ] as $image ) {
		  
		  $index++;
		  $control_li_styles = '';
		  if ( $slideshow[ 'control_nav_index' ] == 'icons' )
		    $control_li_classes = 'rs-icons';
		  elseif ( $slideshow[ 'control_nav_index' ] == 'numbers' )
		    $control_li_classes = 'rs-numbers';
		  elseif ( $slideshow[ 'control_nav_index' ] == 'thumbnails' ) {
		    $control_li_classes = 'rs-thumbnails';
		    $control_li_styles .= " style=\"background: url(". esc_url_raw( riva_slider_pro_image( null, $image[ 'image-url' ], $slideshow[ 'thumb_width' ], $slideshow[ 'thumb_height' ] ) ) .") top left no-repeat !important; width: ". $slideshow[ 'thumb_width' ] ."px; height: ". $slideshow[ 'thumb_height' ] .";\"";
		  }
		  
		  $html .= "\n     <li class=\"". $control_li_classes ."\"". $control_li_styles .">";
		  if ( $slideshow[ 'control_nav_index' ] == 'numbers' )
		    $html .= $index;
		  $html .= "</li>";
		  
		}
		
		$html .= "\n    </ul>";
	
      }
	
      if ( $slideshow[ 'pause_button' ] == 'enable' ) {
		$pause_classes = '';
		if ( $slideshow[ 'pause_button_hover'  ] == 'false' )
		  $pause_classes .= " no-hide";
		$html .= "\n    <div class=\"rs-pause-button". $pause_classes ."\"></div>";
      }
	
      if ( $slideshow[ 'direction_nav' ] == 'enable' ) {
		$direction_classes = '';
		if ( $slideshow[ 'direction_nav_hover' ] == 'false' )
		  $direction_classes .= " no-hide";
		  $nav_top = ( $slideshow[ 'height' ] / 2 ) - 14;
		$html .= "\n    <div class=\"rs-next ". esc_attr( $slideshow[ 'direction_nav_pos' ] ) . $direction_classes ."\" style=\"top: ". $nav_top ."px;\"></div>";
		$html .= "\n    <div class=\"rs-prev ". esc_attr( $slideshow[ 'direction_nav_pos' ] ) . $direction_classes ."\" style=\"top: ". $nav_top ."px;\"></div>";
      }
	
      $html .= "</div>";
      
      if ( ( $slideshow[ 'skin' ] != 'custom' && $slideshow[ 'shadow' ] != 'disable' ) || ( $slideshow[ 'skin' ] == 'custom' && $slideshow[ 'shadow_image' ] != '' && $slideshow[ 'shadow' ] != 'disable' ) )
		$html .= "\n  <div class=\"slider-shadow-". esc_attr( $id ) ."\"></div>";
      
    $html .= "</div>";
      
    } elseif ( count( $slideshow[ 'images' ] ) == 0 )
      $html .= "<div><strong>". __( 'This slideshow has no images set to it.', 'riva_slider_pro' ) ."</strong></div>";
    
  } else
    $html .= "<div><strong>". __( 'The slideshow you have selected does not exist (ID: '. $id .'). Please try a slideshow with a different ID.', 'riva_slider_pro' ) ."</strong></div>";
  
  if ( $echo == true )
    echo html_entity_decode( $html );
  else
    return html_entity_decode( $html );

}  

?>
<?php

function riva_slider_pro_options( $type = null, $id = null ) {
  
  global $post;
  
  // Establish important variables
  $info = riva_slider_pro_info();
  $slideshows = get_option( $info[ 'shortname' ] .'_slideshows' );
  $ajax_nonce = riva_slider_pro_nonce();
  
  
  // If ID is set, get the appropriate array
  if ( $id ) {
  
    if ( riva_slider_pro_search( $slideshows, 'index', $id, 'boolean' ) == true )
      $slideshow = riva_slider_pro_search( $slideshows, 'index', $id, 'array' );
      
    elseif ( riva_slider_pro_search( $slideshows, 'index', $id, 'boolean' ) == false )
      $slideshow[ 'name' ] = 'New Slideshow';
      
  } else {
    $slideshow[ 'name' ] = '';
  }
  
  // If GD library is enabled
  if ( extension_loaded( 'gd' ) && function_exists( 'gd_info' ) )
    $gd_library = 'true';
  else
    $gd_library = 'false';
  
  // Slideshow start image
  if ( isset( $slideshow ) && isset( $slideshow[ 'image-source' ] )
    && ( ( count( $slideshow[ 'images' ] ) >= 1 && $slideshow[ 'image-source' ] == 'this_panel' )
    || ( $slideshow[ 'image-source' ] == 'images_from_posts' ) ) ) {
    
    $index = 1;
    $image_index = array();
    
    if ( $slideshow[ 'image-source' ] == 'this_panel' ) {
      foreach( $slideshow[ 'images' ] as $image ) {
        array_push( $image_index, $index );
        $index++;
      }
    }
    
    elseif ( $slideshow[ 'image-source' ] == 'images_from_posts' ) {
        
        $image_category = explode( ', ', $slideshow[ 'image-category' ] );
      
        if ( $image_category[ 0 ] == '0' ) {
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
          if ( isset( $image[ 'image-url' ] ) && $image[ 'image-url' ] != '' ) {
            array_push( $image_index, $index );
            $index++;
          }
        endwhile;
        wp_reset_query();
        
    }
  } else
    $image_index = '';
  
    
  // Determine save button text
  if ( $type == 'edit' || $type == 'global-settings' )
    $save = 'Save Settings';
  elseif ( $type == 'new' )
    $save = 'Create Slideshow';
  
  
  if ( $type == 'edit' || $type == 'new' ) {
    $options = array(
      array(
        'type' => 'title',
        'name' => 'Slideshow Editor : '. $slideshow[ 'name' ],
        'button' => false
      ),
      array(
        'type' => 'form',
        'state' => 'open',
        'id' => $info[ 'shortname' ] .'_edit_options'
      ),
      array(
        'type' => 'navigation',
        'change' => true
      ),
      
      
      
      
      array(
        'type' => 'div',
        'state' => 'open',
        'name' => 'Images',
        'id' => $info[ 'shortname' ] .'_images',
        'first' => true
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => true,
        'id' => 'images'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'image-interface'
      ),
      array(
        'type' => 'div',
        'state' => 'close'
      ),
      
      
      
      
      array(
        'type' => 'div',
        'state' => 'open',
        'name' => 'Preloading',
        'id' => $info[ 'shortname' ] .'_preloading',
        'first' => false
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Preload Settings',
        'table' => false
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => true,
        'id' => 'preloading'
      ),
      array(
        'type' => 'radio',
        'name' => 'Enable Preloading',
        'id' => 'preload',
        'std' => 'true',
        'options' => array( 'true', 'false' ),
        'desc' => 'Enable or disable the preloader. The preloader is shown until the images have all been fully loaded, then fades out.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'text-hash',
        'name' => 'Preload Colour',
        'id' => 'preload_colour',
        'std' => 'FFF',
        'desc' => 'Preload background colour. Enter a valid hexadecimal code (between 3 and 6 letters).'
      ),
      array(
        'type' => 'checkbox',
        'name' => 'Transparent Preloader',
        'id' => 'transparent_preloader',
        'std' => 'true',
        'desc' => 'Check this option to make the preloader transparent. Doing so will disable the above Preload Colour option.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'radio-icons',
        'name' => 'Preload Icon',
        'id' => 'preload-icon',
        'std' => 'circle_default.gif',
        'desc' => 'Click the above button to choose from the various different preloading icons. The choosen icon will be shown before the slideshows images have been loaded.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'state' => 'close'
      ),
      
      
      
      
      array(
        'type' => 'div',
        'state' => 'open',
        'name' => 'Transitions',
        'id' => $info[ 'shortname' ] .'_transitions',
        'first' => false
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Transition Settings',
        'table' => false
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => true,
        'id' => 'transitions'
      ),
      array(
        'type' => 'text',
        'name' => 'Transition Time',
        'id' => 'trans_time',
        'std' => '1200',
        'desc' => 'The amount of time taken for the transition between images to be completed (in milliseconds).',
        'int' => true
      ),
      array(
        'type' => 'select',
        'name' => 'Effect',
        'id' => 'trans_effect',
        'std' => 'slide',
        'options' => array( 'slide', 'fade', 'blinds_left', 'blinds_right', 'cubes_left', 'cubes_right', 'cubes_diagonal_down', 'cubes_diagonal_up', 'cubes_left', 'cubes_right', 'cubes_random', 'random' ),
        'desc' => 'Choose one of the various transition effects.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'text',
        'name' => 'Blinds per column',
        'id' => 'blinds_cols',
        'std' => '14',
        'desc' => 'Number of blinds per column (vertically) when using a blind-based transition effect.',
        'int' => true
      ),
      array(
        'type' => 'text',
        'name' => 'Cubes per column',
        'id' => 'cubes_cols',
        'std' => '8',
        'desc' => 'Number of cubes per column (vertically) when using a cube-based transition effect.',
        'int' => true
      ),
      array(
        'type' => 'text',
        'name' => 'Cubes per row',
        'id' => 'cubes_rows',
        'std' => '4',
        'desc' => 'Number of cubes per row (horizontally) when using a cube-based transition effect.',
        'int' => true
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'text',
        'name' => 'Pause Time',
        'id' => 'pause_time',
        'std' => '4800',
        'desc' => 'Time the image is shown for before transitioning to the next image',
        'int' => true
      ),
      array(
        'type' => 'radio',
        'name' => 'Show Timer',
        'id' => 'pause_timer',
        'std' => 'true',
        'options' => array( 'true', 'false' ),
        'desc' => 'Enable or disable the timer bar that slides across the image indicating the pause time remaining.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'state' => 'close'
      ),
      
      
      
      
      array(
        'type' => 'div',
        'state' => 'open',
        'name' => 'Navigation',
        'id' => $info[ 'shortname' ] .'_navigation',
        'first' => false
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Direction Navigation',
        'table' => false
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => true,
        'id' => 'direction_nav'
      ),
      array(
        'type' => 'radio',
        'name' => 'Enable/Disable',
        'id' => 'direction_nav',
        'std' => 'enable',
        'options' => array( 'enable', 'disable' ),
        'desc' => 'Enable or disable the next & previous arrow buttons.'
      ),
      array(
        'type' => 'radio',
        'name' => 'Show on Hover',
        'id' => 'direction_nav_hover',
        'std' => 'true',
        'options' => array( 'true', 'false' ),
        'desc' => 'Select "True" to only show the Direction Navigation when the users hovers over the slideshow.'
      ),
      array(
        'type' => 'select',
        'name' => 'Position',
        'id' => 'direction_nav_pos',
        'std' => 'inside',
        'options' => array( 'inside', 'outside' ),
        'desc' => 'Choose a position for the direction navigation.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Control Navigation',
        'table' => false
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => true,
        'id' => 'control-navigation'
      ),
      array(
        'type' => 'radio',
        'name' => 'Enable/Disable',
        'id' => 'control_nav',
        'std' => 'enable',
        'options' => array( 'enable', 'disable' ),
        'desc' => 'Enable or disable the icon navigation.'
      ),
      array(
        'type' => 'radio',
        'name' => 'Index Type',
        'id' => 'control_nav_index',
        'std' => 'icons',
        'options' => array( 'icons', 'numbers', 'thumbnails' ),
        'desc' => 'Choose a index type for the control navigation.'
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Thumbnail Width',
        'id' => 'thumb_width',
        'std' => '50',
        'desc' => 'Set the width of the control navigation thumbnails when set to use thumbnails.'
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Thumbnail Height',
        'id' => 'thumb_height',
        'std' => '30',
        'desc' => 'Set the height of the control navigation thumbnails when set to use thumbnails.'
      ),
      array(
        'type' => 'select',
        'name' => 'Position',
        'id' => 'control_nav_pos',
        'std' => 'bottom_center',
        'options' => array( 'bottom_center', 'bottom_left', 'bottom_right' ),
        'desc' => 'Determine the position of the control navigation with this option.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Pause Button',
        'table' => false
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => true,
        'id' => 'pause-slideshow'
      ),
      array(
        'type' => 'radio',
        'name' => 'Enable/Disable',
        'id' => 'pause_button',
        'std' => 'enable',
        'options' => array( 'enable', 'disable' ),
        'desc' => 'Enable or disable the slideshow pause button (this option will be unavailable if auto-play is disabled.'
      ),
      array(
        'type' => 'radio',
        'name' => 'Show on Hover',
        'id' => 'pause_button_hover',
        'std' => 'true',
        'options' => array( 'true', 'false' ),
        'desc' => 'Select "True" to only show the Pause Button when the users hovers over the slideshow.'
      ),
      array(
        'type' => 'select',
        'name' => 'Position',
        'id' => 'pause_button_pos',
        'std' => 'top_right',
        'options' => array( 'center', 'top_left', 'top_right', 'bottom_left', 'bottom_right' ),
        'desc' => 'Determine the position of the pause button.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'state' => 'close'
      ),
      
      
       
      
      array(
        'type' => 'div',
        'state' => 'open',
        'name' => 'Settings',
        'id' => $info[ 'shortname' ] .'_settings',
        'first' => false
      ),
      array(
        'type' => 'sub-title',
        'name' => 'General Settings',
        'table' => false
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => true,
        'id' => 'general-settings'
      ),
      array(
        'type' => 'text',
        'name' => 'Slideshow Name',
        'id' => 'name',
        'std' => '',
        'desc' => 'Enter a slideshow name. This field must not be left empty, otherwise the slideshow will not save correctly.'
      ),
      array(
        'type' => 'textarea',
        'name' => 'Description',
        'id' => 'desc',
        'std' => '',
        'desc' => 'Enter a brief slideshow description.'
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Width',
        'id' => 'width',
        'std' => '640',
        'desc' => 'Set the width of the slideshow in pixels.'
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Height',
        'id' => 'height',
        'std' => '240',
        'desc' => 'Set the height of the slideshow in pixels.'
      ),
      array(
        'type' => 'checkbox',
        'name' => 'Random Order',
        'id' => 'random_order',
        'std' => 'true',
        'desc' => 'Tick this checkbox to randomize the order of the images.'
      ),
      array(
        'type' => 'select',
        'name' => 'Start Image',
        'id' => 'start_image',
        'std' => '1',
        'options' => $image_index,
        'desc' => 'Select an image to start the slideshow on.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Auto-Play Settings',
        'table' => false
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => true,
        'id' => 'auto-play-settings'
      ),
      array(
        'type' => 'radio',
        'name' => 'Auto-Play',
        'id' => 'auto_play',
        'std' => 'true',
        'options' => array( 'true', 'false' ),
        'desc' => 'Determine if the slideshow will automatically play after it is loaded.'
      ),
      array(
        'type' => 'radio',
        'name' => 'Play Once',
        'id' => 'play_once',
        'std' => 'false',
        'options' => array( 'true', 'false' ),
        'desc' => 'If set to true, the slideshow will only loop through all the images once, then stop playing. Not available if "Auto-Play" set to false.'
      ),
      array(
        'type' => 'checkbox',
        'name' => 'Buttons After',
        'id' => 'buttons_play_once',
        'std' => 'true',
        'desc' => 'Only enable the direction navigation & control navigation after the slideshow has been played once. Only available if "Play Once" set to true.'
        
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'state' => 'close'
      ),
      
      
      
      
      array(
        'type' => 'div',
        'state' => 'open',
        'name' => 'Styling',
        'id' => $info[ 'shortname' ] .'_styling',
        'first' => false
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Skins & Styling',
        'table' => false
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => true,
        'id' => 'skins'
      ),
      array(
        'type' => 'skins',
        'name' => 'Select a skin',
        'id' => 'skin',
        'std' => 'default',
        'options' => riva_slider_pro_skins(),
        'desc' => 'Select a styling skin. If you wish to create your own custom styling, select "custom" and the custom styling options will become available below.'
      ),
      array(
        'type' => 'checkbox',
        'name' => 'Show/hide shadow',
        'id' => 'shadow',
        'std' => 'disable',
        'desc' => 'Check this option to disable the slideshows shadow regardless of the skin or custom styling.'
      ),
      array(
        'type' => 'divider'
      ),     
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => false,
        'id' => 'custom_styling'
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Custom Styling',
        'table' => true
      ),
      array(
        'type' => 'select',
        'name' => 'Style the..',
        'id' => 'edit_section',
        'std' => 'select_a_section',
        'options' => array( 'select_a_section', 'shell', 'direction_navigation', 'control_navigation', 'shadow', 'pause_button', 'video_button', 'close_video', 'pause_timer', 'image_text' )
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => false,
        'id' => 'shell_styling'
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Shell',
        'table' => true
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Border thickness',
        'id' => 'border_thickness',
        'std' => '0',
        'desc' => 'Set the border thickness. Choose zero for no border.'
      ),
      array(
        'type' => 'text-hash',
        'name' => 'Border colour',
        'id' => 'border_colour',
        'std' => '000',
        'desc' => 'Select a border colour.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => false,
        'id' => 'direction_navigation_styling'
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Direction Navigation',
        'table' => true
      ),
      array(
        'type' => 'text',
        'name' => '"Next" image',
        'id' => 'direction_nav_next_image',
        'std' => riva_slider_pro_dir( $nice = true ) .'styles/skins/default/next.png',
        'desc' => 'Image to be used as the "next" direction navigation button.'
      ),
      array(
        'type' => 'text',
        'name' => '"Prev" image',
        'id' => 'direction_nav_prev_image',
        'std' => riva_slider_pro_dir( $nice = true ) .'styles/skins/default/prev.png',
        'desc' => 'Image to be used as the "prev" direction navigation button.'
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Width',
        'id' => 'direction_nav_width',
        'std' => '25',
        'desc' => 'Width of the direction navigation images.'
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Height',
        'id' => 'direction_nav_height',
        'std' => '32',
        'desc' => 'Height of the direction navigation images.'
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Inside margin',
        'id' => 'direction_nav_margin',
        'std' => '10',
        'desc' => 'Set the width of the inside margin for the direction navigation.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => false,
        'id' => 'control_navigation_styling'
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Background Settings',
        'table' => true
      ),
      array(
        'type' => 'background',
        'name' => 'Background',
        'id' => 'control_nav_bg',
        'std' => array( riva_slider_pro_dir( $nice = true ) .'styles/skins/default/controlnav_bg.jpg', 'repeat-x', 'top', 'left', 'ddd' ),
        'desc' => 'Various settings for the control navigation background.'
      ),
      array(
        'type' => 'text-pixels-corners',
        'name' => 'Rounded Corners',
        'id' => 'control_nav_corners',
        'std' => array( '0', '0', '0', '0' ),
        'desc' => 'Choose values for the various rounded corners. These do not working in IE8 or lower.'
      ),
      array(
        'type' => 'text-pixels-four',
        'name' => 'Padding',
        'id' => 'control_nav_padding',
        'std' => array( '4', '4', '4', '4' ),
        'desc' => 'This is the amount of space between the icons and the background.'
      ),
      array(
        'type' => 'text-pixels-four',
        'name' => 'Margin',
        'id' => 'control_nav_margin',
        'std' => array( '0', '0', '0', '0' ),
        'desc' => 'The margin is the space between the control navigation and the sliders shell. Sometimes this setting will not take effect correctly.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Icon Settings',
        'table' => true
      ),
      array(
        'type' => 'text',
        'name' => 'Icon image',
        'id' => 'control_nav_icon_image',
        'std' => riva_slider_pro_dir( $nice = true ) .'styles/skins/default/controlnav.png',
        'desc' => 'Select an icon image for the control navigation (when set to use icons). The image should contain three icons vertically aligned. See the documentation for more information.'
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Width',
        'id' => 'control_nav_icon_width',
        'std' => '14',
        'desc' => 'Set the width of the control navigation icon in pixels.'
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Height',
        'id' => 'control_nav_icon_height',
        'std' => '14',
        'desc' => 'Set the height of the control navigation icon in pixels.'
      ),
      array(
        'type' => 'text-pixels-four',
        'name' => 'Margin',
        'id' => 'control_nav_icon_margin',
        'std' => array( '4', '4', '4', '4' ),
        'desc' => 'Choose the various margins between each Control Navigation icon.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Number Settings',
        'table' => true
      ),
      array(
        'type' => 'text-hash',
        'name' => 'Text colour',
        'id' => 'control_nav_text_colour',
        'std' => '777',
        'desc' => 'The colour of the control navigations text when you are using numbers to represent the images.'
      ),
      array(
        'type' => 'text-hash',
        'name' => 'Text colour (current)',
        'id' => 'control_nav_text_colour_current',
        'std' => '000',
        'desc' => 'Colour of the current control navigation image number.'
      ),
      array(
        'type' => 'text-pixels-four',
        'name' => 'Margin',
        'id' => 'control_nav_number_margin',
        'std' => array( '4', '4', '4', '4' ),
        'desc' => 'Choose the various margins between each Control Navigation number.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Thumbnail Settings',
        'table' => true
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Border thickness',
        'id' => 'control_nav_border_thickness',
        'std' => '0',
        'desc' => 'Set the border thickness. Choose zero for no border.'
      ),
      array(
        'type' => 'text-hash',
        'name' => 'Border colour',
        'id' => 'control_nav_border_colour',
        'std' => '000',
        'desc' => 'Select a border colour.'
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Border thickness (current)',
        'id' => 'control_nav_current_border_thickness',
        'std' => '3',
        'desc' => 'Set the border thickness for the current thumbnail image. Choose zero for no border.'
      ),
      array(
        'type' => 'text-hash',
        'name' => 'Border colour (current)',
        'id' => 'control_nav_current_border_colour',
        'std' => '000',
        'desc' => 'Select a border colour for the current thumbnail.'
      ),
      array(
        'type' => 'text-pixels-four',
        'name' => 'Margin',
        'id' => 'control_nav_thumb_margin',
        'std' => array( '4', '4', '4', '4' ),
        'desc' => 'Choose the various margins between each Control Navigation thumbnail.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => false,
        'id' => 'shadow_styling'
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Shadow',
        'table' => true
      ),
      array(
        'type' => 'text',
        'name' => 'Image',
        'id' => 'shadow_image',
        'std' => riva_slider_pro_dir( $nice = true ) .'styles/skins/default/shadow.png',
        'desc' => 'Choose a shadow to be used as the slideshow shadow.'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => false,
        'id' => 'pause_button_styling'
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Pause Button',
        'table' => true
      ),
      array(
        'type' => 'text',
        'name' => 'Image',
        'id' => 'pause_button_image',
        'std' => riva_slider_pro_dir( $nice = true ) .'styles/skins/default/pause.png',
        'desc' => 'Choose an image to be used as the pause slideshow button.'
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Width',
        'id' => 'pause_button_width',
        'std' => '32',
        'desc' => 'Set the width of the pause button.'
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Height',
        'id' => 'pause_button_height',
        'std' => '32',
        'desc' => 'Set the height of the pause button.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => false,
        'id' => 'video_button_styling'
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Video Button',
        'table' => true
      ),
      array(
        'type' => 'text',
        'name' => 'Image',
        'id' => 'video_button_image',
        'std' => riva_slider_pro_dir( $nice = true ) .'styles/skins/default/video_big.png',
        'desc' => 'Choose an image to be used as the video button.'
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Width',
        'id' => 'video_button_width',
        'std' => '65',
        'desc' => 'Set the width of the pause button.'
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Height',
        'id' => 'video_button_height',
        'std' => '65',
        'desc' => 'Set the height of the pause button.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => false,
        'id' => 'close_video_styling'
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Close Video',
        'table' => true
      ),
      array(
        'type' => 'text',
        'name' => 'Image',
        'id' => 'close_video_image',
        'std' => riva_slider_pro_dir( $nice = true ) .'styles/skins/default/close.png',
        'desc' => 'Choose an image to be used as the close video button.'
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Width',
        'id' => 'close_video_width',
        'std' => '28',
        'desc' => 'Set the width of the close video button.'
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Height',
        'id' => 'close_video_height',
        'std' => '32',
        'desc' => 'Set the height of the close video button.'
      ),
      array(
        'type' => 'text-pixels-corners',
        'name' => 'Rounded Corners',
        'id' => 'close_video_corners',
        'std' => array( '0', '0', '0', '0' ),
        'desc' => 'Choose values for the various rounded corners. These do not working in IE8 or lower.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => false,
        'id' => 'pause_timer_styling'
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Pause Timer',
        'table' => true
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Height',
        'id' => 'pause_timer_height',
        'std' => '5',
        'desc' => 'Select a height in pixels for the pause timer.'
      ),
      array(
        'type' => 'text-hash',
        'name' => 'Background Colour',
        'id' => 'pause_timer_bg',
        'std' => '000',
        'desc' => 'Select a colour for the pause timer.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => false,
        'id' => 'image_text_styling'
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Font Settings',
        'table' => true
      ),
      array(
        'type' => 'text',
        'name' => 'Font',
        'id' => 'text_font',
        'std' => htmlspecialchars( '"Helvetica Neue", Arial, sans-serif' ),
        'desc' => 'Enter the font(s) you wish to use for the images text.'
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Font Size (title)',
        'id' => 'text_font_size_h2',
        'std' => '16',
        'desc' => 'Choose a font size for the images texts title (h2).'
      ),
      array(
        'type' => 'text-pixels-four',
        'name' => 'Title padding',
        'id' => 'text_font_h2_padding',
        'std' => array( 10, 10, 0, 10 ),
        'desc' => 'Padding for the image texts title.'
      ),
      array(
        'type' => 'text-pixels',
        'name' => 'Font Size (text)',
        'id' => 'text_font_size_span',
        'std' => '12',
        'desc' => 'Choose a font size for the image text (span).'
      ),
      array(
        'type' => 'text-pixels-four',
        'name' => 'Text padding',
        'id' => 'text_font_span_padding',
        'std' => array( 10, 10, 10, 10 ),
        'desc' => 'Padding for the image text.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Top & Bottom Text',
        'table' => true
      ),
      array(
        'type' => 'text-pixels-four',
        'name' => 'Margin',
        'id' => 'text_bg_top_bottom_margin',
        'std' => array( 10, 10, 10, 10 ),
        'desc' => 'Set the margin for the image texts background (positioned at the top or bottom).'
      ),
      array(
        'type' => 'text-pixels-corners',
        'name' => 'Rounded Corners',
        'id' => 'text_bg_top_bottom_corners',
        'std' => array( 10, 10, 10, 10 ),
        'desc' => 'Set a value for the rounded corners of the top and bottom text overlays.'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Left & Right Text',
        'table' => true
      ),
      array(
        'type' => 'text-pixels-four',
        'name' => 'Margin',
        'id' => 'text_bg_left_right_margin',
        'std' => array( 10, 10, 10, 10 ),
        'desc' => 'Set the margin for the image texts background (positioned at the left or right).'
      ),
      array(
        'type' => 'text-pixels-corners',
        'name' => 'Rounded Corners',
        'id' => 'text_bg_left_right_corners',
        'std' => array( 10, 10, 10, 10 ),
        'desc' => 'Set a value for the rounded corners of the left and right text overlays.'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'state' => 'close'
      ),
      
      
      
      
      array(
        'type' => 'div',
        'state' => 'open',
        'name' => 'Advanced',
        'id' => $info[ 'shortname' ] .'_advanced',
        'first' => false
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Backup This Slideshow',
        'table' => false
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => true,
        'id' => 'import/export'
      ),
      array(
        'type' => 'export',
        'name' => 'Export',
        'index' => $id,
        'desc' => 'Note: Changes made before slideshow has been saved will not have been recorded in the export textarea. For the most up to date backup, save your slideshow first before recording the code in this box.'
      ),
      array(
        'type' => 'import',
        'name' => 'Import',
        'index' => $id,
        'desc' => 'Paste the export code into this textarea, then update the slideshow. The settings will be extracted from the pasted code and inserted into this slideshow. Do not import slideshows from other Wordpress sites. This will cause errors and may cause the plugin to malfunction.'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'state' => 'close'
      ),
      
      
      
      
      
      array(
        'type' => 'form-save',
        'save' => $save
      ),
      array(
        'type' => 'form',
        'state' => 'close'
      )
    );
  }
  
  elseif ( $type == 'image' ) {
    $options = array(
      array(
        'type' => 'navigation',
        'change' => false
      ),
      array(
        'type' => 'div',
        'name' => 'Image',
        'id' => $info[ 'shortname' ] .'_images_image',
        'state' => 'open',
        'first' => 'true'
      ),
      array(
        'type' => 'text-image',
        'name' => 'Image URL',
        'id' => 'image-url',
        'desc' => 'Edit the image URL.',
        'std' => '',
        'first' => 'true'
      ),
      array(
        'type' => 'radio',
        'name' => 'Link image too',
        'id' => 'image-link',
        'desc' => 'Link the image to either a webpage or a video.',
        'std' => 'webpage',
        'options' => array( 'webpage', 'video' ),
        'first' => 'true'
      ),
      array(
        'type' => 'divider',
        'first' => 'true'
      ),
      array(
        'type' => 'text',
        'name' => 'Webpage URL',
        'id' => 'webpage-url',
        'desc' => 'Enter the webpage URL you want to link this image too.',
        'std' => '',
        'first' => 'true'
      ),
      array(
        'type' => 'text',
        'name' => 'Video URL',
        'id' => 'video-url',
        'std' => '',
        'desc' => 'Enter/paste the videos URL here.',
        'first' => 'true'
      ),
      array(
        'type' => 'radio',
        'name' => 'Auto-play',
        'id' => 'autoplay-video',
        'std' => 'true',
        'desc' => 'Choose to automatically play the video when it loads.',
        'options' => array( 'true', 'false' ),
        'first' => 'true'
      ),
      array(
        'type' => 'radio',
        'name' => 'Related Videos',
        'id' => 'related-videos',
        'std' => 'false',
        'desc' => 'Select true to display related videos once the current video has finished playing (YouTube only).',
        'options' => array( 'true', 'false' ),
        'first' => 'true'
      ),
      array(
        'type' => 'select',
        'name' => 'Button position',
        'id' => 'play-button',
        'std' => 'center',
        'desc' => 'Choose a place to put the video play button.',
        'options' => array( 'center', 'top_left', 'top_right', 'bottom_left', 'bottom_right' ),
        'first' => 'true'
      ),
      array(
        'type' => 'divider',
        'first' => 'true'
      ),
      array(
        'type' => 'div',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'name' => 'Text',
        'id' => $info[ 'shortname' ] .'_images_text',
        'state' => 'open',
        'first' => 'false'
      ),
      array(
        'type' => 'text',
        'name' => 'Title',
        'id' => 'content-title',
        'desc' => 'Add a title to the text overlay displayed on the image.',
        'std' => '',
        'first' => 'false'
      ),
      array(
        'type' => 'textarea',
        'name' => 'Text',
        'id' => 'content-text',
        'desc' => 'Enter some text to be displayed in the text overlay. Basic HTML is allowed.',
        'std' => '',
        'first' => 'false'
      ),
      array(
        'type' => 'select',
        'name' => 'Position',
        'id' => 'content-position',
        'desc' => 'Select a position to present the images text overlay.',
        'std' => 'left',
        'options' => array( 'left', 'right', 'top', 'bottom' ),
        'first' => 'false'
      ),
      array(
        'type' => 'text',
        'name' => 'Opacity',
        'id' => 'content-bg-opacity',
        'desc' => 'Enter a numerical value for the text & backgrounds opacity (between 0 - 100).',
        'std' => '70',
        'small' => 'true',
        'int' => true,
        'first' => 'false'
      ),
      array(
        'type' => 'text-hash',
        'name' => 'Background Colour',
        'id' => 'content-bg-colour',
        'desc' => 'Choose a background colour for the text overlay.',
        'std' => '000',
        'first' => 'false'
      ),
      array(
        'type' => 'text-hash',
        'name' => 'Text Colour',
        'id' => 'content-text-colour',
        'desc' => 'Choose a text colour.',
        'std' => 'FFF',
        'first' => 'false'
      ),
      array(
        'type' => 'divider',
        'first' => 'false'
      ),
      array(
        'type' => 'div',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'name' => 'HTML',
        'id' => $info[ 'shortname' ] .'_images_html',
        'state' => 'open',
        'first' => 'false'
      ),
      array(
        'type' => 'text',
        'name' => '"Title" attribute',
        'id' => 'image-title',
        'desc' => 'HTML title attribute shown when cursor hovers over image.',
        'std' => '',
        'first' => 'false'
      ),
      array(
        'type' => 'text',
        'name' => '"Alt" attribute',
        'id' => 'image-alt',
        'desc' => 'HTML alt text shown if image doesnt exist. Also for SEO purposes.',
        'std' => '',
        'first' => 'false'
      ),
      array(
        'type' => 'select',
        'name' => 'Image link target',
        'id' => 'link-target',
        'desc' => 'Specify how the images link should open; either in a new window/tab, or in the current window.',
        'std' => '_self',
        'options' => array( 'self', 'blank', 'parent', 'top' ),
        'first' => 'false'
      ),
      array(
        'type' => 'divider',
        'first' => 'false'
      ),
      array(
        'type' => 'div',
        'state' => 'close'
      )
    );
  }
  
  elseif ( $type == 'global-settings' ) {
    $options = array(
      array(
        'type' => 'title',
        'name' => 'Global Settings'
      ),
      array(
        'type' => 'form',
        'state' => 'open',
        'id' => $info[ 'shortname' ] .'_global_settings'
      ),
      array(
        'type' => 'div',
        'state' => 'open',
        'name' => 'Load Scripts',
        'id' => $info[ 'shortname' ] .'_load_scripts',
        'first' => true
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Serial Code (required)',
        'table' => false
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => true,
        'id' => 'serial'
      ),
      array(
        'type' => 'serial',
        'name' => 'Serial Code',
        'id' => 'serial_code',
        'std' => '',
        'desc' => 'Please enter a serial code to allow you to download updates and receive support.',
        'role' => 'rivasliderpro_view_serial_code'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'state' => 'open',
        'name' => 'Load Scripts',
        'id' => $info[ 'shortname' ] .'_load_scripts',
        'first' => true
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Load Scripts',
        'table' => false
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => true,
        'id' => 'scripts'
      ),
      array(
        'type' => 'radio',
        'name' => 'jQuery',
        'id' => 'enable_jquery',
        'std' => 'true',
        'options' => array( 'true', 'false' )
      ),
      array(
        'type' => 'radio',
        'name' => 'jQuery Riva Slider',
        'id' => 'enable_script',
        'std' => 'true',
        'options' => array( 'true', 'false' )
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'state' => 'open',
        'name' => 'Timthumb Settings',
        'id' => $info[ 'shortname' ] .'_timthumb_settings',
        'first' => true
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Timthumb Settings',
        'table' => false
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => true,
        'id' => 'timthumb'
      ),
      array(
        'type' => 'radio',
        'name' => 'Enable Image Resizing',
        'id' => 'resize',
        'std' => $gd_library,
        'options' => array( 'true', 'false' )
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'state' => 'open',
        'name' => 'Installation Settings',
        'id' => $info[ 'shortname' ] .'_installation_settings',
        'first' => true
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Installation Settings',
        'table' => false
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => true,
        'id' => 'installation'
      ),
      array(
        'type' => 'installation'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'state' => 'open',
        'name' => 'Installation Settings',
        'id' => $info[ 'shortname' ] .'_reset_css',
        'first' => true
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Reset Settings',
        'table' => false
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => true,
        'id' => 'Reset Settings'
      ),
      array(
        'type' => 'reset',
        'nonce' => $ajax_nonce
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'state' => 'open',
        'name' => 'Backup All Settings',
        'id' => $info[ 'shortname' ] .'_backup',
        'first' => true
      ),
      array(
        'type' => 'sub-title',
        'name' => 'Backup All Settings',
        'table' => false
      ),
      array(
        'type' => 'table',
        'state' => 'open',
        'show' => true,
        'id' => 'Backup All Settings'
      ),
      array(
        'type' => 'backup'
      ),
      array(
        'type' => 'divider'
      ),
      array(
        'type' => 'table',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'state' => 'close'
      ),
      array(
        'type' => 'form-save',
        'save' => $save
      ),
      array(
        'type' => 'form',
        'state' => 'close'
      )
    );
  }
  
  elseif ( $type == 'meta' ) {
    $options = array(
      array(
        'type' => 'checkbox',
        'id' => 'get_post_info',
        'std' => 'true',
        'desc' => 'Fetch link, title & text automatically.'
      ),
      array(
        'type' => 'tabs'
      ),
      array(
        'type' => 'div',
        'name' => 'Image',
        'id' => 'riva_slider_pro_images_meta',
        'state' => 'open'
      ),
      array(
        'type' => 'hidden',
        'id' => 'image-resized',
        'std' => ''
      ),
      array(
        'type' => 'text',
        'name' => 'Image URL',
        'id' => 'image-url',
        'std' => '',
        'desc' => 'Edit the image URL.'
      ),
      array(
        'type' => 'radio',
        'name' => 'Link Image Too',
        'id' => 'image-link',
        'options' => array( 'webpage', 'video' ),
        'std' => 'webpage',
        'desc' => 'Link the image to either a webpage or a video.'
      ),
      array(
        'type' => 'text',
        'name' => 'Webpage URL',
        'id' => 'webpage-url',
        'std' => '',
        'desc' => 'Enter the webpage URL you want to link this image too.'
      ),
      array(
        'type' => 'text',
        'name' => 'Video URL',
        'id' => 'video-url',
        'std' => '',
        'desc' => 'Enter/paste the videos URL here. Only Youtube video are compatible at this current time.'
      ),
      array(
        'type' => 'radio',
        'name' => 'Autoplay Video',
        'id' => 'autoplay-video',
        'options' => array( 'true', 'false' ),
        'std' => 'true',
        'desc' => 'Choose to automatically play the video when it loads.'
      ),
      array(
        'type' => 'radio',
        'name' => 'Related Videos',
        'id' => 'related-videos',
        'options' => array( 'true', 'false' ),
        'std' => 'false',
        'desc' => 'Select true to display related videos once the current video has finished playing (YouTube only).'
      ),
      array(
        'type' => 'select',
        'name' => 'Button position',
        'id' => 'play-button',
        'std' => 'center',
        'options' => array( 'center', 'top_left', 'top_right', 'bottom_left', 'bottom_right' ),
        'desc' => 'Choose a place to put the video play button.'
      ),
      array(
        'type' => 'div',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'name' => 'Text',
        'id' => 'riva_slider_pro_text_meta',
        'state' => 'open'
      ),
      array(
        'type' => 'text',
        'name' => 'Title',
        'id' => 'content-title',
        'std' => '',
        'desc' => 'Add a title to the text overlay displayed on the image.'
      ),
      array(
        'type' => 'textarea',
        'name' => 'Text',
        'id' => 'content-text',
        'std' => '',
        'desc' => 'Enter some text to be displayed in the text overlay. Basic HTML is allowed.'
      ),
      array(
        'type' => 'select',
        'name' => 'Position',
        'id' => 'content-position',
        'std' => 'left',
        'options' => array( 'left', 'right', 'top', 'bottom' ),
        'desc' => 'Select a position to present the images text overlay.'
      ),
      array(
        'type' => 'text',
        'name' => 'Opacity',
        'id' => 'content-bg-opacity',
        'int' => true,
        'desc' => 'Enter a numerical value for the text & backgrounds opacity (between 0 - 100).',
        'std' => '70'
      ),
      array(
        'type' => 'text-hash',
        'name' => 'Background Colour',
        'id' => 'content-bg-colour',
        'std' => '000',
        'desc' => 'Choose a background colour for the text overlay.'
      ),
      array(
        'type' => 'text-hash',
        'name' => 'Text Colour',
        'id' => 'content-text-colour',
        'std' => 'FFF',
        'desc' => 'Choose a text colour.'
      ),
      array(
        'type' => 'div',
        'state' => 'close'
      ),
      array(
        'type' => 'div',
        'name' => 'HTML',
        'id' => 'riva_slider_pro_html_meta',
        'state' => 'open'
      ),
      array(
        'type' => 'text',
        'name' => '"Title" attribute',
        'id' => 'image-title',
        'std' => '',
        'desc' => 'HTML title attribute shown when cursor hovers over image.'
      ),
      array(
        'type' => 'text',
        'name' => '"Alt" attribute',
        'id' => 'image-alt',
        'std' => '',
        'desc' => 'HTML alt text shown if image doesnt exist. Also for SEO purposes.'
      ),
      array(
        'type' => 'select',
        'name' => 'Image link target',
        'id' => 'link-target',
        'std' => 'self',
        'options' => array( 'self', 'blank', 'parent', 'top' ),
        'desc' => 'Specify how the images link should open; either in a new window/tab, or in the current window.'
      ),
      array(
        'type' => 'div',
        'state' => 'close'
      )
    );
  }
  
  else {
    $options = array(
      array(
        'type' => 'title',
        'name' => 'Slideshows',
        'button' => 'slideshow'
      ),
      array(
        'type' => 'div',
        'state' => 'open',
        'id' => $info[ 'shortname' ] .'_manage_slideshows',
        'first' => true
      ),
      array(
        'type' => 'slideshow-interface'
      ),
      array(
        'type' => 'div',
        'state' => 'close'
      )
    );
  }
  
  return $options;
}

?>
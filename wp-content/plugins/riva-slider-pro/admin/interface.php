<?php

/*
  Function that populates the user interfaces.
  This function gets the returned arrays from 'riva_slider_pro_options' and displays the fields in the appropriate order.
*/
function riva_slider_pro_interface( $options, $id = null, $orderby = null, $order = null, $global = null ) {
  
  // Establish essential variables
  global $wp_version;
  $info = riva_slider_pro_info();
  $shortname = $info[ 'shortname' ];
  $permalink = $info[ 'permalink' ];
  $action = riva_slider_pro_action();
  
  if ( isset( $action[ 'message' ] ) )
    $die = riva_slider_pro_message( $action );
  
  // Get the rest of the variables
  $slideshows = get_option( $info[ 'shortname' ] .'_slideshows' );
  $auto_increment = get_option( $info[ 'shortname' ] .'_auto_increment' );
  $global_settings = get_option( $info[ 'shortname' ] .'_global_settings' );
  $latest = get_option( $info[ 'shortname' ] .'_update_check' );
  $ajax_nonce = riva_slider_pro_nonce();
  $image = 1;
  $image_options = riva_slider_pro_options( 'image' );
  
  if ( riva_slider_pro_search( $slideshows, 'index', $id, 'boolean' ) == true ) {
    $image_increment = $auto_increment[ ( riva_slider_pro_search( $slideshows, 'index', $id, 'id' ) )+1 ];
  } else {
    $image_increment = 1;
  }
  
  
  // Obtain the slideshow via its array index, if an ID has been specified
  if ( $global == true ) {
    $slideshow = $global_settings;
    $id = 'none';
    if ( riva_slider_pro_check_mu() == true )
      $admin_url = network_admin_url();
    else
      $admin_url = admin_url();
  }
    
  elseif ( $id )
    $slideshow = riva_slider_pro_search( $slideshows, 'index', $id, 'array' );
  
  elseif ( $orderby && $order )
    $slideshows = riva_slider_pro_order_array( $slideshows, 'name', $order );
    
  elseif ( is_null( $id ) == true )
    $slideshow[ 'images' ] = array();
    
  
  // Check Wordpress version
  if ( version_compare( $wp_version, '3.0', '>=' ) ) { ?>
  <!--[if lte IE 7]>
  <div class="wrap ie7">
  <![endif]-->
  <!--[if IE 8]>
  <div class="wrap ie8">
  <![endif]-->
  <!--[if IE 9]>
  <div class="wrap ie9">
  <![endif]-->
  <!--[if !IE]><!-->
  <div class="wrap">
  <!--<![endif]-->
    <?php foreach ( $options as $value ) {
      switch ( $value[ 'type' ] ) {
	
	
	case 'title' : ?>
	  <!-- A nice little icon to display beside the title -->
	  <div id="icon-plugins" class="icon32"></div>
	  <h2 id="rs-title"><?php esc_html_e( $value[ 'name' ], 'riva_slider_pro' ); ?><?php if ( isset( $value[ 'button' ] ) && $value[ 'button' ] == 'slideshow' ) { ?><a href="admin.php?page=<?php echo esc_attr( $permalink[ 'new_slideshow' ] ); ?>" id="add-new" class="button add-new-h2"><?php _e( 'Add New', 'riva_slider_pro' ); ?></a><?php } ?></h2>
	  <?php if ( get_option( $info[ 'shortname' ] .'_serialcode' ) && riva_slider_pro_check_caps( 'rivasliderpro_view_serial_code' ) ) { ?><div id="message" class="rs-message dont-hide error"><p><?php _e( 'Do not forget to enter your Serial Code. You will not receive notifications of updates without it. <a href="admin.php?page='. $permalink[ 'global_settings' ] .'">Click here</a> to enter it.', 'riva_slider_pro' ); ?></p></div><?php } ?>
	  <?php if ( isset( $die ) ) { ?><div id="message" class="rs-message updated"><p><?php echo esc_html( $die ); ?></p></div><?php } ?>
	<?php break;
	
	
	case 'sub-title' :
	  if ( esc_attr( $value[ 'table' ] ) == true ) { ?>
	  <tr>
	    <td colspan="2">
	  <?php } ?>
	      <h3 class="sub-title"><?php esc_html_e( $value[ 'name' ], 'riva_slider_pro' ); ?></h3>
	  <?php if ( esc_attr( $value[ 'table' ] ) == true ) { ?>
	    </td>
	  </tr>
	  <?php }
	break;
      
      
	case 'navigation' : ?>
	  <ul class="rs-navigation"><?php
	  $tabs = $options;
	  foreach ( $tabs as $option ) {
	    if ( esc_attr( $option[ 'type' ] ) == 'div' && esc_attr( $option[ 'state' ] ) == 'open' ) { ?>
		<li><a href="javascript: jQuery.rivaTab('<?php echo esc_attr( $option[ 'id' ] ); ?>');" <?php if ( $option[ 'first' ] == true ) { echo 'class="current"'; } ?> id="<?php echo esc_attr( $option[ 'id' ] ); ?>_tab"><?php esc_attr_e( $option[ 'name' ], 'riva_slider_pro' ); ?></a></li>
	    <?php }
	  }
	  if ( esc_attr( $value[ 'change' ] ) == true ) { ?>
	  <li class="right">
	    <select name="quick_change" id="quick_change">
	      <option value="0"><?php esc_html_e( 'Change Slideshow', 'riva_slider_pro' ); ?></option>
	      <?php foreach ( $slideshows as $change ) { ?>
	      <option value="<?php echo esc_attr( $change[ 'index' ] ); ?>"><?php echo esc_html( $change[ 'index' ] .': '. $change[ 'name' ] ); ?></option>
	      <?php } ?>
	    </select>
	  </li>
	  <?php } ?>
	  </ul>
	  <div class="clear"></div><?php
	break;
      
      
	case 'reset' : ?>
	  <tr>
	    <th scope="row"><?php _e( 'Reset CSS', 'riva_slider_pro' ); ?></th>
	    <td colspan="1">
	      <a href="admin.php?page=<?php echo $permalink[ 'global_settings' ]; ?>&amp;reset=css" id="reset-css" class="button-secondary"><?php _e( 'Reset CSS', 'riva_slider_pro' ); ?></a>
	    </td>
	  </tr>
	  <tr>
	    <th scope="row"><?php _e( 'Reset Defaults', 'riva_slider_pro' ); ?></th>
	    <td colspan="1">
	      <a href="admin.php?page=<?php echo $permalink[ 'global_settings' ]; ?>&amp;reset=defaults" id="reset-settings" class="button-secondary"><?php _e( 'Reset Slideshows & Settings', 'riva_slider_pro' ); ?></a>
	    </td>
	  </tr>
	<?php break;
      
      
	case 'backup' : ?>
	  <tr>
	    <th scope="row"><label for="export"><?php _e( 'Export', 'riva_slider_pro' ); ?></label></th>
	    <td colspan="1">
	      <a href="admin.php?page=rs_global_settings&amp;export=true" id="export" class="button-secondary">Export Plugin Settings</a>
	      <div class="desc"><?php _e( 'This button will output a .txt file that contains all of your plugins settings, including slideshows. Save this .txt file in a safe place for future restorations.', 'riva_slider_pro' ); ?></div>
	    </td>
	  </tr>
	  <tr>
	    <th scope="row"><label for="import"><?php _e( 'Import', 'riva_slider_pro' ); ?></label></th>
	    <td colspan="1">
	      <textarea name="import" id="import"></textarea>
	      <div class="desc"><?php _e( 'Paste the code from one of your exported .txt files into this text area. Then click "Save Settings" to restore the entire plugin (including slideshows) to a previous state. Do not use codes from a Wordpress site. Doing so will throw up errors and you will have to reset the plugin.', 'riva_slider_pro' ); ?></div>
	    </td>
	  </tr>
	<?php break;
	
	
	case 'div' :
	  if ( $value[ 'state' ] == 'open' ) { ?>
	    <div class="content-holder" <?php if ( isset( $value[ 'id' ] ) ) { ?>id="<?php echo esc_attr( $value[ 'id' ] ); ?>"<?php } ?> style="display:<?php if ( $value[ 'first' ] == true ) { ?>block<?php } else { ?>none<?php } ?>;">
	  <?php } elseif ( $value[ 'state' ] == 'close' ) { ?>
	    </div>
	  <?php }
	break;
      
	
	case 'table' :
	  if ( $value[ 'state' ] == 'open' ) { ?>
	  <table class="form-table" <?php if ( isset( $value[ 'id' ] ) ) { ?>id="<?php echo esc_attr( $value[ 'id' ] ); } ?>" <?php if ( $value[ 'show' ] == false ) { echo 'style="display: none;"'; } ?>>
	    <tbody>
	  <?php } elseif ( $value[ 'state' ] == 'close' ) { ?>
	    </tbody>
	  </table>
	  <?php }
	break;
	
	
	case 'form' :
	  if ( $value[ 'state' ] == 'open' ) { ?>
	  <form method="post" <?php if ( isset( $value[ 'id' ] ) ) { ?>id="<?php echo esc_attr( $value[ 'id' ] ); } ?>">
	    <input type="hidden" name="security" id="security" value="<?php echo $ajax_nonce; ?>" />
	    <input type="hidden" name="index" id="index" value="<?php if ( isset( $id ) ) echo esc_attr( $id ); else echo esc_attr( $auto_increment[ 0 ] ); ?>" />
	  <?php } elseif ( $value[ 'state' ] == 'close' ) { ?>
	  </form>
	  <?php }
	break;
      
      
	case 'form-save' : ?>
	  <tr>
	    <td colspan="2">
	      <input type="submit" class="button-primary save-button" name="save" value="<?php esc_attr_e( $value[ 'save' ], 'riva_slider_pro' ); ?>" />
	      <img src="<?php bloginfo( 'wpurl' ); ?>/wp-admin/images/wpspin_light.gif" class="ajax-loading" id="loading" style="position: relative; top: 4px; left: -5px;">
	    </td>
	  </tr>
	<?php break;
	
	
	case 'export' : ?>
	  <tr>
	    <th scope="row"><label for="export"><?php esc_html_e( $value[ 'name' ], 'riva_slider_pro' ); ?></label></th>
	    <td colspan="1" id="export-td">
	      <?php $slideshow = riva_slider_pro_search( $slideshows, 'index', $value[ 'index' ], 'array' ); ?>
	      <textarea name="export" id="export" readonly="true" <?php if ( riva_slider_pro_search( $slideshows, 'index', $value[ 'index' ], 'boolean' ) == false ) echo 'class="disabled" readonly="true"'; ?>><?php if ( riva_slider_pro_search( $slideshows, 'index', $value[ 'index' ], 'boolean' ) == true ) echo base64_encode( serialize( $slideshow ) ); ?></textarea>
	      <div class="desc"><?php esc_html_e( $value[ 'desc' ] ); ?></div>
	    </td>
	  </tr>
	<?php break;
	
	
	case 'import' : ?>
	  <tr>
	    <th scope="row"><label for="import"><?php esc_html_e( $value[ 'name' ], 'riva_slider_pro' ); ?></label></th>
	    <td colspan="1">
	      <textarea name="import" id="import" <?php if ( riva_slider_pro_search( $slideshows, 'index', $value[ 'index' ], 'boolean' ) == false ) echo 'class="disabled" readonly="true"'; ?>></textarea>
	      <div class="desc"><?php esc_html_e( $value[ 'desc' ] ); ?></div>
	    </td>
	  </tr>
	<?php break;
      
      
	case 'postbox' :
	  if ( $value[ 'state' ] == 'open' ) { ?>
	    <div class="postbox" <?php if ( isset( $value[ 'id' ] ) ) { ?>id="<?php echo esc_attr( $value[ 'id' ] ); } ?>">
	      <h3><span><?php esc_html_e( $value[ 'name' ], 'riva_slider_pro' ); ?></span></h3>
	      <div class="inside"></div>
	  <?php } elseif ( $value[ 'state' ] == 'close' ) { ?>
	    </div><!-- END .postbox -->
	  <?php }
	break;
      
      
	case 'divider' : ?>
	  <tr>
	    <td colspan="2" class="rs-divider-td">
	      <div class="rs-divider"></div>
	    </td>
	  </tr>
	<?php break;
	
	case 'background' : ?>
	  <tr>
	    <th scope="row"><label for="<?php echo esc_attr( $value[ 'id' ] ); ?>"><?php esc_html_e( $value[ 'name' ], 'riva_slider_pro' ); ?></label></th>
	    <td colspan="1">
	      <input type="text" name="<?php echo esc_attr( $value[ 'id' ] ); ?>[]" class="medium" id="<?php echo esc_attr( $value[ 'id' ] ); ?>-image" value="<?php if ( isset( $slideshow[ $value[ 'id' ] ] ) && $id ) { echo esc_attr( $slideshow[ $value[ 'id' ] ][ 0 ] ); } else { echo esc_attr( $value[ 'std' ][ 0 ] ); } ?>" />
	      <select name="<?php echo esc_attr( $value[ 'id' ] ); ?>[]" class="medium">
		<option value="no-repeat" <?php if ( isset( $slideshow[ $value[ 'id' ] ][ 1 ] ) && $id ) { selected( esc_attr( 'no-repeat' ), esc_attr( $slideshow[ $value[ 'id' ] ][ 1 ] ) ); } else { selected( esc_attr( 'no-repeat' ), esc_attr( $value[ 'std' ][ 1 ] ) ); } ?>><?php esc_html_e( 'No repeat', 'riva_slider_pro' ); ?></option>
		<option value="repeat-x" <?php if ( isset( $slideshow[ $value[ 'id' ] ][ 1 ] ) && $id ) { selected( esc_attr( 'repeat-x' ), esc_attr( $slideshow[ $value[ 'id' ] ][ 1 ] ) ); } else { selected( esc_attr( 'repeat-x' ), esc_attr( $value[ 'std' ][ 1 ] ) ); } ?>><?php esc_html_e( 'Repeat horizontally', 'riva_slider_pro' ); ?></option>
		<option value="repeat-y" <?php if ( isset( $slideshow[ $value[ 'id' ] ][ 1 ] ) && $id ) { selected( esc_attr( 'repeat-y' ), esc_attr( $slideshow[ $value[ 'id' ] ][ 1 ]  ) ); } else { selected( esc_attr( 'repeat-y' ), esc_attr( $value[ 'std' ][ 1 ] ) ); } ?>><?php esc_html_e( 'Repeat vertically', 'riva_slider_pro' ); ?></option>
	      </select>
	      <select name="<?php echo esc_attr( $value[ 'id' ] ); ?>[]" class="small">
		<option value="top" <?php if ( isset( $slideshow[ $value[ 'id' ] ][ 2 ] ) && $id ) { selected( esc_attr( 'top' ), esc_attr( $slideshow[ $value[ 'id' ] ][ 2 ] ) ); } else { selected( esc_attr( 'top' ), esc_attr( $value[ 'std' ][ 2 ] ) ); } ?>><?php esc_html_e( 'Top', 'riva_slider_pro' ); ?></option>
		<option value="center" <?php if ( isset( $slideshow[ $value[ 'id' ] ][ 2 ] ) && $id ) { selected( esc_attr( 'center' ), esc_attr( $slideshow[ $value[ 'id' ] ][ 2 ] ) ); } else { selected( esc_attr( 'center' ), esc_attr( $value[ 'std' ][ 2 ] ) ); } ?>><?php esc_html_e( 'Center', 'riva_slider_pro' ); ?></option>
		<option value="bottom" <?php if ( isset( $slideshow[ $value[ 'id' ] ][ 2 ] ) && $id ) { selected( esc_attr( 'bottom' ), esc_attr( $slideshow[ $value[ 'id' ] ][ 2 ] ) ); } else { selected( esc_attr( 'bottom' ), esc_attr( $value[ 'std' ][ 2 ] ) ); } ?>><?php esc_html_e( 'Bottom', 'riva_slider_pro' ); ?></option>
	      </select>
	      <select name="<?php echo esc_attr( $value[ 'id' ] ); ?>[]" class="small">
		<option value="left" <?php if ( isset( $slideshow[ $value[ 'id' ] ][ 3 ] ) && $id ) { selected( esc_attr( 'left' ), esc_attr( $slideshow[ $value[ 'id' ] ][ 3 ] ) ); } else { selected( esc_attr( 'left' ), esc_attr( $value[ 'std' ][ 3 ] ) ); } ?>><?php esc_html_e( 'Left', 'riva_slider_pro' ); ?></option>
		<option value="center" <?php if ( isset( $slideshow[ $value[ 'id' ] ][ 3 ] ) && $id ) { selected( esc_attr( 'center' ), esc_attr( $slideshow[ $value[ 'id' ] ][ 3 ] ) ); } else { selected( esc_attr( 'center' ), esc_attr( $value[ 'std' ][ 3 ] ) ); } ?>><?php esc_html_e( 'Center', 'riva_slider_pro' ); ?></option>
		<option value="right" <?php if ( isset( $slideshow[ $value[ 'id' ] ][ 3 ] ) && $id ) { selected( esc_attr( 'right' ), esc_attr( $slideshow[ $value[ 'id' ] ][ 3 ] ) ); } else { selected( esc_attr( 'right' ), esc_attr( $value[ 'std' ][ 3 ] ) ); } ?>><?php esc_html_e( 'Right', 'riva_slider_pro' ); ?></option>
	      </select>
	      <span class="rs-color-picker-holder">
		#<input type="text" name="<?php echo esc_attr( $value[ 'id' ] ); ?>[]" class="small hex" id="<?php echo esc_attr( $value[ 'id' ] ); ?>-colour" value="<?php if ( isset( $slideshow[ $value[ 'id' ] ][ 4 ] ) && $id ) { echo esc_attr( $slideshow[ $value[ 'id' ] ][ 4 ] ); } else { echo esc_attr( $value[ 'std' ][ 4 ] ); } ?>" /><div class="rs-color-picker" style="top: -5px !important; left: 76px !important;"></div>
	      </span>
	      <?php if ( isset( $value[ 'desc' ] ) ) { ?><div class="desc"><?php esc_html_e( $value[ 'desc' ] ); ?></div><?php } ?>
	    </td>
	  </tr>
	<?php break;
      
      
	case 'serial' : ?>
	  <tr>
	    <th scope="row"><label for="<?php echo esc_attr( $value[ 'id' ] ); ?>"><?php esc_html_e( $value[ 'name' ], 'riva_slider_pro' ); ?></label></th>
	    <td colspan="1">
	      <?php if ( ( isset( $value[ 'role' ] ) && riva_slider_pro_check_caps( $value[ 'role' ] ) ) || !isset( $value[ 'role' ] ) ) { ?>
	      <input type="text" name="<?php echo esc_attr( $value[ 'id' ] ); ?>" class="<?php if ( isset( $value[ 'int' ] ) && $value[ 'int' ] == true ) echo 'small int'; else echo 'large'; ?>" id="<?php echo esc_attr( $value[ 'id' ] ); ?>" value="<?php if ( isset( $slideshow[ $value[ 'id' ] ] ) && $id ) { echo esc_attr( $slideshow[ $value[ 'id' ] ] ); } else { echo esc_attr( $value[ 'std' ] ); } ?>" />
	      <?php if ( isset( $value[ 'desc' ] ) ) { ?><div class="desc"><?php esc_html_e( $value[ 'desc' ] ); ?></div><?php } ?>
	      <?php } else { ?>
	      <p><strong><?php _e( 'You do not have sufficient permissions to view the '. $value[ 'name' ] .'.', 'riva_slider_pro' ); ?></strong></p>
	      <?php } ?>
	    </td>
	  </tr>
	<?php break;
      
      
	case 'text' : ?>
	  <tr>
	    <th scope="row"><label for="<?php echo esc_attr( $value[ 'id' ] ); ?>"><?php esc_html_e( $value[ 'name' ], 'riva_slider_pro' ); ?></label></th>
	    <td colspan="1">
	      <input type="text" name="<?php echo esc_attr( $value[ 'id' ] ); ?>" class="<?php if ( isset( $value[ 'int' ] ) && $value[ 'int' ] == true ) echo 'small int'; else echo 'large'; ?>" id="<?php echo esc_attr( $value[ 'id' ] ); ?>" value="<?php if ( isset( $slideshow[ $value[ 'id' ] ] ) && $id ) { echo esc_attr( $slideshow[ $value[ 'id' ] ] ); } else { echo esc_attr( $value[ 'std' ] ); } ?>" />
	      <?php if ( isset( $value[ 'desc' ] ) ) { ?><div class="desc"><?php esc_html_e( $value[ 'desc' ] ); ?></div><?php } ?>
	    </td>
	  </tr>
	<?php break;
      
      
	case 'text-pixels' : ?>
	  <tr>
	    <th scope="row"><label for="<?php echo esc_attr( $value[ 'id' ] ); ?>"><?php esc_html_e( $value[ 'name' ], 'riva_slider_pro' ); ?></label></th>
	    <td colspan="1">
	      <input type="text" name="<?php echo esc_attr( $value[ 'id' ] ); ?>" class="small text-pixels int" id="<?php echo esc_attr( $value[ 'id' ] ); ?>" value="<?php if ( isset( $slideshow[ $value[ 'id' ] ] ) && $id ) { echo esc_attr( $slideshow[ $value[ 'id' ] ] ); } else { echo esc_attr( $value[ 'std' ] ); } ?>" />px
	      <?php if ( isset( $value[ 'desc' ] ) ) { ?><div class="desc"><?php esc_html_e( $value[ 'desc' ] ); ?></div><?php } ?>
	    </td>
	  </tr>
	<?php break;
      
      
	case 'text-pixels-four' : ?>
	  <tr>
	    <th scope="row"><label for="<?php echo esc_attr( $value[ 'id' ] ); ?>"><?php esc_html_e( $value[ 'name' ], 'riva_slider_pro' ); ?></label></th>
	    <td colspan="1">
	      <span class="text-pixels"><?php _e( '<i>Top</i>', 'riva_slider_pro' ); ?>: <input type="text" name="<?php echo esc_attr( $value[ 'id' ] ); ?>[]" class="small text-pixels int" id="<?php echo esc_attr( $value[ 'id' ] ); ?>-top" value="<?php if ( isset( $slideshow[ $value[ 'id' ] ][ 0 ] ) && $id ) { echo esc_attr( $slideshow[ $value[ 'id' ] ][ 0 ] ); } else { echo esc_attr( $value[ 'std' ][ 0 ] ); } ?>" />px</span>
	      <span class="text-pixels"><?php _e( '<i>Right</i>', 'riva_slider_pro' ); ?>: <input type="text" name="<?php echo esc_attr( $value[ 'id' ] ); ?>[]" class="small text-pixels int" id="<?php echo esc_attr( $value[ 'id' ] ); ?>-right" value="<?php if ( isset( $slideshow[ $value[ 'id' ] ][ 1 ] ) && $id ) { echo esc_attr( $slideshow[ $value[ 'id' ] ][ 1 ] ); } else { echo esc_attr( $value[ 'std' ][ 1 ] ); } ?>" />px</span>
	      <span class="text-pixels"><?php _e( '<i>Bottom</i>', 'riva_slider_pro' ); ?>: <input type="text" name="<?php echo esc_attr( $value[ 'id' ] ); ?>[]" class="small text-pixels int" id="<?php echo esc_attr( $value[ 'id' ] ); ?>-bottom" value="<?php if ( isset( $slideshow[ $value[ 'id' ] ][ 2 ] ) && $id ) { echo esc_attr( $slideshow[ $value[ 'id' ] ][ 2 ] ); } else { echo esc_attr( $value[ 'std' ][ 2 ] ); } ?>" />px</span>
	      <span class="text-pixels"><?php _e( '<i>Left</i>', 'riva_slider_pro' ); ?>: <input type="text" name="<?php echo esc_attr( $value[ 'id' ] ); ?>[]" class="small text-pixels int" id="<?php echo esc_attr( $value[ 'id' ] ); ?>-left" value="<?php if ( isset( $slideshow[ $value[ 'id' ] ][ 3 ] ) && $id ) { echo esc_attr( $slideshow[ $value[ 'id' ] ][ 3 ] ); } else { echo esc_attr( $value[ 'std' ][ 3 ] ); } ?>" />px</span>
	      <?php if ( isset( $value[ 'desc' ] ) ) { ?><div class="desc"><?php esc_html_e( $value[ 'desc' ] ); ?></div><?php } ?>
	    </td>
	  </tr>
	<?php break;
      
      
	case 'text-pixels-corners' : ?>
	  <tr>
	    <th scope="row"><label for="<?php echo esc_attr( $value[ 'id' ] ); ?>"><?php esc_html_e( $value[ 'name' ], 'riva_slider_pro' ); ?></label></th>
	    <td colspan="1">
	      <span class="text-pixels"><?php _e( '<i>Top-left</i>', 'riva_slider_pro' ); ?>: <input type="text" name="<?php echo esc_attr( $value[ 'id' ] ); ?>[]" class="small text-pixels int" id="<?php echo esc_attr( $value[ 'id' ] ); ?>-topleft" value="<?php if ( isset( $slideshow[ $value[ 'id' ] ][ 0 ] ) && $id ) { echo esc_attr( $slideshow[ $value[ 'id' ] ][ 0 ] ); } else { echo esc_attr( $value[ 'std' ][ 0 ] ); } ?>" />px</span>
	      <span class="text-pixels"><?php _e( '<i>Top-right</i>', 'riva_slider_pro' ); ?>: <input type="text" name="<?php echo esc_attr( $value[ 'id' ] ); ?>[]" class="small text-pixels int" id="<?php echo esc_attr( $value[ 'id' ] ); ?>-topright" value="<?php if ( isset( $slideshow[ $value[ 'id' ] ][ 1 ] ) && $id ) { echo esc_attr( $slideshow[ $value[ 'id' ] ][ 1 ] ); } else { echo esc_attr( $value[ 'std' ][ 1 ] ); } ?>" />px</span>
	      <span class="text-pixels"><?php _e( '<i>Bottom-left</i>', 'riva_slider_pro' ); ?>: <input type="text" name="<?php echo esc_attr( $value[ 'id' ] ); ?>[]" class="small text-pixels int" id="<?php echo esc_attr( $value[ 'id' ] ); ?>-bottomleft" value="<?php if ( isset( $slideshow[ $value[ 'id' ] ][ 2 ] ) && $id ) { echo esc_attr( $slideshow[ $value[ 'id' ] ][ 2 ] ); } else { echo esc_attr( $value[ 'std' ][ 2 ] ); } ?>" />px</span>
	      <span class="text-pixels"><?php _e( '<i>Bottom-right</i>', 'riva_slider_pro' ); ?>: <input type="text" name="<?php echo esc_attr( $value[ 'id' ] ); ?>[]" class="small text-pixels int" id="<?php echo esc_attr( $value[ 'id' ] ); ?>-bottomright" value="<?php if ( isset( $slideshow[ $value[ 'id' ] ][ 3 ] ) && $id ) { echo esc_attr( $slideshow[ $value[ 'id' ] ][ 3 ] ); } else { echo esc_attr( $value[ 'std' ][ 3 ] ); } ?>" />px</span>
	      <?php if ( isset( $value[ 'desc' ] ) ) { ?><div class="desc"><?php esc_html_e( $value[ 'desc' ] ); ?></div><?php } ?>
	    </td>
	  </tr>
	<?php break;
      
      
	case 'text-hash' : ?>
	  <tr>
	    <th scope="row"><label for="<?php echo esc_attr( $value[ 'id' ] ); ?>"><?php esc_html_e( $value[ 'name' ], 'riva_slider_pro' ); ?></label></th>
	    <td colspan="1">
	      <div class="rs-color-picker-holder">
		#<input type="text" name="<?php echo esc_attr( $value[ 'id' ] ); ?>" class="small hex" id="<?php echo esc_attr( $value[ 'id' ] ); ?>" value="<?php if ( isset( $slideshow[ $value[ 'id' ] ] ) && $id ) { echo esc_attr( $slideshow[ $value[ 'id' ] ] ); } else { echo esc_attr( $value[ 'std' ] ); } ?>" /><div class="rs-color-picker"></div>
	      </div>
	      <?php if ( isset( $value[ 'desc' ] ) ) { ?><div class="desc"><?php esc_html_e( $value[ 'desc' ] ); ?></div><?php } ?>
	    </td>
	  </tr>
	<?php break;
      
      
	case 'textarea' : ?>
	  <tr>
	    <th scope="row"><label for="<?php echo esc_attr( $value[ 'id' ] ); ?>"><?php esc_html_e( $value[ 'name' ], 'riva_slider_pro' ); ?></label>
	    <td colspan="1">
	      <textarea name="<?php echo esc_attr( $value[ 'id' ] ); ?>" id="<?php echo esc_attr( $value[ 'id' ] ); ?>"><?php if ( isset( $slideshow[ $value[ 'id' ] ] ) && $id ) { echo esc_attr( $slideshow[ $value[ 'id' ] ] ); } else { echo esc_attr( $value[ 'std' ] ); } ?></textarea>
	      <?php if ( isset( $value[ 'desc' ] ) ) { ?><div class="desc"><?php esc_html_e( $value[ 'desc' ] ); ?></div><?php } ?>
	    </td>
	  </tr>
	<?php break;
	
	
	case 'select' : ?>
	  <tr>
	    <th scope="row"><label for="<?php echo esc_attr( $value[ 'id' ] ); ?>"><?php esc_html_e( $value[ 'name' ], 'riva_slider_pro' ); ?></label></th>
	    <td colspan="1">
	      <select name="<?php echo esc_attr( $value[ 'id' ] ); ?>" id="<?php echo esc_attr( $value[ 'id' ] ); ?>">
		<?php if ( isset( $value[ 'options' ] ) && is_array( $value[ 'options' ] ) ) { ?>
		<?php foreach ( $value[ 'options' ] as $option ) { ?>
		<option value="<?php echo esc_attr( $option ); ?>" <?php if ( isset( $slideshow[ $value[ 'id' ] ] ) && $id ) { selected( esc_attr( $option ), esc_attr( $slideshow[ $value[ 'id' ] ] ) ); } else { selected( esc_attr( $option ), esc_attr( $value[ 'std' ] ) ); } ?>><?php esc_html_e( ucwords( str_replace( '_', ' ', $option ) ) ); ?></option>
		<?php } ?>
		<?php } ?>
	      </select>
	      <?php if ( isset( $value[ 'desc' ] ) ) { ?><div class="desc"><?php esc_html_e( $value[ 'desc' ] ); ?></div><?php } ?>
	    </td>
	  </tr>
	<?php break;
	
	
	case 'skins' : ?>
	  <tr>
	    <th scope="row"><label for="<?php echo esc_attr( $value[ 'id' ] ); ?>"><?php esc_html_e( $value[ 'name' ], 'riva_slider_pro' ); ?></label></th>
	    <td colspan="1">
	      <select name="<?php echo esc_attr( $value[ 'id' ] ); ?>" id="<?php echo esc_attr( $value[ 'id' ] ); ?>">
		<?php foreach ( $value[ 'options' ] as $key => $option ) { ?>
		<option value="<?php echo esc_attr( $key ); ?>" <?php if ( isset( $slideshow[ $value[ 'id' ] ] ) && $id ) { selected( esc_attr( $key ), esc_attr( $slideshow[ $value[ 'id' ] ] ) ); } else { selected( esc_attr( $key ), esc_attr( $value[ 'std' ] ) ); } ?>><?php esc_html_e( ucwords( str_replace( '_', ' ', $option ) ) ); ?></option>
		<?php } ?>
	      </select>
	      <?php if ( isset( $value[ 'desc' ] ) ) { ?><div class="desc"><?php esc_html_e( $value[ 'desc' ] ); ?></div><?php } ?>
	    </td>
	  </tr>
	<?php break;
	
	
	case 'radio' : ?>
	  <tr>
	    <th scope="row"><?php esc_html_e( $value[ 'name' ], 'riva_slider_pro' ); ?></th>
	    <td colspan="1">
	    <?php foreach ( $value[ 'options' ] as $option ) { ?>
	      <label class="radio" for="<?php echo esc_attr( $value[ 'id' ] .'_'. $option ); ?>">
	      <input type="radio" name="<?php echo esc_attr( $value[ 'id' ] ); ?>" id="<?php echo esc_attr( $value[ 'id' ] .'_'. $option ); ?>" value="<?php echo esc_attr( $option ); ?>" <?php if ( isset( $slideshow[ $value[ 'id' ] ] ) && $id ) { checked( esc_attr( $option ), esc_attr( $slideshow[ $value[ 'id' ] ] ) ); } else { checked( esc_attr( $option ), esc_attr( $value[ 'std' ] ) ); } ?> /><span><?php esc_html_e( ucwords( str_replace( '_', ' ', $option ) ) ); ?></span>
	      </label>
	    <?php } ?>
	    <?php if ( isset( $value[ 'desc' ] ) ) { ?><div class="desc"><?php esc_html_e( $value[ 'desc' ] ); ?></div><?php } ?>
	    </td>
	  </tr>
	<?php break;
	
	
	case 'radio-icons' : ?>
	  <tr>
	    <th scope="row"><?php esc_html_e( $value[ 'name' ], 'riva_slider_pro' ); ?></th>
	    <td colspan="1">
	      <?php $icons = scandir( riva_slider_pro_dir() .'images/loading/' ); ?>
	      <?php unset( $icons[ 0 ], $icons[ 1 ] ); ?>
	      <?php foreach ( $icons as $option ) { ?>
		<?php if ( strpos( $option, '.gif' ) != false || strpos( $option, '.jpg' ) != false || strpos( $option, '.jpeg' ) != false || strpos( $option, '.png' ) != false ) { ?>
		<?php if ( ( $id && $slideshow[ $value[ 'id' ] ] == $option ) || ( is_null( $id ) && $value[ 'std' ] == $option ) ) { ?>
		<div id="radio-icons-current-holder">
		  <div class="radio-icons-current <?php if ( ( $id && $slideshow[ $value[ 'id' ] ] == $option ) || ( is_null( $id ) && $value[ 'std' ] == $option ) ) echo 'selected'; ?>">
		    <div>
		      <img id="preload-current-icon" src="<?php echo esc_attr( riva_slider_pro_path( 'images/loading/'. $option ) ); ?>" />
		    </div>
		  </div>
		  <a href="javascript: return false;" id="show-preload-icons" class="button-secondary"><?php echo esc_attr( 'Show the options', 'riva_slider_pro' ); ?></a>
		</div>
		<div class="clear"></div>
		<?php } ?>
		<?php } ?>
	      <?php } ?>
	      <div id="radio-icons-holder">
	      <?php foreach ( $icons as $option ) { ?>
	      <?php if ( strpos( $option, '.gif' ) != false || strpos( $option, '.jpg' ) != false || strpos( $option, '.jpeg' ) != false || strpos( $option, '.png' ) != false ) { ?>
		<label class="radio-icons <?php if ( ( $id && $slideshow[ $value[ 'id' ] ] == $option ) || ( is_null( $id ) && $value[ 'std' ] == $option ) ) echo 'selected'; ?>" for="<?php echo esc_attr( $value[ 'id' ] .'_'. $option ); ?>">
		  <div>
		    <input type="radio" name="<?php echo esc_attr( $value[ 'id' ] ); ?>" id="<?php echo esc_attr( $value[ 'id' ] .'_'. $option ); ?>" value="<?php echo esc_attr( $option ); ?>" <?php if ( isset( $slideshow[ $value[ 'id' ] ] ) && $id ) { checked( esc_attr( $option ), esc_attr( $slideshow[ $value[ 'id' ] ] ) ); } else { checked( esc_attr( $option ), esc_attr( $value[ 'std' ] ) ); } ?> />
		    <img src="<?php echo esc_attr( riva_slider_pro_path( 'images/loading/'. $option ) ); ?>" />
		  </div>
		</label>
	      <?php } ?>
	      <?php } ?>
	      <div class="clear"></div>
	      </div>
	      <?php if ( isset( $value[ 'desc' ] ) ) { ?><div class="desc"><?php esc_html_e( $value[ 'desc' ] ); ?></div><?php } ?>
	    </td>
	  </tr>
	<?php break;
	
	
	case 'checkbox' : ?>
	  <tr>
	    <th scope="row"><?php esc_html_e( $value[ 'name' ], 'riva_slider_pro' ); ?></th>
	    <td colspan="1">
	      <label for="<?php echo esc_attr( $value[ 'id' ] ); ?>">
	      <input type="checkbox" name="<?php echo esc_attr( $value[ 'id' ] ); ?>" id="<?php echo esc_attr( $value[ 'id' ] ); ?>" value="<?php echo esc_attr( $value[ 'std' ] ); ?>" <?php if ( isset( $slideshow[ $value[ 'id' ] ] ) && $id ) { checked( esc_attr( $value[ 'std' ] ), esc_attr( $slideshow[ $value[ 'id' ] ] ) ); } ?> /><?php esc_html_e( $value[ 'desc' ] ); ?>
	      </label>
	    </td>
	  </tr>
	<?php break;
	
	
	case 'installation' : ?>
	  <tr>
	    <th scope="row"><?php _e( 'PHP Version', 'riva_slider_pro' ); ?></th>
	    <td colspan="1"><?php echo phpversion(); ?></td>
	  </tr>
	  <tr>
	    <th scope="row"><?php _e( 'GB Library', 'riva_slider_pro' ); ?></th>
	    <td colspan="1">
	      <?php if ( extension_loaded( 'gd' ) && function_exists( 'gd_info' ) ) {
		_e( 'GD Library is installed.', 'riva_slider_pro' );
	      } else {
		_e( 'GD Library is not installed and therefore image resizing will not work. Please contact your hosting company to have it enabled.', 'riva_slider_pro' );
	      } ?>
	    </td>
	  </tr>
	  <tr>
	    <th scope="row"><?php _e( 'MySQL Version', 'riva_slider_pro' ); ?></th>
	    <td colspan="1"><?php echo mysql_get_server_info(); ?></td>
	  </tr>
	  <tr>
	    <th scope="row"><?php _e( 'Wordpress Version', 'riva_slider_pro' ); ?></th>
	    <td colspan="1"><?php echo $wp_version; ?></td>
	  </tr>
	  <tr>
	    <th scope="row"><?php _e( 'Plugin Version', 'riva_slider_pro' ); ?></th>
	    <td colspan="1">
	      <?php echo $info[ 'version' ]; if ( empty( $latest ) ) { _e( ' - Your site is blocking outbound requests. This may be due to SSL. Unable to check for updates. Please contact support.', 'riva_slider_pro' ); } elseif ( isset( $latest->version ) && version_compare( $latest->version, $info[ 'version' ], '>' ) ) { _e( ' - A new version of the Riva Slider Pro (', 'riva_slider_pro' ); echo $latest->version; _e( ') is available. You can upgrade via the ', 'riva_slider_pro' ); echo '<a href="'. $admin_url .'plugins.php?plugin_status=upgrade">'; _e( 'plugins', 'riva_slider_pro' ); echo "</a>"; _e( ' admin panel. <br /><b>Please Note:</b> Backup your Skins before updating. All Skins will be deleted when updating and will have to be re-uploaded afterwards.', 'riva_slider_pro' ); } ?>
	    </td>
	  </tr>
	<?php break;
	
	
	case 'image-interface' : ?>	
	<table id="images" width="100%">
	  <tbody>
	    <tr>
	      <!-- Left hand column -->
	      <td valign="top" id="rs-left">
		<div class="rs-holder-wrap">
		<input type="hidden" name="image-increment" value="<?php echo esc_attr( $image_increment ); ?>" />
		<input type="hidden" name="image-index" value="<?php echo esc_attr( count( $slideshow[ 'images' ] ) +1 ); ?>" />
		
		<div id="rs-image-0" class="rs-image-template">
	          <div class="rs-image-container">
		    <div class="rs-image-title">
		      <h3>Image #0</h3>
		      <a href="javascript: jQuery.rivaDeleteSingle(<?php echo esc_attr( $image ); ?>);" class="delete-image submitdelete"><?php _e( 'Delete', 'riva_slider_pro' ); ?></a>
		    </div>
		    <div class="rs-image-wrap">
	              <div class="rs-image-box">
			<img src="<?php echo riva_slider_pro_path( 'images/no_image.jpg' ); ?>" title="" class="rs-image" />
		      </div>
		    </div>
		    <div class="rs-image-shadow"></div>
		  </div>
		  <div class="rs-image-options closed">
		    <div class="rs-image-options-container">
			<?php foreach ( $image_options as $option ) {
			  switch ( $option[ 'type' ] ) {
			    
			    case 'navigation' : ?>
			    <div class="rs-option">
			      <ul class="rs-image-navigation"><?php
			      $tabs = $image_options;
			      foreach ( $tabs as $option ) {
				if ( $option[ 'type' ] == 'div' && $option[ 'state' ] == 'open' ) { ?>
				  <li><a href="javascript: jQuery.rivaImageTab('<?php echo esc_attr( $option[ 'id' ] ); ?>');" <?php if ( $option[ 'first' ] == 'true' ) { echo 'class="current"'; } ?> id="<?php echo esc_attr( $option[ 'id' ] ); ?>_tab"><?php esc_attr_e( $option[ 'name' ], 'riva_slider_pro' ); ?></a></li>
				<?php }
			      } ?>
			      </ul>
			      <div class="clear"></div>
			    </div><?php
			    break;
			      
			    case 'div' :
			      if ( $option[ 'state' ] == 'open' ) { ?><div class="rs-option-panel <?php echo esc_attr( $option[ 'id' ] ); ?> <?php if( $option[ 'first' ] == 'true' ) echo 'open'; elseif ( $option[ 'first' ] == 'false' ) echo 'closed'; ?>"><?php }
			      elseif ( $option[ 'state' ] == 'close' ) { ?></div><?php }
			    break;
			    
			    case 'divider' : ?>
			    <div class="rs-divider-option">
			      <div class="rs-divider-image"></div>
			    </div>
			    <?php break;
			    
			    case 'radio' : ?>
			    <div class="rs-option">
			      <div class="rs-first-column"><?php esc_html_e( $option[ 'name' ], 'riva_slider_pro' ); ?></div>
			      <div class="rs-last-column">
				<?php foreach ( $option[ 'options' ] as $choice ) { ?>
				<label for="<?php echo esc_attr( $option[ 'id' ] ); ?>-0-<?php echo esc_attr( $choice ); ?>" class="radio">
				  <input type="radio" name="<?php echo esc_attr( $option[ 'id' ] ); ?>-0" id="<?php echo esc_attr( $option[ 'id' ] ); ?>-0-<?php echo esc_attr( $choice ); ?>" <?php if ( $choice == $option[ 'std' ] ) echo 'class="checked"'; ?> value="<?php echo esc_attr( $choice ); ?>" <?php checked( $choice, $option[ 'std' ] ); ?> /><?php echo esc_attr( ucwords( $choice ) ); ?>
				</label>
				<?php } ?>
				<div class="desc"><?php esc_html_e( $option[ 'desc' ], 'riva_slider_pro' ); ?></div>
			      </div>
			      <div class="clear"></div>
			    </div>
			    <?php break;
			      
			    case 'select' : ?>
			    <div class="rs-option">
			      <div class="rs-first-column">
				<label for="<?php echo esc_attr( $option[ 'id' ] ); ?>-0"><?php esc_html_e( $option[ 'name' ], 'riva_slider_pro' ); ?></label>
			      </div>
			      <div class="rs-last-column">
				<select name="<?php echo esc_attr( $option[ 'id' ] ); ?>-0" id="<?php echo esc_attr( $option[ 'id' ] ); ?>-0">
				  <?php foreach ( $option[ 'options' ] as $choice ) { ?>
				  <option value="<?php echo esc_attr( $choice ); ?>" <?php selected( $choice, $option[ 'std' ] ); ?>><?php esc_html_e( ucwords( str_replace( '_', ' ', $choice ) ) ); ?></option>
				  <?php } ?>
				</select>
				<div class="desc"><?php esc_html_e( $option[ 'desc' ], 'riva_slider_pro' ); ?></div>
			      </div>
			      <div class="clear"></div>
			    </div>
			    <?php break;
			    
			    case 'text' : ?>
			    <div class="rs-option">
			      <div class="rs-first-column">
				<label for="<?php echo esc_attr( $option[ 'id' ] ); ?>-0"><?php esc_html_e( $option[ 'name' ], 'riva_slider_pro' ); ?></label>
			      </div>
			      <div class="rs-last-column">
				<input type="text" name="<?php echo esc_attr( $option[ 'id' ] ); ?>-0" id="<?php echo esc_attr( $option[ 'id' ] ); ?>-0" <?php if ( isset( $option[ 'small' ] ) && $option[ 'small' ] == 'true' ) echo 'class="small int" style="margin-left: 0px;"'; ?> value="<?php if ( isset( $option[ 'std' ] ) ) echo esc_attr( $option[ 'std' ] ); ?>" />
				<div class="desc"><?php esc_html_e( $option[ 'desc' ], 'riva_slider_pro' ); ?></div>
			      </div>
			      <div class="clear"></div>
			    </div>
			    <?php break;
			    
			    case 'text-image' : ?>
			    <div class="rs-option">
			      <div class="rs-first-column">
				<label for="<?php echo esc_attr( $option[ 'id' ] ); ?>-0"><?php esc_html_e( $option[ 'name' ], 'riva_slider_pro' ); ?></label>
			      </div>
			      <div class="rs-last-column">
				<input type="text" name="<?php echo esc_attr( $option[ 'id' ] ); ?>-0" id="<?php echo esc_attr( $option[ 'id' ] ); ?>-0" value="<?php if ( isset( $option[ 'std' ] ) ) echo esc_attr( $option[ 'std' ] ); ?>" />
				<a href="media-upload.php?post_id=0&amp;tab=riva_slider_pro_tab&amp;change=true&amp;riva_id=0&amp;TB_iframe=true" class="button-secondary change-image thickbox" title="<?php _e( 'Change image #0', 'riva_slider_pro' ); ?>"><?php _e( 'Change image', 'riva_slider_pro' ); ?></a>
				<div class="desc" style="margin-top: 6px;"><?php esc_html_e( $option[ 'desc' ], 'riva_slider_pro' ); ?></div>
			      </div>
			      <div class="clear"></div>
			    </div>
			    <?php break;
			    
			    case 'text-hash' : ?>
			    <div class="rs-option">
			      <div class="rs-first-column">
				<label for="<?php echo esc_attr( $option[ 'id' ] ); ?>-0"><?php esc_html_e( $option[ 'name' ], 'riva_slider_pro' ); ?></label>
			      </div>
			      <div class="rs-last-column">
				<div class="rs-color-picker-holder">
				  #<input type="text" name="<?php echo esc_attr( $option[ 'id' ] ); ?>-0" id="<?php echo esc_attr( $option[ 'id' ] ); ?>-0" class="small hex" value="<?php if ( isset( $option[ 'std' ] ) ) echo esc_attr( $option[ 'std' ] ); ?>" /><div class="rs-color-picker"></div>
				</div>
				<div class="desc"><?php esc_html_e( $option[ 'desc' ], 'riva_slider_pro' ); ?></div>
			      </div>
			      <div class="clear"></div>
			    </div>
			    <?php break;
			    
			    case 'textarea' : ?>
			    <div class="rs-option">
			      <div class="rs-first-column">
				<label for="<?php echo esc_attr( $option[ 'id' ] ); ?>-0"><?php esc_html_e( $option[ 'name' ], 'riva_slider_pro' ); ?></label>
			      </div>
			      <div class="rs-last-column">
				<textarea name="<?php echo esc_attr( $option[ 'id' ] ); ?>-0" id="<?php echo esc_attr( $option[ 'id' ] ); ?>-0"><?php if ( isset( $option[ 'std' ] ) ) echo esc_attr( $option[ 'std' ] ); ?></textarea>
				<div class="desc"><?php esc_html_e( $option[ 'desc' ], 'riva_slider_pro' ); ?></div>
			      </div>
			      <div class="clear"></div>
			    </div>
			    <?php break;
			    
			  }
			} ?>
		    </div>
		    <input type="hidden" name="image-resized-0" id="image-resized-0" value="" />
		    <input type="hidden" name="image-id-0" id="image-id-0" value="0" />
		  </div>
		</div>
		
		<?php if ( ( $id && count( $slideshow[ 'images' ] ) == 0 && $slideshow[ 'image-source' ] == 'this_panel' ) || is_null( $id ) ) { ?>
		  <h3 class="sub-title" id="no-images"><?php _e( 'This slideshow currently has no images', 'riva_slider_pro' ); ?></h3>
		<?php } else { ?>
		
		  <?php if ( $slideshow[ 'image-source' ] == 'images_from_posts' ) { ?><h3 class="sub-title" id="alt-source"><?php _e( 'Slideshow is currently set to use Images From Posts. Therefore, this panel is currently disabled. To enable it, change the image source on the right-hand side.', 'riva_slider_pro' ); ?></h3><?php } ?>
		  
		  <?php foreach ( $slideshow[ 'images' ] as $value ) { ?>
		    <div id="rs-image-<?php echo esc_attr( $value[ 'id' ] ); ?>" class="rs-image-holder" <?php if ( $slideshow[ 'image-source' ] == 'images_from_posts' ) echo 'style="display: none;"'; ?>>
		      <div class="rs-image-container">
			<div class="rs-image-title">
			  <h3>Image #<?php echo esc_attr( $image ); ?></h3>
			  <a href="javascript: jQuery.rivaDeleteSingle(<?php echo esc_attr( $image ); ?>);" class="delete-image submitdelete"><?php _e( 'Delete', 'riva_slider_pro' ); ?></a>
			</div>
			<div class="rs-image-wrap">
			  <div class="rs-image-box">
			    <img src="<?php if ( $value[ 'image-url' ] != '' ) { echo esc_attr( $value[ 'image-resized' ] ); } else { echo riva_slider_pro_path( 'images/no_image.jpg' ); } ?>" title="<?php echo esc_attr( $value[ 'image-title' ] ); ?>" class="rs-image" />
			  </div>
			</div>
			<div class="rs-image-shadow"></div>
		      </div>
		      <div class="rs-image-options closed">
			<div class="rs-image-options-container">
			  <?php foreach ( $image_options as $option ) {
			    switch ( $option[ 'type' ] ) {
			      
			      case 'navigation' : ?>
			      <div class="rs-option">
				<ul class="rs-image-navigation"><?php
				$tabs = $image_options;
				foreach ( $tabs as $option ) {
				  if ( $option[ 'type' ] == 'div' && $option[ 'state' ] == 'open' ) { ?>
				      <li><a href="javascript: jQuery.rivaImageTab('<?php echo esc_attr( $option[ 'id' ] ); ?>');" <?php if ( $option[ 'first' ] == 'true' ) { echo 'class="current"'; } ?> id="<?php echo esc_attr( $option[ 'id' ] ); ?>_tab"><?php esc_attr_e( $option[ 'name' ], 'riva_slider_pro' ); ?></a></li>
				  <?php }
				} ?>
				</ul>
				<div class="clear"></div>
			      </div><?php
			      break;
			      
			      case 'div' :
				if ( $option[ 'state' ] == 'open' ) { ?><div class="rs-option-panel <?php echo esc_attr( $option[ 'id' ] ); ?> <?php if( $option[ 'first' ] == 'true' ) echo 'open'; elseif ( $option[ 'first' ] == 'false' ) echo 'closed'; ?>"><?php }
				elseif ( $option[ 'state' ] == 'close' ) { ?></div><?php }
			      break;
			      
			      case 'divider' : ?>
			      <div class="rs-divider-option">
				<div class="rs-divider-image"></div>
			      </div>
			      <?php break;
			    
			      case 'radio' : ?>
			      <div class="rs-option">
				<div class="rs-first-column"><?php esc_html_e( $option[ 'name' ], 'riva_slider_pro' ); ?></div>
				<div class="rs-last-column">
				  <?php foreach ( $option[ 'options' ] as $choice ) { ?>
				  <label for="<?php echo esc_attr( $option[ 'id' ] ); ?>-<?php echo esc_attr( $value[ 'id' ] ); ?>-<?php echo esc_attr( $choice ); ?>" class="radio">
				    <input type="radio" name="<?php echo esc_attr( $option[ 'id' ] ); ?>-<?php echo esc_attr( $value[ 'id' ] ); ?>" id="<?php echo esc_attr( $option[ 'id' ] ); ?>-<?php echo esc_attr( $value[ 'id' ] ); ?>-<?php echo esc_attr( $choice ); ?>" <?php if ( $choice == $option[ 'std' ] ) echo 'class="checked"'; ?> value="<?php echo esc_attr( $choice ); ?>" <?php if ( isset( $value[ $option[ 'id' ] ] ) ) { checked( $choice, $value[ $option[ 'id' ] ] ); } else { checked( $choice, $option[ 'std' ] ); } ?> /><?php echo esc_attr( ucwords( $choice ) ); ?>
				  </label>
				  <?php } ?>
				  <div class="desc"><?php esc_html_e( $option[ 'desc' ], 'riva_slider_pro' ); ?></div>
				</div>
				<div class="clear"></div>
			      </div>
			      <?php break;
			      
			      case 'select' : ?>
			      <div class="rs-option">
				<div class="rs-first-column">
				  <label for="<?php echo esc_attr( $option[ 'id' ] ); ?>-<?php echo esc_attr( $value[ 'id' ] ); ?>"><?php esc_html_e( $option[ 'name' ], 'riva_slider_pro' ); ?></label>
				</div>
				<div class="rs-last-column">
				  <select name="<?php echo esc_attr( $option[ 'id' ] ); ?>-<?php echo esc_attr( $value[ 'id' ] ); ?>" id="<?php echo esc_attr( $option[ 'id' ] ); ?>-<?php echo esc_attr( $value[ 'id' ] ); ?>">
				    <?php foreach ( $option[ 'options' ] as $choice ) { ?>
				    <option value="<?php echo esc_attr( $choice ); ?>" <?php if ( isset( $value[ $option[ 'id' ] ] ) ) { selected( $choice, $value[ $option[ 'id' ] ] ); } else { selected( $choice, $option[ 'std' ] ); } ?>><?php esc_html_e( ucwords( str_replace( '_', ' ', $choice ) ) ); ?></option>
				    <?php } ?>
				  </select>
				  <div class="desc"><?php esc_html_e( $option[ 'desc' ], 'riva_slider_pro' ); ?></div>
				</div>
				<div class="clear"></div>
			      </div>
			      <?php break;
			    
			      case 'text' : ?>
			      <div class="rs-option">
				<div class="rs-first-column">
				  <label for="<?php echo esc_attr( $option[ 'id' ] ); ?>-<?php echo esc_attr( $value[ 'id' ] ); ?>"><?php esc_html_e( $option[ 'name' ], 'riva_slider_pro' ); ?></label>
				</div>
				<div class="rs-last-column">
				  <input type="text" name="<?php echo esc_attr( $option[ 'id' ] ); ?>-<?php echo esc_attr( $value[ 'id' ] ); ?>" id="<?php echo esc_attr( $option[ 'id' ] ); ?>-<?php echo esc_attr( $value[ 'id' ] ); ?>" <?php if ( isset( $option[ 'small' ] ) && $option[ 'small' ] == 'true' ) echo 'class="small int" style="margin-left: 0px"'; ?> value="<?php if ( isset( $value[ $option[ 'id' ] ] ) ) { echo esc_attr( $value[ $option[ 'id' ] ] ); } else { echo esc_attr( $option[ 'std' ] ); } ?>" />
				  <div class="desc"><?php esc_html_e( $option[ 'desc' ], 'riva_slider_pro' ); ?></div>
				</div>
				<div class="clear"></div>
			      </div>
			      <?php break;
			    
			      case 'text-image' : ?>
			      <div class="rs-option">
				<div class="rs-first-column">
				  <label for="<?php echo esc_attr( $option[ 'id' ] ); ?>-<?php echo esc_attr( $value[ 'id' ] ); ?>"><?php esc_html_e( $option[ 'name' ], 'riva_slider_pro' ); ?></label>
				</div>
				<div class="rs-last-column">
				  <input type="text" name="<?php echo esc_attr( $option[ 'id' ] ); ?>-<?php echo esc_attr( $value[ 'id' ] ); ?>" id="<?php echo esc_attr( $option[ 'id' ] ); ?>-<?php echo esc_attr( $value[ 'id' ] ); ?>" value="<?php if ( isset( $value[ $option[ 'id' ] ] ) ) { echo esc_attr( $value[ $option[ 'id' ] ] ); } else { echo esc_attr( $option[ 'std' ] ); } ?>" />
				  <a href="media-upload.php?post_id=0&amp;tab=riva_slider_pro_tab&amp;change=true&amp;riva_id=<?php echo esc_attr( $value[ 'id' ] ); ?>&amp;TB_iframe=true" class="button-secondary change-image thickbox" title="<?php _e( 'Change image #'. $value[ 'id' ] .'', 'riva_slider_pro' ); ?>"><?php _e( 'Change image', 'riva_slider_pro' ); ?></a>
				  <div class="desc" style="margin-top: 6px;"><?php esc_html_e( $option[ 'desc' ], 'riva_slider_pro' ); ?></div>
				</div>
				<div class="clear"></div>
			      </div>
			      <?php break;
			    
			      case 'text-hash' : ?>
			      <div class="rs-option">
				<div class="rs-first-column">
				  <label for="<?php echo esc_attr( $option[ 'id' ] ); ?>-<?php echo esc_attr( $value[ 'id' ] ); ?>"><?php esc_html_e( $option[ 'name' ], 'riva_slider_pro' ); ?></label>
				</div>
				<div class="rs-last-column">
				  <div class="rs-color-picker-holder">
				    #<input type="text" name="<?php echo esc_attr( $option[ 'id' ] ); ?>-<?php echo esc_attr( $value[ 'id' ] ); ?>" id="<?php echo esc_attr( $option[ 'id' ] ); ?>-<?php echo esc_attr( $value[ 'id' ] ); ?>" class="small hex" value="<?php if ( isset( $value[ $option[ 'id' ] ] ) ) { echo esc_attr( $value[ $option[ 'id' ] ] ); } else { echo esc_attr( $option[ 'std' ] ); } ?>" /><div class="rs-color-picker"></div>
				  </div>
				  <div class="desc"><?php esc_html_e( $option[ 'desc' ], 'riva_slider_pro' ); ?></div>
				</div>
				<div class="clear"></div>
			      </div>
			      <?php break;
			    
			      case 'textarea' : ?>
			      <div class="rs-option">
				<div class="rs-first-column">
				  <label for="<?php echo esc_attr( $option[ 'id' ] ); ?>-<?php echo esc_attr( $value[ 'id' ] ); ?>"><?php esc_html_e( $option[ 'name' ], 'riva_slider_pro' ); ?></label>
				</div>
				<div class="rs-last-column">
				  <textarea name="<?php echo esc_attr( $option[ 'id' ] ); ?>-<?php echo esc_attr( $value[ 'id' ] ); ?>" id="<?php echo esc_attr( $option[ 'id' ] ); ?>-<?php echo esc_attr( $value[ 'id' ] ); ?>"><?php if ( isset( $value[ $option[ 'id' ] ] ) ) { echo esc_html( $value[ $option[ 'id' ] ] ); } else { echo esc_html( $option[ 'std' ] ); } ?></textarea>
				  <div class="desc"><?php esc_html_e( $option[ 'desc' ], 'riva_slider_pro' ); ?></div>
				</div>
				<div class="clear"></div>
			      </div>
			      <?php break;
			      
			    }
			  } ?>
			</div>
			<input type="hidden" name="image-resized-<?php echo esc_attr( $value[ 'id' ], 'riva_slider_pro' ); ?>" id="image-resized-<?php echo esc_attr( $value[ 'id' ], 'riva_slider_pro' ); ?>" value="<?php echo esc_attr( $value[ 'image-resized' ], 'riva_slider_pro' ); ?>" />
			<input type="hidden" name="image-id[]" id="image-id-<?php echo esc_attr( $value[ 'id' ], 'riva_slider_pro' ); ?>" value="<?php echo esc_attr( $value[ 'id' ], 'riva_slider_pro' ); ?>" />
		      </div>
		    </div>
		  <?php $image++; } ?>
		<?php } ?>
		
		</div>
	      </td>
	  
	      <!-- Right hand column -->
	      <td valign="top" align="right" class="widget-liquid-right">
		<div id="widgets-right">
		  
		  <!-- Manage Images -->
		  <div class="rs-holder-wrap" <?php if ( isset( $slideshow[ 'image-source' ] ) && $slideshow[ 'image-source' ] == 'images_from_posts' ) echo 'style="display: none;"'; ?>>
		    <!-- Widget title -->
		    <div class="sidebar-name">
		      <h3><?php _e( 'Manage Images', 'riva_slider_pro' ); ?></h3>
		    </div>
		    <!-- Widget box content -->
		    <div id="manage-images" class="widgets-sortables">
		      <div class="rs-holder-content">
			<a href="<?php echo esc_url( 'media-upload.php?post_id=0&amp;tab=riva_slider_pro_tab&amp;TB_iframe=true' ); ?>" id="add-a-new-image" class="button-secondary thickbox" title="<?php _e( 'Add image to slideshow', 'riva_slider_pro' ); ?>"><?php _e( 'Add a new image', 'riva_slider_pro' ); ?></a>
			<a href="javascript: jQuery.rivaDeleteImages();" id="delete-all-images" class="button-secondary" title="<?php _e( 'Delete all slideshows images', 'riva_slider_pro' ); ?>"><?php _e( 'Delete all images', 'riva_slider_pro' ); ?></a>
		      </div> 
		    </div>
		  </div>
		  
		  <!-- Image source -->
		  <div class="rs-holder-wrap">
		    <!-- Widget title -->
		    <div class="sidebar-name">
		      <h3><?php _e( 'Change Image Source', 'riva_slider_pro' ); ?></h3>
		    </div>
		    <!-- Widget box content -->
		    <div id="image-source" class="widgets-sortables">
		      <div class="rs-holder-content">
			<select name="rs-image-source">
			  <option value="this_panel" <?php if ( isset( $slideshow[ 'image-source' ] ) ) selected( 'this_panel', $slideshow[ 'image-source' ] ); else echo 'selected="selected"'; ?>><?php esc_html_e( 'This Panel', 'riva_slider_pro' ); ?></option>
			  <option value="images_from_posts" <?php if ( isset( $slideshow[ 'image-source' ] ) ) selected( 'images_from_posts', $slideshow[ 'image-source' ] ); ?>><?php esc_html_e( 'Images From Posts', 'riva_slider_pro' ); ?></option>
			</select>
			<select name="rs-image-category" <?php if ( ( isset( $slideshow[ 'image-source' ] ) && $slideshow[ 'image-source' ] == 'this_panel' ) || ( is_null( $id ) ) ) { ?>style="display: none;"<?php } ?>>
			  <?php foreach( riva_slider_pro_categories() as $cat ) { ?>
			  <option value="<?php echo esc_attr( $cat[ 'id' ] .', '. $cat[ 'images' ] ); ?>" <?php if ( isset( $slideshow[ 'image-category' ] ) ) selected( $cat[ 'id' ], $slideshow[ 'image-category' ] ); elseif ( $cat[ 'id' ] == 0 ) echo 'selected="selected"'; ?>><?php echo ucwords( str_replace( '_', ' ', $cat[ 'name' ] ) ); ?></option>
			  <?php } ?>
			</select>
			<div id="max-images" <?php if ( ( isset( $slideshow[ 'image-source' ] ) && $slideshow[ 'image-source' ] == 'this_panel' ) || ( is_null( $id ) ) ) { ?>style="display: none;"<?php } ?>><?php _e( '<b>Display a maximum of</b>', 'riva_slider_pro' ); ?><input type="text" name="rs-max-images" id="rs-max-images" class="small int" value="<?php if ( isset( $slideshow[ 'max-images' ] ) ) echo $slideshow[ 'max-images' ]; else echo '10'; ?>" /><?php _e( '<b>images.</b>', 'riva_slider_pro' ); ?></div>
			<div class="desc" style="margin: 5px 0px 10px 0px;"><?php _e( 'Select a source to get the images from. Setting the slideshow to get "Images from Posts" will disable this images panel.', 'riva_slider_pro' ); ?></div>
		      </div> 
		    </div>
		  </div>
		  
		</div>
	      </td>
	    </tr>
	  </tbody>
	</table>
	<?php break;
	
      
	case 'slideshow-interface' : ?>
	<form id="riva_slider_pro_slideshows_list" method="post">
	<div class="tablenav top">
	  <select name="action" id="selectaction">
	    <option value="-1" selected="selected"><?php _e( 'Bulk Actions', 'riva_slider_pro' ); ?></option>
	    <option value="delete"><?php _e( 'Delete', 'riva_slider_pro' ); ?></option>
	  </select>
	  <input type="submit" id="doaction" name="doaction" class="button-secondary action" value="<?php _e( 'Apply', 'riva_slider_pro' ); ?>" />
	  <input type="hidden" name="security" value="<?php echo esc_attr( $ajax_nonce ); ?>" />
	  <img src="<?php bloginfo( 'wpurl' ); ?>/wp-admin/images/wpspin_light.gif" class="ajax-loading" id="loading" style="position: relative; top: 4px; left: -5px;">
	</div>
	  <table class="widefat" id="rs-list-slideshows" cellspacing="0">
	    <thead>
	      <tr>
		<!-- Checkbox -->
		<th scope="col" id="cb" class="manage-column check-column"><input type="checkbox" /></th>
		<!-- Display the slideshows ID -->
		<th scope="col" id="id" class="manage-column"><?php esc_html_e( 'ID', 'riva_slider_pro' ); ?></th>
		<!-- And its title & description -->
		<th scope="col" id="title" class="manage-column <?php if ( $orderby == 'name' ) { echo 'sorted'; } else { echo 'sortable'; } if ( esc_attr( $order ) == 'asc' ) { echo ' asc'; } else { echo ' desc'; } ?>">
		  <a href="admin.php?page=<?php echo esc_attr( $permalink[ 'slideshows' ] ); ?>&amp;orderby=name&amp;order=<?php if ( esc_attr( $order ) == 'asc' ) { echo 'desc'; } else { echo 'asc'; } ?>">
		    <span><?php esc_html_e( 'Name', 'riva_slider_pro' ); ?></span>
		    <span class="sorting-indicator"></span>
		  </a>
		</th>
	      </tr>
	    </thead>
	    <tbody>
	      <?php foreach ( $slideshows as $value ) { ?>
	      <tr>
		<th scope="row" class="check-column multi-column"><input type="checkbox" name="bulkid[]" value="<?php echo esc_attr( $value[ 'index' ] ); ?>" /></th>
		<td colspan="1" id="<?php echo esc_attr( $value[ 'index' ] ); ?>"><?php esc_html_e( $value[ 'index' ], 'riva_slider_pro' ); ?></td>
		<td colspan="1">
		  <strong>
		    <a href="admin.php?page=<?php echo esc_attr( $permalink[ 'slideshows' ] ); ?>&amp;id=<?php echo esc_attr( $value[ 'index' ] ); ?>" class="row-title" title="<?php esc_html_e( 'Edit this slideshow', 'riva_slider_pro' ); ?>">
		      <?php esc_html_e( $value[ 'name' ], 'riva_slider_pro' ); ?>
		    </a>
		  </strong>
		  <div class="row-actions">
		    <span class="edit">
		      <a href="admin.php?page=<?php echo esc_attr( $permalink[ 'slideshows' ] ); ?>&amp;id=<?php echo esc_attr( $value[ 'index' ] ); ?>" title="<?php esc_html_e( 'Edit this slideshow', 'riva_slider_pro' ); ?>">
			<?php esc_html_e( 'Edit', 'riva_slider_pro' ); ?>
		      </a>| 
		    </span>
		    <span class="edit">
		      <a href="admin.php?page=<?php echo esc_attr( $permalink[ 'slideshows' ] ); ?>&amp;duplicate=<?php echo esc_attr( $value[ 'index' ] ); ?>&amp;security=<?php echo esc_attr( $ajax_nonce ); ?>" class="submitduplicate" title="<?php esc_html_e( 'Duplicate this slideshow', 'riva_slider_pro' ); ?>">
			<?php esc_html_e( 'Duplicate', 'riva_slider_pro' ); ?>
		      </a>| 
		    </span>
		    <span class="trash">
		      <a href="admin.php?page=<?php echo esc_attr( $permalink[ 'slideshows' ] ); ?>&amp;delete=<?php echo esc_attr( $value[ 'index' ] ); ?>&amp;security=<?php echo esc_attr( $ajax_nonce ); ?>" class="submitdelete" title="<?php esc_html_e( 'Delete this slideshow', 'riva_slider_pro' ); ?>">
			<?php esc_html_e( 'Delete', 'riva_slider_pro' ); ?>
		      </a>
		    </span>
		  </div>
		</td>
	      </tr>
	      <?php } ?>
	    </tbody>
	    <tfoot>
	      <tr>
		<!-- Checkbox -->
		<th scope="col" id="cb" class="check-column"><input type="checkbox" /></th>
		<!-- Display the slideshows ID -->
		<th scope="col" id="id"><?php esc_html_e( 'ID', 'riva_slider_pro' ); ?></th>
		<!-- And its title & description -->
		<th scope="col" id="title" class="manage-column <?php if ( $orderby == 'name' ) { echo 'sorted'; } else { echo 'sortable'; } if ( esc_attr( $order ) == 'asc' ) { echo ' asc'; } else { echo ' desc'; } ?>">
		  <a href="admin.php?page=<?php echo esc_attr( $permalink[ 'slideshows' ] ); ?>&amp;orderby=name&amp;order=<?php if ( esc_attr( $order ) == 'asc' ) { echo 'desc'; } else { echo 'asc'; } ?>">
		    <span><?php esc_html_e( 'Name', 'riva_slider_pro' ); ?></span>
		    <span class="sorting-indicator"></span>
		  </a>
		</th>
	      </tr>
	    </tfoot>
	  </table>
	</form>
	<?php break;
	
	
      }
    } ?>
  </div><!-- END .wrap --><?php
  } else {
    echo '<div class="error">';
      if ( !version_compare( $wp_version, '3.0', '>=' ) ) {
	// Display error message if Wordpress version is too low.
	_e( '<p><strong>Wordpress version is too low. Please upgrade to 3.0 or higher.</strong></p>', 'riva_slider_pro' );
      }
    echo '</div>';
  }
}

?>
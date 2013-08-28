<?php

function riva_slider_pro_metabox() {
    
    global $theme_name;
    
    if ( function_exists( 'add_meta_box' ) && function_exists( 'riva_slider_pro_check_caps' ) && riva_slider_pro_check_caps( 'rivasliderpro_view_metabox' ) ) {
        add_meta_box( 'riva-slider-pro-metabox', 'Slideshow Image', 'riva_slider_pro_metabox_code', 'post', 'side', 'high', array( 'options' => true ) );
        add_meta_box( 'riva-slider-pro-metabox', 'Insert Slideshow', 'riva_slider_pro_metabox_code', 'page', 'side', 'high', array( 'options' => false ) );
    }
    
}

function riva_slider_pro_metabox_code( $post, $metabox ) {
    
    global $post;

    $options = riva_slider_pro_options( 'meta' );
    $data = get_post_meta( $post->ID, '_rivasliderpro', true );
    $info = riva_slider_pro_info();
    $slideshows = get_option( $info[ 'shortname' ] .'_slideshows' ); ?>
    
    <div id="riva-slider-meta">
    
    <?php if ( $metabox[ 'args' ][ 'options' ] == true ) : ?>
    <input type="hidden" name="rs-nonce" id="rs-nonce" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />
    
    <div class="misc-pub-section" style="border-top-width: 0px; padding: 5px 0px;">
        <div class="rs-image-meta">
            <?php if ( isset( $data[ 'image-resized' ] ) && isset( $data[ 'image-url' ] ) && $data[ 'image-resized' ] != '' && $data[ 'image-url' ] != '' ) { ?><img src="<?php echo esc_attr( $data[ 'image-resized' ] ); ?>" width="258" height="86" style="margin-bottom: 5px;" /><?php } else { ?><center><strong id="rs-choose-image"><?php _e( 'Choose an image', 'riva_slider_pro' ); ?></strong></center><?php } ?>
        </div>
        <?php if ( function_exists( 'riva_slider_pro_check_caps' ) && riva_slider_pro_check_caps( 'rivasliderpro_edit_metabox' ) ) { ?>
        <div style="margin: 10px 0px 6px 0px;">
            <center><a href="media-upload.php?post_id=0&amp;tab=riva_slider_pro_tab&amp;TB_iframe=true" class="button-secondary thickbox" title="Choose an image"><?php _e( 'Insert an image', 'riva_slider_pro' ); ?></a></center>
        </div>
        <?php } ?>
    </div>
    
    <?php if ( function_exists( 'riva_slider_pro_check_caps' ) && riva_slider_pro_check_caps( 'rivasliderpro_edit_metabox' ) ) {
    foreach ( $options as $value ) {
        
        switch ( $value[ 'type' ] ) {
            
            case 'tabs' : ?>
                <div class="misc-pub-section" style="padding: 0px 0px 12px 0px;">
                    <ul id="rs-meta-navigation">
                        <?php foreach ( $options as $type ) {
                            if ( $type[ 'type' ] == 'div' && $type[ 'state' ] == 'open' ) { ?>
                            <li><a onClick="javascript: jQuery.rivaMetaTab('<?php echo esc_attr( $type[ 'id' ] ); ?>');" id="<?php echo esc_attr( $type[ 'id' ] ); ?>_link"><?php echo esc_attr( $type[ 'name' ] ); ?></a></li>
                            <?php }
                        } ?>
                    </ul>
                    <div class="clear"></div>
                </div>
            <?php break;
            
            case 'div' :
                
                if ( $value[ 'state' ] == 'open' ) { ?>
                <div id="<?php echo esc_attr( $value[ 'id' ] ); ?>" class="rs-meta-box" style="display: none;">
                <?php }
                
                elseif ( $value[ 'state' ] == 'close' ) { ?>
                </div>
                <?php } ?>
                
            <?php break;
            
            case 'hidden' : ?>
                <input type="hidden" name="<?php echo esc_attr( $value[ 'id' ] ); ?>" id="<?php echo esc_attr( $value[ 'id' ] ); ?>" value="<?php if ( $data[ $value[ 'id' ] ] != '' ) { echo esc_attr( $data[ $value[ 'id' ] ] ); } else { echo $value[ 'std' ]; } ?>" />
            <?php break;
            
            case 'text' : ?>
                <div class="misc-pub-section rs-<?php echo esc_attr( $value[ 'id' ] ); ?>">
                    <label for="<?php echo esc_attr( $value[ 'id' ] ); ?>" class="rs-label"><?php echo esc_attr( $value[ 'name' ] ); ?></label>
                    <input type="text" <?php if ( isset( $value[ 'int' ] ) && $value[ 'int' ] ) { ?>class="small"<?php } ?> name="<?php echo esc_attr( $value[ 'id' ] ); ?>" id="<?php echo esc_attr( $value[ 'id' ] ); ?>" value="<?php if ( isset( $data[ $value[ 'id' ] ] ) && $data[ $value[ 'id' ] ] != '' ) { echo esc_attr( $data[ $value[ 'id' ] ] ); } else { echo $value[ 'std' ]; } ?>" />
                    <div class="desc" style="line-height: 1.4;"><?php esc_html_e( $value[ 'desc' ], 'riva_slider_pro' ); ?></div>
                </div>
            <?php break;
            
            case 'text-hash' : ?>
                <div class="misc-pub-section rs-<?php echo esc_attr( $value[ 'id' ] ); ?>">
                    <label for="<?php echo esc_attr( $value[ 'id' ] ); ?>" class="rs-label"><?php echo esc_attr( $value[ 'name' ] ); ?></label>
                    #<input type="text" name="<?php echo esc_attr( $value[ 'id' ] ); ?>" class="small" id="<?php echo esc_attr( $value[ 'id' ] ); ?>" value="<?php if ( isset( $data[ $value[ 'id' ] ] ) && $data[ $value[ 'id' ] ] != '' ) { echo esc_attr( $data[ $value[ 'id' ] ] ); } else { echo $value[ 'std' ]; } ?>" />
                    <div class="desc" style="line-height: 1.4;"><?php esc_html_e( $value[ 'desc' ], 'riva_slider_pro' ); ?></div>
                </div>
            <?php break;
            
            case 'textarea' : ?>
                <div class="misc-pub-section rs-<?php echo esc_attr( $value[ 'id' ] ); ?>">
                    <label for="<?php echo esc_attr( $value[ 'id' ] ); ?>" class="rs-label"><?php echo esc_attr( $value[ 'name' ] ); ?></label>
                    <textarea name="<?php echo esc_attr( $value[ 'id' ] ); ?>" id="<?php echo esc_attr( $value[ 'id' ] ); ?>"><?php if ( isset( $data[ $value[ 'id' ] ] ) &&  $data[ $value[ 'id' ] ] != '' ) { echo esc_attr( $data[ $value[ 'id' ] ] ); } else { echo $value[ 'std' ]; } ?></textarea>
                    <div class="desc" style="line-height: 1.4;"><?php esc_html_e( $value[ 'desc' ], 'riva_slider_pro' ); ?></div>
                </div>
            <?php break;
            
            case 'select' : ?>
                <div class="misc-pub-section rs-<?php echo esc_attr( $value[ 'id' ] ); ?>">
                    <label for="<?php echo esc_attr( $value[ 'id' ] ); ?>" class="rs-label"><?php echo esc_attr( $value[ 'name' ] ); ?></label>
                    <select name="<?php echo esc_attr( $value[ 'id' ] ); ?>" id="<?php echo esc_attr( $value[ 'id' ] ); ?>">
                    <?php foreach ( $value[ 'options' ] as $option ) { ?>
                        <option value="<?php echo esc_attr( $option ); ?>" <?php if ( isset( $data[ $value[ 'id' ] ] ) &&  $data[ $value[ 'id' ] ] != '' ) { selected( $data[ $value[ 'id' ] ], $option ); } else { selected( $value[ 'std' ], $option ); } ?>><?php echo esc_html( ucwords( str_replace( '_', ' ', $option ) ) ); ?></option>
                    <?php } ?>
                    </select>
                    <div class="desc" style="line-height: 1.4;"><?php esc_html_e( $value[ 'desc' ], 'riva_slider_pro' ); ?></div>
                </div>
            <?php break;
            
            case 'radio' : ?>
                <div class="misc-pub-section rs-<?php echo esc_attr( $value[ 'id' ] ); ?>">
                    <span class="rs-label"><?php echo esc_attr( $value[ 'name' ] ); ?></span>
                    <?php foreach ( $value[ 'options' ] as $option ) { ?>
                    <label for="<?php echo esc_attr( $value[ 'id' ] .'-'. $option ); ?>"><?php echo ucwords( esc_attr( $option ) ); ?>
                    <input type="radio" name="<?php echo esc_attr( $value[ 'id' ] ); ?>" id="<?php echo esc_attr( $value[ 'id' ] .'-'. $option ); ?>" value="<?php echo esc_attr( $option ); ?>" <?php if ( isset( $data[ $value[ 'id' ] ] ) &&  $data[ $value[ 'id' ] ] != '' ) { checked( $data[ $value[ 'id' ] ], $option ); } else { checked( $value[ 'std' ], $option ); } ?> />
                    </label>
                    <?php } ?>
                    <div class="desc" style="line-height: 1.4;"><?php esc_html_e( $value[ 'desc' ], 'riva_slider_pro' ); ?></div>
                </div>
            <?php break;
            
            case 'checkbox' : ?>
                <div class="misc-pub-section rs-<?php echo esc_attr( $value[ 'id' ] ); ?>">
                    <label for="<?php echo esc_attr( $value[ 'id' ] ); ?>">
                    <input type="checkbox" name="<?php echo esc_attr( $value[ 'id' ] ); ?>" id="<?php echo esc_attr( $value[ 'id' ] ); ?>" value="<?php echo esc_attr( $value[ 'std' ] ); ?>" <?php if ( isset( $data[ $value[ 'id' ] ] ) && $data[ $value[ 'id' ] ] != '' ) { checked( $value[ 'std' ], $data[ $value[ 'id' ] ] ); } ?> /><?php esc_html_e( $value[ 'desc' ] ); ?>
                    </label>
                </div>
            <?php break;
            
        }
        
    }
    } endif; ?>
    
    <div class="misc-pub-section" style="<?php if ( $metabox[ 'args' ][ 'options' ] == false ) echo 'border-top-width: 0px; '; ?>border-bottom-width: 0px; padding: 5px 0px 2px 0px;">
        <p>
            <div class="desc-slideshow"><?php _e( 'Use the button below to add a slideshow to this post.', 'riva_slider_pro' ); ?></div>
            <select name="rs-select-slideshow" id="rs-select-slideshow">
            <?php foreach ( $slideshows as $slideshow ) { ?>
                <option value="<?php echo esc_attr( $slideshow[ 'index' ] ); ?>"><?php echo esc_html( $slideshow[ 'index' ] .': '. $slideshow[ 'name' ] ); ?></option>
            <?php } ?>
            </select>
            <center><div id="rs-insert-slideshow"><input type="button" onClick="javascript: jQuery.rivaEditor('rs-select-slideshow');" class="button-primary" value="<?php _e( 'Insert a slideshow', 'riva_slider_pro' ); ?>" /></div></center>
        </p>
    </div>
    
    </div>
    
<?php }

function riva_slider_pro_meta_save( $post_id ) {
    
    if ( function_exists( 'riva_slider_pro_check_caps' ) && riva_slider_pro_check_caps( array( 'rivasliderpro_view_metabox' , 'rivasliderpro_edit_metabox' ) ) ) :
    
    global $post;
    
    $options = riva_slider_pro_options( 'meta' );
    
    if ( isset( $_POST[ 'rs-nonce' ] ) && !wp_verify_nonce( $_POST[ 'rs-nonce' ], basename(__FILE__) ) )
        return $post_id;
      
    if ( !current_user_can( 'edit_post', $post_id ) )   
        return $post_id;
    
    $data = $_POST;
    $values = array();
    
    foreach ( $options as $value ) {
        if ( isset( $value[ 'id' ] ) && isset( $data[ $value[ 'id' ] ] ) && $data[ $value[ 'id' ] ] != '' )
            $values = array_merge( array( $value[ 'id' ] => $data[ $value[ 'id' ] ] ), $values );
    }
    
    if ( get_post_meta( $post_id, '_rivasliderpro' ) == '' )
        add_post_meta( $post_id, '_rivasliderpro', $values, true );
    
    elseif ( $values != get_post_meta( $post_id, '_rivasliderpro', true ) )
        update_post_meta( $post_id, '_rivasliderpro', $values ) ;
    
    elseif ( $values == '' )
        delete_post_meta( $post_id, '_rivasliderpro', get_post_meta( $post_id, '_rivasliderpro', true ) );
     
    endif;   
}

add_action( 'admin_menu', 'riva_slider_pro_metabox' );
add_action( 'save_post', 'riva_slider_pro_meta_save' );

?>
<?php

/*
    Function used to save a slideshow
*/
function riva_slider_pro_save_slideshow() {
    
    // Establish important variables
    $info = riva_slider_pro_info();
    $permalink = $info[ 'permalink' ];
    $data = $_REQUEST;
    
    if ( !wp_verify_nonce( $data[ 'security' ], 'riva-slider-nonce' ) )
        return false;
    
    $id = $data[ 'index' ];
    $options = riva_slider_pro_options( 'edit', $id );
    $image_options = riva_slider_pro_options( 'image' );
        
    // Get slideshows & auto increment
    $slideshows = get_option( $info[ 'shortname' ] .'_slideshows' );
    $auto_increment = get_option( $info[ 'shortname' ] .'_auto_increment' );
    
    // Check to make sure a slideshow name has been set before doing anything
    if ( $data[ 'name' ] != '' ) {
        if ( isset( $data[ 'import' ] ) && $data[ 'import' ] == '' &&
            ( ( riva_slider_pro_uri( $permalink[ 'slideshows' ] ) && riva_slider_pro_check_caps( 'rivasliderpro_edit_slideshows' ) ) ||
            riva_slider_pro_uri( $permalink[ 'new_slideshow' ] ) && riva_slider_pro_check_caps( 'rivasliderpro_edit_addnew' ) ) ) {
            
            // Find the ID's array key
            if ( riva_slider_pro_search( $slideshows, 'index', $id, 'array' ) != false )
                $id = riva_slider_pro_search( $slideshows, 'index', $id, 'id' );
            else
                $id = count( $slideshows );
                
            if ( isset ( $slideshows[ $id ] ) && riva_slider_pro_check_caps( 'rivasliderpro_edit_slideshows' ) ) {
            
                // Get the appropriate slideshow
                $slideshow = $slideshows[ $id ];
                
                // Edit the old options
                foreach ( $options as $value ) {
                    if ( $value[ 'type' ] == 'checkbox' ) {
                        if ( isset ( $value[ 'id' ] ) && isset ( $data[ $value[ 'id' ] ] ) )
                            $slideshow[ $value[ 'id' ] ] = stripslashes( $data[ $value[ 'id' ] ] );
                        elseif ( isset ( $value[ 'id' ] ) && !isset ( $data[ $value[ 'id' ] ] ) )
                            $slideshow[ $value[ 'id' ] ] = '';
                    }
                    elseif ( isset ( $value[ 'id' ] ) && isset ( $data[ $value[ 'id' ] ] ) && $value[ 'id' ] != 'edit_section' ) {
                        if ( !is_array( $data[ $value[ 'id' ] ] ) ) {
                            $slideshow[ $value[ 'id' ] ] = stripslashes( $data[ $value[ 'id' ] ] );
                        }
                        else
                            $slideshow[ $value[ 'id' ] ] = $data[ $value[ 'id' ] ];
                    }
                }
                
                // Image source value & category
                $slideshow[ 'image-source' ] = $data[ 'rs-image-source' ];
                $slideshow[ 'image-category' ] = explode( ', ', $data[ 'rs-image-category' ] );
                $slideshow[ 'image-category' ] = $slideshow[ 'image-category' ][ 0 ];
                $slideshow[ 'max-images' ] = $data[ 'rs-max-images' ];
                
                // Clear the previous images array
                $slideshow[ 'images' ] = array();
                
                // Now its time to add the images
                if ( isset( $data[ 'image-id' ] ) ) {
                    $image_ids = $data[ 'image-id' ];
                    foreach ( $image_ids as $image_id ) {
                        $image = array(
                            'image-resized' => $data[ "image-resized-$image_id" ],
                            'id' => $image_id
                        );
                        foreach ( $image_options as $option ) {
                            if ( isset( $option[ 'id' ] ) && $option[ 'type' ] != 'div' ) {
                                $new_option = array( $option[ 'id' ] => stripslashes( $data[ $option[ 'id' ]. '-' .$image_id ] ) );
                                $image = array_merge( $new_option, $image );
                            }
                        }
                        $slideshow[ 'images' ] = array_merge( $slideshow[ 'images' ], array( $image ) );
                    }
                }
                
                // Update the image auto increment
                $auto_increment[ $id+1 ] = intval( $data[ 'image-increment' ] );
                $auto_increment = array_map( 'esc_sql', $auto_increment );
                update_option( $info[ 'shortname' ] .'_auto_increment', $auto_increment );
                
                $die = 'saved';
                
            } elseif ( !isset ( $slideshow[ $id ] ) && riva_slider_pro_check_caps( 'rivasliderpro_edit_addnew' ) ) {
                
                // Create the new array
                $slideshow = array( 'index' => $auto_increment[ 0 ] );
                
                // Merge the requested values the user has entered for the new slideshow
                foreach ( $options as $value ) {
                    if ( $value[ 'type' ] == 'checkbox' ) {
                        if ( isset ( $value[ 'id' ] ) && isset ( $data[ $value[ 'id' ] ] ) )
                            $option = array( $value[ 'id' ] => stripslashes( $data[ $value[ 'id' ] ] ) );
                        elseif ( isset ( $value[ 'id' ] ) && !isset ( $data[ $value[ 'id' ] ] ) )
                            $option = array( $value[ 'id' ] => '' );
                    }
                    elseif ( isset ( $value[ 'id' ] ) && isset ( $data[ $value[ 'id' ] ] ) && $value[ 'id' ] != 'edit_section' ) {
                        if ( !is_array( $data[ $value[ 'id' ] ] ) )
                            $option = array( $value[ 'id' ] => stripslashes( $data[ $value[ 'id' ] ] ) );
                        else
                            $slideshow[ $value[ 'id' ] ] = $data[ $value[ 'id' ] ];
                        $slideshow = array_merge( $slideshow, $option );
                    }
                }
                
                // Image source value
                $slideshow[ 'image-source' ] = $data[ 'rs-image-source' ];
                $slideshow[ 'image-category' ] = explode( ', ', $data[ 'rs-image-category' ] );
                $slideshow[ 'image-category' ] = $slideshow[ 'image-category' ][ 0 ];
                $slideshow[ 'max-images' ] = $data[ 'rs-max-images' ];
                
                // Create the slideshow images variable
                $slideshow[ 'images' ] = array();
                
                // Now its time to add the images
                if ( isset( $data[ 'image-id' ] ) ) {
                    $image_ids = $data[ 'image-id' ];
                    foreach ( $image_ids as $image_id ) {
                        $image = array(
                            'image-resized' => $data[ "image-resized-$image_id" ],
                            'id' => $image_id
                        );
                        foreach ( $image_options as $option ) {
                            if ( isset( $option[ 'int' ] ) && $option[ 'int' ] == true && is_numeric( $data[ $option[ 'id' ]. '-' .$image_id ] ) == false ) {
                                return false;
                            } elseif ( isset( $option[ 'id' ] ) && $option[ 'type' ] != 'div' ) {
                                $new_option = array( $option[ 'id' ] => stripslashes( $data[ $option[ 'id' ]. '-' . $image_id ] ) );
                                $image = array_merge( $new_option, $image );
                            }
                        }
                        $slideshow[ 'images' ] = array_merge( $slideshow[ 'images' ], array( $image ) );
                    }
                }
                
                // Update slideshow & image auto increments
                $auto_increment[ 0 ] = intval( $auto_increment[ 0 ]+1 );
                $auto_increment[ $id+1 ] = intval( $data[ 'image-increment' ] );
                $auto_increment = array_map( 'esc_sql', $auto_increment );
                update_option( $info[ 'shortname' ] .'_auto_increment', $auto_increment );
                
                $die = 'created';
                
            }
            
            // Update $slideshows with updated slideshow
            $slideshows[ $id ] = $slideshow;
            
            // Sanitize array
            $slideshows = array_map( 'esc_sql', $slideshows );
            $slideshows = array_map( 'stripslashes_deep', $slideshows );
            
            update_option( $info[ 'shortname' ] .'_slideshows', $slideshows );
        
            // Generate dynamic CSS
            if ( function_exists( 'riva_slider_pro_dynamic_css' ) )
                riva_slider_pro_dynamic_css( $refresh = true );
    
        } elseif ( isset( $data[ 'import' ] ) && $data[ 'import' ] != '' ) {
            
            // Find the ID's array key
            if ( riva_slider_pro_search( $slideshows, 'index', $id, 'array' ) != false )
                $id = riva_slider_pro_search( $slideshows, 'index', $id, 'id' );
                
            // If current slideshow exists
            if ( isset( $slideshows[ $id ] ) ) {
                
                $import = $data[ 'import' ];
                $import = base64_decode( $import );
                $import = unserialize( $import );
                
                // If the imported settings are valid
                if ( is_array( $import ) ) {
                    
                    // Don't change the slideshow's name, description & id
                    unset( $import[ 'index' ], $import[ 'name' ], $import[ 'desc' ] );
                    $import[ 'index' ] = $data[ 'index' ];
                    $import[ 'name' ] = $data[ 'name' ];
                    $import[ 'desc' ] = $data[ 'desc' ];
        
                    // Call the update function
                    $import = riva_slider_pro_update_changes( array( $import ), $options, $image_options, true );
                    
                    // Update the slideshow
                    $slideshows[ $id ] = $import;
                    
                    // Sanitize array
                    $slideshows = array_map( 'esc_sql', $slideshows );
                    $slideshows = array_map( 'stripslashes_deep', $slideshows );
                    
                    update_option( $info[ 'shortname' ] .'_slideshows', $slideshows );
                    
                    // Generate dynamic CSS
                    if ( function_exists( 'riva_slider_pro_dynamic_css' ) )
                        riva_slider_pro_dynamic_css( $refresh = true );
                    
                    $die = 'import-success';
                    
                } else {
                    
                    $die = 'import-failed';
                    
                }
                
            }
            
        } else {
            if ( riva_slider_pro_uri( $permalink[ 'slideshows' ] ) ) {
                wp_die( __( 'You do not have sufficient permissions to edit slideshows.', 'riva_slider_pro' ) );
                exit();
            }
            elseif ( riva_slider_pro_uri( $permalink[ 'new_slideshow' ] ) ) {
                wp_die( __( 'You do not have sufficient permissions to create a slideshow.', 'riva_slider_pro' ) );
                exit();
            }
        }
    }
    return $die; 
}





/*
    Function that saves the plugins global settings
*/

function riva_slider_pro_save_global_settings() {
    
    // Establish important variables
    $info = riva_slider_pro_info();
    $data = $_REQUEST;
    
    if ( !wp_verify_nonce( $data[ 'security' ], 'riva-slider-nonce' ) )
        return false;
    
    $options = riva_slider_pro_options( 'new' );
    $image_options = riva_slider_pro_options( 'image' );
    
    if ( riva_slider_pro_check_caps( 'rivasliderpro_edit_global_settings' ) ) {
        if ( $data[ 'import' ] != '' ) {
            
            // Get imported code
            $import = $data[ 'import' ];
            $import = base64_decode( $import );
            $import = unserialize( $import );
            
            // Call the update function
            $import[ 0 ] = riva_slider_pro_update_changes( $import[ 0 ], $options, $image_options );
            
            // Serial code check & save
            if ( get_option( $info[ 'shortname' ] .'_serialcode' ) && $import[ 2 ][ 'serial_code' ] != '' ) {
                if ( riva_slider_pro_check_serial( $import[ 2 ] ) == true )
                    delete_option( $info[ 'shortname' ] .'_serialcode' );
            }
            
            // Sanitize the arrays
            $import = array_map( 'stripslashes_deep', $import );
            
            // Save imported options
            update_option( $info[ 'shortname' ] .'_slideshows', $import[ 0 ] );
            update_option( $info[ 'shortname' ] .'_auto_increment', $import[ 1 ] );
            update_option( $info[ 'shortname' ] .'_global_settings', $import[ 2 ] );
            
            // Generate dynamic CSS
            if ( function_exists( 'riva_slider_pro_dynamic_css' ) )
                riva_slider_pro_dynamic_css( $refresh = true );
                
            $die = 'global-imported';
            
            
        } else {
            
            $options = riva_slider_pro_options( 'global-settings' );
            
            // Create initial array
            $settings = array();
            
            // Add the settings
            foreach ( $options as $value ) {
                if ( isset( $value[ 'std' ] ) ) {
                    $option = array( $value[ 'id' ] => $data[ $value[ 'id' ] ] );
                    $settings = array_merge( $settings, $option );
                }
            }
            
            // Sanitize array
            $settings = array_map( 'esc_sql', $settings );
            
            // Serial code check & save
            if ( get_option( $info[ 'shortname' ] .'_serialcode' ) && $settings[ 'serial_code' ] != '' ) {
                if ( riva_slider_pro_check_serial( $settings ) == true ) {
                    delete_option( $info[ 'shortname' ] .'_serialcode' );
                    update_option( $info[ 'shortname' ] .'_global_settings', $settings );
                } elseif ( riva_slider_pro_check_serial( $settings ) == false ) {
                    $settings[ 'serial_code' ] = '';
                    update_option( $info[ 'shortname' ] .'_global_settings', $settings );
                    return 'serial-invalid';
                }
            } else
                update_option( $info[ 'shortname' ] .'_global_settings', $settings );
            
            // Generate dynamic CSS
            if ( function_exists( 'riva_slider_pro_dynamic_css' ) )
                riva_slider_pro_dynamic_css( $refresh = true );
            
            $die = 'global-saved';
            
        }
    } else {
        wp_die( __( 'You do not have sufficient permissions to edit the Global Settings.', 'riva_slider_pro' ) );
        exit();
    }
    return $die;  
}

?>
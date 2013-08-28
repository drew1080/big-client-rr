<?php

class riva_slider_pro_widget extends WP_Widget {
    
    var $riva_options = array( 'title', 'slideshow' );
    
    function __construct() {
        parent::__construct(
            'riva_slider_pro_widget',
            'RS: Slideshow Widget',
            array( 'description' => __( 'Display a slideshow using a widget.', 'riva_slider_pro' ) )
        );
    }
    
    function widget( $args, $instance ) {
        $args = array_merge( $args, $instance );
        extract( $args );
	$title = apply_filters( 'widget_title', $title );
	
        echo $before_widget;
        
        // Display title
	if ( !empty( $title ) )
	    echo $before_title . $title . $after_title;
        
        // Display slideshow
        if ( !isset( $slideshow ) )
            $slideshow = 1;
        if ( function_exists( 'riva_slider_pro' ) )
                riva_slider_pro( $slideshow );
        else
            _e( '<b>Unable to display the slideshow. Function does not exist.', 'riva_slider_pro' );
        
	echo $after_widget;
    }
    
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        foreach ( $this->riva_options as $value )
            $instance[ $value ] = $new_instance[ $value ];
	return $instance;
    }

    function form( $instance ) {
        extract( $instance );
        $info = riva_slider_pro_info();
        $slideshows = get_option( $info[ 'shortname' ] .'_slideshows' );
        if ( empty( $slideshows ) ) {
            echo '<p>'. __( 'You must create a slideshow before you can display one in a widget!', 'riva_slider_pro' ) .'</p>';
            return;
        }
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'riva_slider_pro' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php if ( isset( $title ) ) echo $title; ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'slideshow' ); ?>"><?php _e( 'Slideshow:', 'riva_slider_pro' ); ?></label> 
        <select class="widefat" id="<?php echo $this->get_field_id( 'slideshow' ); ?>" name="<?php echo $this->get_field_name( 'slideshow' ); ?>">
        <?php
        foreach ( $slideshows as $s ) :
        ?>
            <option value="<?php echo esc_attr( $s[ 'index' ] ); ?>" <?php if ( isset( $slideshow ) ) selected( $slideshow, $s[ 'index' ] ); ?>><?php echo esc_html( $s[ 'name' ] ); ?></option>
        <?php
        endforeach;
        ?>
        </select>
        </p>
        <?php
    }
    
}
add_action( 'widgets_init', create_function( '', 'register_widget( "riva_slider_pro_widget" );' ) );

?>
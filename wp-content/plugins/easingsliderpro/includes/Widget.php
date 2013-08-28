<?php

/**
 * Adds a 'Slideshows' widget to the WordPress widgets interface
 *
 * @author Matthew Ruddy
 * @since 2.0
 */
class ESP_Widget extends WP_Widget {

    /**
     * Constructor
     *
     * @since 2.0
     */
    public function __construct() {
        parent::__construct(
            'easingsliderpro_widget',
            __( 'Slideshow', 'easingsliderpro' ),
            array( 'description' => __( 'Display a slideshow using a widget', 'easingsliderpro' ) )
        );
    }

    /**
     * Widget logic
     *
     * @since 2.0
     */
    public function widget( $args, $instance ) {

        /** Extract arguments */
        extract( $args );

        /** Get widget title */
        $title = apply_filters( 'widgets_title', $instance['title'] );

        /** Display widget header */
        echo $before_widget;
        if ( !empty( $title ) )
            echo $before_title . $title . $after_title;
        
        /** Display slideshow */
        ESP_Slideshow::get_instance()->display_slideshow( $instance['id'] );

        /** Display widget footer */
        echo $after_widget;


    }

    /**
     * Returns updated settings array. Also does some sanatization.
     *
     * @since 2.0
     */
    public function update( $new_instance, $old_instance ) {
        return array(
            'title' => strip_tags( $new_instance['title'] ),
            'id' => intval( $new_instance['id'] )
        );
    }

    /**
     * Widget settings form
     *
     * @since 2.0
     */
    public function form( $instance ) {

        /** Get all of the slideshows for the select box */
        $slideshows = ESP_Database::get_instance()->get_all_slideshows();

        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'easingsliderpro' ); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="widefat" value="<?php if ( isset( $instance['title'] ) ) echo esc_attr( $instance['title'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'id' ); ?>"><?php _e( 'Slideshow', 'easingsliderpro' ); ?></label>
            <select type="text" id="<?php echo $this->get_field_id( 'id' ); ?>" name="<?php echo $this->get_field_name( 'id' ); ?>" class="widefat">
                <?php foreach ( $slideshows as $s ) : ?>
                    <option value="<?php echo esc_attr( $s->id ); ?>" <?php if ( isset( $instance['id'] ) ) selected( $instance['id'], $s->id ); ?>><?php echo esc_html( $s->name ) . sprintf( __( ' (ID #%s)', 'easingsliderpro' ), $s->id ); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php

    }

}
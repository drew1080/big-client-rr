<?php

/**
 * Main Slideshow Class
 *
 * @author Matthew Ruddy
 * @since 2.0
 */
class ESP_Slideshow {

    /**
     * Class instance
     *
     * @since 2.0
     */
    private static $instance;

    /**
     * An array of loaded slideshow IDs
     *
     * @since 2.0
     */
    public static $loaded = array();

    /**
     * Boolean indicating if custom styles have already been printed
     *
     * @since 2.0
     */
    public static $printed = false;

    /**
     * Getter method for retrieving the class instance.
     *
     * @since 2.0
     */
    public static function get_instance() {
    
        if ( !self::$instance instanceof self )
            self::$instance = new self;
        return self::$instance;
    
    }

    /**
     * Loads slideshow styles
     *
     * @since 2.0
     */
    public static function enqueue_styles() {

        /** Load styling */
        wp_enqueue_style( 'esp-slideshow' );
        do_action( 'easingsliderpro_enqueue_slideshow_styles' );

    }

    /**
     * Loads slideshow scripts
     *
     * @since 2.0
     */
    public static function enqueue_scripts() {

        /** Load scripts */
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'esp-slideshow' );
        do_action( 'easingsliderpro_enqueue_slideshow_scripts' );

    }

    /**
     * Prints the custom styling of all slideshows
     *
     * @since 2.0
     */
    public static function print_all_custom_styles() {

        /** Get all slideshows */
        $slideshows = ESP_Database::get_instance()->get_all_slideshows();

        if ( !empty( $slideshows ) )
            foreach ( ESP_Database::get_instance()->get_all_slideshows() as $slideshow )
                self::print_custom_styles( $slideshow );

        /** Flag that the custom styling has been printed */
        self::$printed = true;

    }

    /**
     * Prints the custom styling of loaded slideshows only
     *
     * @since 2.0
     */
    public static function print_loaded_custom_styles() {

        if ( !empty( self::$loaded ) )
            foreach ( self::$loaded as $id )
                self::print_custom_styles( ESP_Database::get_instance()->get_slideshow( $id ) );

        /** Flag that the custom styling has been printed */
        self::$printed = true;

    }

    /**
     * Prints the custom styling of a particular slideshow
     *
     * @since 2.0
     */
    public static function print_custom_styles( $slideshow ) {

        /** Get the customizations & defaults */
        $customizations = $c = $slideshow->customizations;
        $defaults = ESP_Database::get_instance()->get_slideshow_defaults()->customizations;

        /** Bail if there are no customizations */
        if ( empty( $c ) )
            return;
        if ( $defaults == $c )
            return;

        /** Print the styling. Long selectors here ensure styles take preference over CSS files. */
        ob_start();
        ?>
        <style type="text/css">
            <?php if ( $defaults->border != $c->border ) : ?>
            .easingsliderpro-<?php echo esc_attr( $slideshow->id ); ?> {
                <?php if ( $defaults->border->color != $c->border->color ) echo "border-color: {$c->border->color};"; ?>
                <?php if ( $defaults->border->width != $c->border->width ) echo "border-width: {$c->border->width}px; border-style: solid;"; ?>
                <?php if ( $defaults->border->radius != $c->border->radius ) echo "-webkit-border-radius: {$c->border->radius}px; -moz-border-radius: {$c->border->radius}px; border-radius: {$c->border->radius}px;"; ?>
            }
            <?php endif; ?>
            <?php if ( $defaults->arrows != $c->arrows ) : ?>
            .easingsliderpro-<?php echo esc_attr( $slideshow->id ); ?> .easingsliderpro-arrows.easingsliderpro-next,
            .easingsliderpro-<?php echo esc_attr( $slideshow->id ); ?> .easingsliderpro-arrows.easingsliderpro-prev {
                <?php if ( $defaults->arrows->width != $c->arrows->width ) echo "width: {$c->arrows->width}px;"; ?>
                <?php if ( $defaults->arrows->height != $c->arrows->height ) { $margin_top = ( $c->arrows->height / 2 ); echo "height: {$c->arrows->height}px; margin-top: -{$margin_top}px;"; } ?>
            }
            .easingsliderpro-<?php echo esc_attr( $slideshow->id ); ?> .easingsliderpro-arrows.easingsliderpro-next {
                <?php if ( $defaults->arrows->next != $c->arrows->next ) echo "background-image: url({$c->arrows->next});"; ?>
            }
            .easingsliderpro-<?php echo esc_attr( $slideshow->id ); ?> .easingsliderpro-arrows.easingsliderpro-prev {
                <?php if ( $defaults->arrows->prev != $c->arrows->prev ) echo "background-image: url({$c->arrows->prev});"; ?>
            }
            <?php endif; ?>
            <?php if ( $defaults->pagination != $c->pagination ) : ?>
            .easingsliderpro-<?php echo esc_attr( $slideshow->id ); ?> .easingsliderpro-pagination .easingsliderpro-icon {
                <?php if ( $defaults->pagination->width != $c->pagination->width ) echo "width: {$c->pagination->width}px;"; ?>
                <?php if ( $defaults->pagination->height != $c->pagination->height ) echo "height: {$c->pagination->height}px;"; ?>
            }
            .easingsliderpro-<?php echo esc_attr( $slideshow->id ); ?> .easingsliderpro-pagination .easingsliderpro-icon.inactive {
                <?php if ( $defaults->pagination->inactive != $c->pagination->inactive ) echo "background-image: url({$c->pagination->inactive});"; ?>
            }
            .easingsliderpro-<?php echo esc_attr( $slideshow->id ); ?> .easingsliderpro-pagination .easingsliderpro-icon.active {
                <?php if ( $defaults->pagination->active != $c->pagination->active ) echo "background-image: url({$c->pagination->active});"; ?>
            }
            <?php endif; ?>
        </style>
        <?php
        print preg_replace( '/\s+/', ' ', ob_get_clean() );

    }

    /**
     * Parses a slide link and return the components we need
     *
     * @since 2.0
     */
    public function parse_link( $url ) {

        /** Validate the link URL */
        $validated_url = $this->validate_link( $url );

        /** Return components */
        return (object) array(
            'url' => $this->validate_link( $url ),
            'class' => ( $validated_url !== $url ) ? 'has-video' : ''
        );

    }

    /**
     * Validates a slide link, replacing it with the correct embed URL if it is a video
     *
     * @since 2.0
     */
    public function validate_link( $url ) {

        /** Handle YouTube URLs */
        if ( stripos( $url, 'youtube.com' ) || stripos( $url, 'youtu.be' ) ) {

            /** Prepare link */
            if ( stripos( $url, 'watch?v=' ) )
                $url = str_replace( 'watch?v=', '/embed/', $url );
            elseif ( stripos( $url, 'youtu.be/' ) )
                $url = str_replace( 'youtu.be/', 'youtube.com/embed/', $url );

            /** Add parameters to URL */
            $url = $url .'?'. http_build_query( array( 'autohide' => 1, 'autoplay' => 1 ) );

        }

        /** Handle Vimeo URLs */
        if ( stripos( $url, 'vimeo.com' ) ) {
            if ( stripos( $url, 'player.vimeo.com/' ) === false )
                $url = str_replace( 'vimeo.com/', 'player.vimeo.com/video/', $url ) .'?'. http_build_query( array( 'autoplay' => 1 ) );
        }
        
        /** Return validated URL */
        return $url;

    }

    /**
     * Returns the users current browser
     *
     * @since 2.0
     */
    public function detect_browser() {
        $browser = esc_attr( $_SERVER[ 'HTTP_USER_AGENT' ] );
        if ( preg_match( '/MSIE 7/i', $browser ) )
            return "is-ie7";
        elseif ( preg_match( '/MSIE 8/i', $browser ) )
            return "is-ie8";
        elseif ( preg_match( '/MSIE 9/i', $browser ) )
            return "is-ie9";
        elseif ( preg_match( '/Firefox/i', $browser ) )
            return "is-firefox";
        elseif ( preg_match( '/Safari/i', $browser ) )
            return "is-safari";
        elseif ( preg_match( '/Chrome/i', $browser ) )
            return "is-chrome";
        elseif ( preg_match( '/Flock/i', $browser ) )
            return "is-flock";
        elseif ( preg_match( '/Opera/i', $browser ) )
            return "is-opera";
        elseif ( preg_match( '/Netscape/i', $browser ) )
            return "is-netscape";
        return;
    }

    /**
     * Displays a slideshow
     *
     * @since 2.0
     */
    public function display_slideshow( $id ) {

        /** Add to loaded slideshows array */
        self::$loaded[] = $id;

        /** Display the slideshow */
        ob_start();
        require dirname( EasingSliderPro::get_file() ) . DIRECTORY_SEPARATOR .'templates'. DIRECTORY_SEPARATOR .'slideshow.php';
        return ob_get_clean();

    }

}
<?php

/**
 * Plugin upgrade class. This will become more populated over time.
 *
 * @author Matthew Ruddy
 * @since 2.0
 */
class ESP_Upgrade {

    /**
     * Upgrade from Easing Slider
     *
     * @since 2.0
     */
    public static final function do_upgrades() {

        /** Get current plugin version */
        $version = get_option( 'easingsliderpro_version' );

        /** Custom hooks */
        do_action( 'easingsliderpro_upgrades', EasingSliderPro::$version, $version );

        /** Update plugin version number if needed */
        if ( !version_compare( $version, EasingSliderPro::$version, '=' ) )
            update_option( 'easingsliderpro_version', EasingSliderPro::$version );

    }

}
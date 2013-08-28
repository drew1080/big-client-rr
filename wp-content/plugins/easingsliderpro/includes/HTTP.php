<?php

/**
 * Class for Riva Slider's HTTP requests and license key validation.
 *
 * @author Matthew Ruddy
 * @since 2.0
 */
class ESP_HTTP {

    /**
     * Our API Url
     *
     * @since 2.0
     */
    private static $api = 'http://easingslider.com/api/';

    /**
     * Transient expiry time (default 6 hours)
     *
     * @since 2.0
     */
    public static $transient_expiry = 21600;

    /**
     * Get plugin updates, requesting to server if required.
     *
     * @since 2.0
     */
    public static final function get_updates() {

        global $wp_version;

        /** Bail if update have been manually disabled */
        if ( !apply_filters( 'easingsliderpro_enable_updates', __return_true() ) )
            return false;

        /** Bail if we don't have a valid license key */
        if ( get_option( 'easingsliderpro_license_key' ) != 'valid' )
            return false;

        /** Get the cached transient */
        $updates = get_transient( 'easingsliderpro_available_updates' );

        /** Do a new request if transient has expired */
        if ( !$updates ) {

            /** Get setting so we can retrieve the license key */
            $settings = get_option( 'easingsliderpro_settings' );

            /** Make the request for update information. We get the response body right away as we don't want to alarm the user of failed update checks. */
            $response = json_decode(
                wp_remote_retrieve_body(
                    wp_remote_post( self::$api, array(
                        'timeout' => 10,
                        'body' => array(
                            'action' => 'get_updates',
                            'license_key' => $settings['license_key'],
                            'url' => get_bloginfo( 'url' ),
                            'versions' => array(
                                'php' => phpversion(),
                                'mysql' => mysql_get_server_info(),
                                'wordpress' => $wp_version,
                                'plugin' => EasingSliderPro::$version
                            )
                        )
                    ) )
                )
            );

            /** Bail if update request failed */
            if ( !isset( $response->response ) )
                return false;

            /** Flag invalid license key if response is false (this is the response for an invalid key) */
            if ( $response->response === false ) {
                update_option( 'easingsliderpro_license_key', 'invalid' );
                return false;
            }

            /** Get and cache the updates */
            $updates = $response->response;
            set_transient( 'easingsliderpro_available_updates', $updates, self::$transient_expiry );

        }

        return $updates;

    }

    /**
     * Add update information to WordPress updates transient array
     *
     * @since 2.0
     */
    public static final function check_updates( $checked_data ) {

        /** Bail if already checked */
        if ( empty( $checked_data->checked ) )
            return $checked_data;

        /** Get current updates */
        $updates = self::get_updates();

        /** Bail if false returned */
        if ( !$updates )
            return $checked_data;

        /** Bail if we are using the current version */
        if ( version_compare( EasingSliderPro::$version, $updates->new_version, '>=' ) )
            return $checked_data;

        /** Remove plugin's API information (not need for this). */
        unset( $updates->information );

        /** Get plugin slug */
        $slug = plugin_basename( EasingSliderPro::get_file() );

        /** Add to WordPress updates transient array */
        $checked_data->response[ $slug ] = $updates;

        return $checked_data;

    }

    /**
     * Displays plugin update information on the WordPress Plugins page
     *
     * @since 2.0
     */
    public static final function update_information( $res, $action, $args ) {

        /** Return the plugin information object */
        if ( isset( $args->slug ) && $args->slug == 'easingsliderpro' ) {

            /** Get available update info stored in database */
            $updates = self::get_updates();

            /** Ensure sections are an array (avoids errors) */
            $updates->information->sections = (array) $updates->information->sections;

            /** Return information (if it exists) */
            if ( $updates->information )
                return $updates->information;

        }

        return $res;

    }

    /**
     * Displays messages under the Easing Slider "Pro" plugin row
     *
     * @since 2.0
     */
    public static final function plugin_row( $plugin_name ) {

        /** Get license key validity */
        $license_key_status = get_option( 'easingsliderpro_license_key' );

        /** Display messages */
        if ( $license_key_status != 'valid' )
            echo '</tr><tr class="plugin-update-tr"><td colspan="3" class="plugin-update"><div class="update-message">'. __( 'Please <a href="admin.php?page=easingsliderpro_edit_settings">register</a> your copy of Easing Slider "Pro" to receive access to upgrades and support. Need a license key? <a href="http://easingslider.com/purchase">Purchase one here</a>.', 'easingsliderpro' ) .'</div></td>';

    }

    /**
     * Checks if we need to carry out license key validation (or not)
     *
     * @since 2.0
     */
    public static final function should_do_validation( $settings ) {

        /** Bail if license key index doesn't exist */
        if ( !isset( $settings['license_key'] ) )
            return false;

        /** Check against currently saved license key */
        $old_settings = get_option( 'easingsliderpro_settings' );
        if ( $old_settings['license_key'] !== $settings['license_key'] )
            return true;

        return false;

    }

    /**
     * Validates the plugin using its license key
     *
     * @since 2.0
     */
    public static final function do_validation( $key ) {

        global $wp_version;

        /** Bail if key is empty */
        if ( empty( $key ) ) {
            update_option( 'easingsliderpro_license_key', '' );
            return;
        }

        /** Make the validation request */
        $request = wp_remote_post( self::$api, array(
            'body' => array(
                'action' => 'validate',
                'license_key' => $key,
                'url' => get_bloginfo( 'url' ),
                'versions' => array(
                    'php' => phpversion(),
                    'mysql' => mysql_get_server_info(),
                    'wordpress' => $wp_version,
                    'plugin' => EasingSliderPro::$version
                )
            )
        ) );

        /** Bail if WordPress error was received (HTTP API may have failed or may not have access required) */
        if ( is_wp_error( $request ) )
            return (object) array( 'message' => $request->get_error_message(), 'status' => 'error', 'checked' => false );

        /** Get the response body */
        $response = json_decode( wp_remote_retrieve_body( $request ) );
                
        /** Bail if no response was received (service may temporarily be offline) */
        if ( !isset( $response->response ) )
            return (object) array( 'message' => __( 'The License Key validation service is temporarily unavailable. Please try again later.', 'easingsliderpro' ), 'status' => 'error', 'checked' => false );

        /** Handle query response */
        if ( $response->response === false ) {

            /** Flag invalid license key */
            update_option( 'easingsliderpro_license_key', 'invalid' );

            /** Return response object */
            return (object) array( 'message' => __( 'Failed to validate license key. Please make sure it is correct and try again.', 'rivaslider' ), 'status' => 'error', 'checked' => true );
        }
        else {

            /** Set license key admin option  */
            update_option( 'easingsliderpro_license_key', 'valid' );

            /** Return response object */
            return (object) array( 'message' => __( 'License key has been validated successfully.', 'rivaslider' ), 'status' => 'updated', 'checked' => true );

        }

    }

}
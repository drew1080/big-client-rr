<?php

/**
 * Legacy plugin functionality.
 *
 * @author Matthew Ruddy
 * @since 2.0.2
 */
class ESP_Legacy {

    /**
     * Parent class object
     *
     * @since 2.0.2
     */
    private static $class;

    /**
     * Initialize legacy functionality
     *
     * @since 2.0.2
     */
    public static final function init( $class ) {

        global $pagenow;

        /** Store parent */
        self::$class = $class;

        /** Hook old shortcodes */
        add_shortcode( 'rivasliderpro', array( $class, 'do_shortcode' ) );

        /** Continue only if there are Riva Slider Pro settings to act upon (or not currently removing them) */
        if ( get_option( 'riva_slider_pro_version' ) ) {

            /** Hook actions */
            add_action( 'easingsliderpro_edit_slideshows_actions', array( __CLASS__, 'do_legacy_import' ) );
            add_action( 'easingsliderpro_edit_settings_actions', array( __CLASS__, 'do_legacy_import' ) );
            add_action( 'easingsliderpro_edit_settings_actions', array( __CLASS__, 'do_legacy_remove' ) );
            add_action( 'easingsliderpro_welcome_panel_before', array( __CLASS__, 'print_legacy_message' ) );
            add_action( 'easingsliderpro_settings_after', array( __CLASS__, 'print_legacy_settings_field' ), 10, 2 );

        }

        /** Continue only if there are Easing Slider "Lite" settings to act upon (or not currently removing them) */
        if ( get_option( 'easingsliderlite_version' ) ) {

            /** Hook actions */
            add_action( 'easingsliderpro_edit_slideshows_actions', array( __CLASS__, 'do_lite_import' ) );
            add_action( 'easingsliderpro_edit_settings_actions', array( __CLASS__, 'do_lite_import' ) );
            add_action( 'easingsliderpro_edit_settings_actions', array( __CLASS__, 'do_lite_remove' ) );
            add_action( 'easingsliderpro_welcome_panel_before', array( __CLASS__, 'print_lite_message' ) );
            add_action( 'easingsliderpro_settings_after', array( __CLASS__, 'print_lite_settings_field' ), 10, 2 );

        }

    }

    /**
     * Imports old settings from the old Riva Slider Pro settings.
     *
     * @since 2.0.2
     */
    public static final function legacy_import() {

        /** Reset database table */
        ESP_Database::get_instance()->delete_table();
        ESP_Database::get_instance()->create_table();

        /** Get old slideshows */
        $old_slideshows = get_option( 'riva_slider_pro_slideshows' );

        /** Get the settings */
        foreach ( $old_slideshows as $old_slideshow ) {

            /** Get default settings for new slideshow */
            $s = ESP_Database::get_instance()->get_slideshow_defaults();

            /** Now let's transfer the settings */
            $s->name = $old_slideshow['name'];
            $s->dimensions->width = $old_slideshow['width'];
            $s->dimensions->height = $old_slideshow['height'];
            $s->general->randomize = ( isset( $old_slideshow['random_order'] ) && $old_slideshow['random_order'] ) ? true : false;
            $s->transitions->duration = $old_slideshow['trans_time'];
            $s->playback->enabled = $old_slideshow['auto_play'];
            $s->playback->pause = $old_slideshow['pause_time'];
            $s->navigation->arrows = ( $old_slideshow['direction_nav'] == 'enable' ) ? true : false;
            $s->navigation->arrows_hover = $old_slideshow['direction_nav_hover'];
            $s->navigation->arrows_position = $old_slideshow['direction_nav_pos'];
            $s->navigation->pagination = ( $old_slideshow['control_nav'] == 'enable' ) ? true : false;
            $s->navigation->pagination_position = 'outside';
            $s->navigation->pagination_location = str_replace( '_', '-', $old_slideshow['control_nav_pos'] );

            /** Add the slides */
            $s->slides = array();
            foreach ( $old_slideshow['images'] as $index => $image ) {

                /** Set the slide thumbnail */
                $sizes = (object) array(
                    'thumbnail' => (object) array(
                        'url' => $image['image-url']
                    )
                );

                /** Add the slide */
                $s->slides[] = (object) array(
                    'id' => ( $index + 1 ),
                    'url' => $image['image-url'],
                    'alt' => $image['image-alt'],
                    'title' => $image['image-title'],
                    'link' => ( $image['image-link'] == 'webpage' ) ? $image['webpage-url'] : $image['video-url'],
                    'linkTarget' => "_{$image['link-target']}",
                    'content' => ( !empty( $image['content-text'] ) ) ? $image['content-text'] : $image['content-title'],
                    'sizes' => $sizes
                );

            }

            /** Encode the slides as they aren't done automatically (inconvenient here, but convenient elsewhere) */
            $s->slides = json_encode( $s->slides );

            /** Do validation */
            $s = EasingSliderPro::get_instance()->validate( $s );

            /** Add the slideshow */
            ESP_Database::get_instance()->add_slideshow( (array) $s );

        }

        /** Flag upgrade */
        update_option( 'easingsliderpro_major_upgrade', 1 );

    }

    /**
     * Does a legacy settings import
     *
     * @since 2.0.2
     */
    public static final function do_legacy_import( $page ) {

        /** Imports legacy Riva Slider Pro setting */
        if ( isset( $_POST['legacy-import'] ) ) {

            /** Security check */
            if ( !self::$class->security_check( 'legacy-import', $page ) ) {
                wp_die( __( 'Security check has failed. Import has been prevented. Please try again.', 'easingsliderpro' ) );
                exit();
            }

            /** Do the upgrade (thus importing old settings) */
            self::legacy_import();

            /** Queue message */
            return self::$class->queue_message( __( 'Riva Slider Pro settings have been imported successfully.', 'easingsliderpro' ), 'updated' );
    
        }

    }

    /**
     * Does the removal of legacy settings
     *
     * @since 2.0.2
     */
    public static final function do_legacy_remove( $page ) {

        /** Removes legacy Riva Slider Pro settings */
        if ( isset( $_POST['legacy-remove'] ) ) {

            /** Security check */
            if ( !self::$class->security_check( 'legacy-remove', $page ) ) {
                wp_die( __( 'Security check has failed. Removal has been prevented. Please try again.', 'easingsliderpro' ) );
                exit();
            }

            /** Delete the options */
            delete_option( 'riva_slider_pro_slideshows' );
            delete_option( 'riva_slider_pro_auto_increment' );
            delete_option( 'riva_slider_pro_global_settings' );
            delete_option( 'riva_slider_pro_update_check' );
            delete_option( 'riva_slider_pro_serialcode' );
            delete_option( 'riva_slider_pro_dynamic_css' );
            delete_option( 'riva_slider_pro_version' );

            /** Remove user capabilities */
            self::$class->manage_capabilities( 'remove', array(
                'rivasliderpro_view_slideshows',
                'rivasliderpro_view_addnew',
                'rivasliderpro_view_global_settings',
                'rivasliderpro_view_metabox',
                'rivasliderpro_view_serial_code',
                'rivasliderpro_edit_slideshows',
                'rivasliderpro_edit_addnew',
                'rivasliderpro_edit_global_settings',
                'rivasliderpro_edit_metabox'
            ) );

            /** Queue message */
            return self::$class->queue_message( __( 'Riva Slider Pro settings have been permanently deleted.', 'easingsliderpro' ), 'updated' );

        }

    }

    /**
     * Prints the Riva Slider Pro legacy settings message in "All Slideshows" panel
     *
     * @since 2.0.2
     */
    public static final function print_legacy_message() {

        /** Display import legacy settings panel */
        if ( !get_option( 'easingsliderpro_major_upgrade' ) ) :
            ?>
            <div class="welcome-panel-content">
                <?php
                    /** Security field */
                    wp_nonce_field( "easingsliderpro-legacy-import_{$_GET['page']}", "easingsliderpro-legacy-import_{$_GET['page']}", false );
                ?>
                <h2><?php _e( 'Riva Slider "Pro" Settings Detected', 'easingsliderpro' ); ?></h2>
                <p class="about-description">
                    <?php _e( 'Click the button below to import your settings from Riva Slider "Pro".', 'easingsliderpro' ); ?>
                </p>
                <div class="welcome-panel-column-container">
                    <div class="welcome-panel-column">
                        <input type="submit" name="legacy-import" class="button button-primary button-hero warn_reset" value="<?php esc_attr_e( 'Import my Riva Slider "Pro" settings.', 'easingsliderpro' ); ?>">
                    </div>
                </div>
            </div>
            <div class="divider"></div>
            <?php
        endif;

    }

    /**
     * Prints the Riva Slider Pro legacy settings field in "Settings" panel
     *
     * @since 2.0.2
     */
    public static final function print_legacy_settings_field( $settings, $page ) {

        /** Bail if we've already triggered deletion */
        if ( isset( $_POST['legacy-remove'] ) )
            return;

        /** Display field related to legacy settings in "Settings" admin panel */
        ?>
        <h3><?php _e( 'Riva Slider "Pro" Settings', 'easingsliderpro' ); ?></h3>
        <table class="form-table main-settings">
            <tbody>
                <tr valign="top">
                    <?php
                        /** Security nonce fields */
                        wp_nonce_field( "easingsliderpro-legacy-import_{$_GET['page']}", "easingsliderpro-legacy-import_{$_GET['page']}", false );
                        wp_nonce_field( "easingsliderpro-legacy-remove_{$_GET['page']}", "easingsliderpro-legacy-remove_{$_GET['page']}", false );
                    ?>
                    <th scope="row"><label><?php _e( 'Legacy Settings', 'easingsliderpro' ); ?></label></th>
                    <td>
                        <input type="submit" name="legacy-import" class="button button-primary warn_reset" value="<?php esc_attr_e( 'Import Riva Slider "Pro" Settings', 'easingsliderpro' ); ?>">
                        <input type="submit" name="legacy-remove" class="button button-secondary warn" value="<?php esc_attr_e( 'Remove Riva Slider "Pro" Settings', 'easingsliderpro' ); ?>">
                        <p class="description"><?php _e( 'These buttons allow you to import and remove your old Riva Slider "Pro" v1.x settings. Only remove them if you are sure you will not be downgrading the plugin in the future.', 'easingsliderpro' ); ?></p>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="divider"></div>
        <?php

    }

    /**
     * Imports settings from Easing Slider "Lite"
     *
     * @since 2.0.2
     */
    public static final function lite_import() {

        /** Get lite slideshow and customizations */
        $slideshow = get_option( 'easingsliderlite_slideshow' );
        $slideshow->customizations = json_decode( get_option( 'easingsliderlite_customizations' ) );

        /** Add slideshow name */
        $slideshow->name = __( 'Easing Slider "Lite" slideshow', 'easingsliderpro' );

        /** Add new variables to each slide */
        if ( !empty( $slideshow->slides ) )
            foreach ( $slideshow->slides as $index => $slide )
                $slideshow->slides[ $index ]->content = '';

        /** JSON encode the slides */
        $slideshow->slides = json_encode( $slideshow->slides );

        /** Add the new slideshow */
        ESP_Database::get_instance()->add_slideshow( (array) $slideshow );

        /** Flag upgrade */
        update_option( 'easingsliderpro_lite_upgrade', 1 );

    }

    /**
     * Does a Easing Slider "Lite" settings import
     *
     * @since 2.0.2
     */
    public static final function do_lite_import( $page ) {

        /** Imports Easing Slider "Lite" settings */
        if ( isset( $_POST['lite-import'] ) ) {

            /** Security check */
            if ( !self::$class->security_check( 'lite-import', $page ) ) {
                wp_die( __( 'Security check has failed. Import has been prevented. Please try again.', 'easingsliderpro' ) );
                exit();
            }

            /** Do the upgrade (thus importing old settings) */
            self::lite_import();

            /** Queue message */
            return self::$class->queue_message( esc_html__( 'Easing Slider "Lite" settings have been imported successfully.', 'easingsliderpro' ), 'updated' );

        }

    }

    /**
     * Does the removal of Easing Slider "Lite" settings
     *
     * @since 2.0.2
     */
    public static final function do_lite_remove( $page ) {

        /** Removes Easing Slider "Lite" settings */
        if ( isset( $_POST['lite-remove'] ) ) {

            /** Security check */
            if ( !self::$class->security_check( 'lite-remove', $page ) ) {
                wp_die( __( 'Security check has failed. Removal has been prevented. Please try again.', 'easingsliderpro' ) );
                exit();
            }

            /** Delete "wp_options" table options */
            delete_option( 'easingsliderlite_version' );
            delete_option( 'easingsliderlite_slideshow' );
            delete_option( 'easingsliderlite_customizations' );
            delete_option( 'easingsliderlite_settings' );
            delete_option( 'easingsliderlite_major_upgrade' );
            delete_option( 'easingsliderlite_disable_welcome_panel' );

            /** Remove user capabilities */
            self::$class->manage_capabilities( 'remove', array(
                'easingsliderlite_edit_slideshow',
                'easingsliderlite_can_customize',
                'easingsliderlite_edit_settings'
            ) );

            /** Queue message */
            return self::$class->queue_message( esc_html__( 'Easing Slider "Lite" settings have been permanently deleted.', 'easingsliderpro' ), 'updated' );

        }

    }

    /**
     * Prints Easing Slider "Lite" settings import message in "All Slideshows" panel
     *
     * @since 2.0.2
     */
    public static final function print_lite_message() {

        /** Display import "Lite" settings panel */
        if ( !get_option( 'easingsliderpro_lite_upgrade' ) ) :
            ?>
            <div class="welcome-panel-content">
                <?php
                    /** Security field */
                    wp_nonce_field( "easingsliderpro-lite-import_{$_GET['page']}", "easingsliderpro-lite-import_{$_GET['page']}", false );
                ?>
                <h2><?php _e( 'Easing Slider "Lite" Settings Detected', 'easingsliderpro' ); ?></h2>
                <p class="about-description">
                    <?php _e( 'Click the button below to import your settings from Easing Slider "Lite".', 'easingsliderpro' ); ?>
                </p>
                <div class="welcome-panel-column-container">
                    <div class="welcome-panel-column">
                        <input type="submit" name="lite-import" class="button button-primary button-hero" value="<?php esc_attr_e( 'Import my Easing Slider "Lite" settings.', 'easingsliderpro' ); ?>">
                    </div>
                </div>
            </div>
            <div class="divider"></div>
            <?php
        endif;

    }

    /**
     * Prints the Easing Slider "Lite" settings field on "Settings" panel
     *
     * @since 2.0.2
     */
    public static final function print_lite_settings_field( $settings, $page ) {

        /** Bail if we've already triggered deletion */
        if ( isset( $_POST['lite-remove'] ) )
            return;

        /** Display field related to lite settings in "Settings" admin panel */
        ?>
        <h3><?php _e( 'Easing Slider "Lite" Settings', 'easingsliderpro' ); ?></h3>
        <table class="form-table main-settings">
            <tbody>
                <tr valign="top">
                    <?php
                        /** Security nonce fields */
                        wp_nonce_field( "easingsliderpro-lite-import_{$_GET['page']}", "easingsliderpro-lite-import_{$_GET['page']}", false );
                        wp_nonce_field( "easingsliderpro-lite-remove_{$_GET['page']}", "easingsliderpro-lite-remove_{$_GET['page']}", false );
                    ?>
                    <th scope="row"><label><?php _e( '"Lite" Settings', 'easingsliderpro' ); ?></label></th>
                    <td>
                        <input type="submit" name="lite-import" class="button button-primary" value="<?php esc_attr_e( 'Import Easing Slider "Lite" Settings', 'easingsliderpro' ); ?>">
                        <input type="submit" name="lite-remove" class="button button-secondary warn" value="<?php esc_attr_e( 'Remove Easing Slider "Lite" Settings', 'easingsliderpro' ); ?>">
                        <p class="description"><?php _e( 'These buttons allow you to import and remove your Easing Slider "Lite" settings. Only remove them if you are sure you will not be downgrading the plugin in the future.', 'easingsliderpro' ); ?></p>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="divider"></div>
        <?php

    }

}
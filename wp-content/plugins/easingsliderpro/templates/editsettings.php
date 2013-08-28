<?php
    /** Get the plugin settings */
    $settings = $s = get_option( 'easingsliderpro_settings' );
?>
<div class="wrap">
    <div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
    <h2><?php _e( 'Edit Settings', 'easingsliderpro' ); ?></h2>
    <form name="post" action="admin.php?page=easingsliderpro_edit_settings" method="post">
        <?php

            /** Security nonce field */
            wp_nonce_field( "easingsliderpro-save_{$_GET['page']}", "easingsliderpro-save_{$_GET['page']}", false );
            wp_nonce_field( "easingsliderpro-reset_{$_GET['page']}", "easingsliderpro-reset_{$_GET['page']}", false );
        
            /** Before actions */
            do_action( 'easingsliderpro_settings_before', $s, $_GET['page'] );

        ?>
        <div class="main-panel">
            <div class="messages-container">
                <?php do_action( 'easingsliderpro_admin_messages' ); ?>
            </div>

            <h3><?php _e( 'General Settings', 'easingsliderpro' ); ?></h3>
            <table class="form-table settings">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><label for="license_key"><?php _e( 'Support License Key', 'easingsliderpro' ); ?></label></th>
                        <td>
                            <label for="license_key">
                                <?php
                                    /** Check validity */
                                    $validity = get_option( 'easingsliderpro_license_key' );
                                ?>
                                <input type="password" name="settings[license_key]" id="license_key" class="regular-text <?php if ( $validity == 'invalid' ) echo 'error'; elseif ( $validity == 'valid' ) echo 'success'; ?>" value="<?php echo esc_attr( $s['license_key'] ); ?>">
                                <?php if ( $validity == 'invalid' ) : ?><span style="color: #b94a48;"><?php _e( 'License key entered is invalid.', 'easingsliderpro' ); ?></span><?php elseif ( $validity == 'valid' ) : ?><span style="color: #468847;"><?php _e( 'License key is valid.', 'easingsliderpro' ); ?><?php endif; ?>
                            </label>
                            <p class="description"><?php _e( 'Enter your license key to receive update information and support for this installation.', 'easingsliderpro' ); ?></p>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row"><label for="resizing"><?php _e( 'Image Resizing', 'easingsliderpro' ); ?></label></th>
                        <td>
                            <label for="resizing_true">
                                <input type="radio" name="settings[resizing]" id="resizing_true" value="true" <?php checked( $s['resizing'], true ); ?>><span><?php _e( 'Enable', 'easingsliderpro' ); ?></span>
                            </label>
                            <label for="resizing_false">
                                <input type="radio" name="settings[resizing]" id="resizing_false" value="false" <?php checked( $s['resizing'], false ); ?>><span><?php _e( 'Disable', 'easingsliderpro' ); ?></span>
                            </label>
                            <p class="description"><?php _e( 'Enable or disable the plugins image resizing functionality. Disable this if you do not want the slide images to be resized.', 'easingsliderpro' ); ?></p>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label for="load_scripts"><?php _e( 'Output JS', 'easingsliderpro' ); ?></label></th>
                        <td>
                            <label for="load_scripts_header">
                                <input type="radio" name="settings[load_scripts]" id="load_scripts_header" value="header" <?php checked( $s['load_scripts'], 'header' ); ?>><span><?php _e( 'Header', 'easingsliderpro' ); ?></span>
                            </label>
                            <label for="load_scripts_footer">
                                <input type="radio" name="settings[load_scripts]" id="load_scripts_footer" value="footer" <?php checked( $s['load_scripts'], 'footer' ); ?>><span><?php _e( 'Footer', 'easingsliderpro' ); ?></span>
                            </label>
                            <label for="load_scripts_off">
                                <input type="radio" name="settings[load_scripts]" id="load_scripts_off" value="false" <?php checked( $s['load_scripts'], false ); ?>><span><?php _e( 'Off', 'easingsliderpro' ); ?></span>
                            </label>
                            <p class="description"><?php _e( 'Settings for Javascript output. Scripts loaded in the "Footer" are only when they are needed. This decreases page loading times but is prone to errors.', 'easingsliderpro' ); ?></p>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label for="load_styles"><?php _e( 'Output CSS', 'easingsliderpro' ); ?></label></th>
                        <td>
                            <label for="load_styles_header">
                                <input type="radio" name="settings[load_styles]" id="load_styles_header" value="header" <?php checked( $s['load_styles'], 'header' ); ?>><span><?php _e( 'Header', 'easingsliderpro' ); ?></span>
                            </label>
                            <label for="load_styles_footer">
                                <input type="radio" name="settings[load_styles]" id="load_styles_footer" value="footer" <?php checked( $s['load_styles'], 'footer' ); ?>><span><?php _e( 'Footer', 'easingsliderpro' ); ?></span>
                            </label>
                            <label for="load_styles_off">
                                <input type="radio" name="settings[load_styles]" id="load_styles_off" value="false" <?php checked( $s['load_styles'], false ); ?>><span><?php _e( 'Off', 'easingsliderpro' ); ?></span>
                            </label>
                            <p class="description"><?php _e( 'Settings for CSS output. Styles loaded in the "Footer" will invalidate the HTML, but will prevent them from loading when not needed.', 'easingsliderpro' ); ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="divider"></div>

            <h3><?php _e( 'Reset Plugin', 'easingsliderpro' ); ?></h3>
            <table class="form-table main-settings">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><label for="reset"><?php _e( 'Plugin Settings', 'easingsliderpro' ); ?></label></th>
                        <td>
                            <input type="submit" name="reset" class="button button-secondary warn_reset" value="<?php _e( 'Reset Plugin', 'easingsliderpro' ); ?>">
                            <p class="description"><?php _e( 'Click this button to reset the plugin to its default settings. This cannot be reversed, so be sure before you do this!', 'easingsliderpro' ); ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="divider"></div>

            <h3><?php _e( 'Installation Settings', 'easingsliderpro' ); ?></h3>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><?php _e( 'PHP Version', 'easingsliderpro' ); ?></th>
                        <td><?php echo phpversion(); ?></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><?php _e( 'MySQL Version', 'easingsliderpro' ); ?></th>
                        <td><?php echo mysql_get_server_info(); ?></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><?php _e( 'WordPress Version', 'easingsliderpro' ); ?></th>
                        <td><?php global $wp_version; echo $wp_version; ?></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><?php _e( 'Plugin Version', 'easingsliderpro' ); ?></th>
                        <td><?php echo EasingSliderPro::$version; ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="divider"></div>

            <?php
                /** After actions */
                do_action( 'easingsliderpro_settings_after', $s, $_GET['page'] );
            ?>

            <p class="submit">
                <input type="submit" name="save" class="button button-primary button-large" id="save" accesskey="p" value="<?php _e( 'Save Settings', 'easingsliderpro' ); ?>">
            </p>
        </div>
    </form>
</div>
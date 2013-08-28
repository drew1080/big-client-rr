<?php

    /** Get all slideshows */
    $slideshows = ESP_Database::get_instance()->get_all_slideshows();

    /** Bail if there are no slideshows to customize */
    if ( empty( $slideshows ) )
        return _e( '<p style="background-color: #ffebe8; border: 1px solid #c00; border-radius: 4px; padding: 8px !important;">You need to create some slideshows to use the customizer. Whoops!</p>', 'easingsliderpro' );

    /** Get the slideshow */
    if ( isset( $_GET['id'] ) )
        $slideshow = $s = ESP_Database::get_instance()->get_slideshow( $_GET['id'] );
    else
        $slideshow = $s = $slideshows[0];

    /** Load required extra scripts and styling */
    wp_enqueue_script( 'customize-controls' );
    wp_enqueue_style( 'customize-controls' );
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'esp-customizer' );
    ESP_Slideshow::enqueue_styles();
    ESP_Slideshow::print_custom_styles( $s );
    ESP_Slideshow::enqueue_scripts();

    /** Get defaults */
    $defaults = ESP_Database::get_instance()->get_slideshow_defaults();

    /** Get the customizations */
    if ( !empty( $s->customizations ) )
        $customizations = $c = $s->customizations;
    else
        $customizations = $c = $defaults->customizations;

    /** Prevent slideshow from showing edit icon */
    add_filter( 'easingsliderpro_edit_slideshow_icon', '__return_false' );

?>
<div id="customize-container" class="customize-container" style="display: block; background: url(../../../../wp-admin/images/wpspin_light.gif) no-repeat center center #fff;">
    <div class="wp-full-overlay expanded" style="opacity: 0;">
        <form id="customize-controls" action="admin.php?page=<?php echo esc_attr( $_GET['page'] ); if ( isset( $_GET['id'] ) ) echo esc_attr( "&id={$_GET['id']}" ); ?>" method="post" class="wrap wp-full-overlay-sidebar" style="z-index: 9999;">
            <?php
                /** Security nonce field */
                wp_nonce_field( "easingsliderpro-save_{$_GET['page']}", "easingsliderpro-save_{$_GET['page']}", false );
            ?>

            <div id="customize-header-actions" class="wp-full-overlay-header">
                <input type="submit" name="save" id="save" class="button button-primary save" value="<?php _e( 'Save', 'easingsliderpro' ); ?>">
                <span class="spinner"></span>
                <a class="back button" href="admin.php?page=easingsliderpro_edit_slideshows"><?php _e( 'Close', 'easingsliderpro' ); ?></a>
            </div>

            <div class="wp-full-overlay-sidebar-content" tabindex="-1">
                <div id="customize-info" class="accordion-section customize-section">
                    <div class="accordion-section-title customize-section-title" aria-label="Theme Customizer Options" tabindex="0">
                        <span class="preview-notice"><?php _e( 'You are customizing <strong class="theme-name">Easing Slider "Pro"</strong>', 'easingsliderpro' ); ?></span>
                        <p>
                            <span class="preview-notice"><?php _e( 'Select a slideshow', 'easingsliderpro' ); ?></span>
                            <select name="s" id="change-slideshow" class="widefat">
                                <?php foreach ( $slideshows as $object ) : ?>
                                    <option value="<?php echo esc_attr( $object->id ); ?>" <?php selected( $object->id, $s->id ); ?>><?php printf( "{$object->name} (#{$object->id})" ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </p>
                    </div>
                </div>
                <div id="customize-theme-controls" class="accordion-container">
                    <ul>
                        <li class="control-section accordion-section customize-section">
                            <h3 class="accordion-section-title customize-section-title" tabindex="0" title=""><?php _e( 'Next & Previous Arrows', 'easingsliderpro' ); ?></h3>
                            <ul class="accordion-section-content customize-section-content">
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( '"Next" Arrow Image', 'easingsliderpro' ); ?></span>
                                        <input type="text" name="arrows[next]" data-selector=".easingsliderpro-next" data-property="background-image" value="<?php echo esc_attr( $c->arrows->next ); ?>">
                                    </label>
                                </li>
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( '"Previous" Arrow Image', 'easingsliderpro' ); ?></span>
                                        <input type="text" name="arrows[prev]" data-selector=".easingsliderpro-prev" data-property="background-image" value="<?php echo esc_attr( $c->arrows->prev ); ?>">
                                    </label>
                                </li>
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( 'Width', 'easingsliderpro' ); ?></span>
                                        <input type="number" min="0" step="1" name="arrows[width]" style="width: 90%" data-selector=".easingsliderpro-arrows" data-property="width" value="<?php echo esc_attr( $c->arrows->width ); ?>"> px
                                    </label>
                                </li>
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( 'Height', 'easingsliderpro' ); ?></span>
                                        <input type="number" min="0" step="1" name="arrows[height]" style="width: 90%" data-selector=".easingsliderpro-arrows" data-property="height" value="<?php echo esc_attr( $c->arrows->height ); ?>"> px
                                    </label>
                                </li>
                            </ul>
                        </li>

                        <li class="control-section accordion-section customize-section">
                            <h3 class="accordion-section-title customize-section-title" tabindex="0" title=""><?php _e( 'Pagination Icons', 'easingsliderpro' ); ?></h3>
                            <ul class="accordion-section-content customize-section-content">
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( '"Inactive" Image', 'easingsliderpro' ); ?></span>
                                        <input type="text" name="pagination[inactive]" data-selector=".easingsliderpro-icon.inactive" data-property="background-image" value="<?php echo esc_attr( $c->pagination->inactive ); ?>">
                                    </label>
                                </li>
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( '"Active" Image', 'easingsliderpro' ); ?></span>
                                        <input type="text" name="pagination[active]" data-selector=".easingsliderpro-icon.active" data-property="background-image" value="<?php echo esc_attr( $c->pagination->active ); ?>">
                                    </label>
                                </li>
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( 'Icon Width', 'easingsliderpro' ); ?></span>
                                        <input type="number" min="0" step="1" name="pagination[width]" style="width: 90%" data-selector=".easingsliderpro-icon" data-property="width" value="<?php echo esc_attr( $c->pagination->width ); ?>"> px
                                    </label>
                                </li>
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( 'Icon Height', 'easingsliderpro' ); ?></span>
                                        <input type="number" min="0" step="1" name="pagination[height]" style="width: 90%" data-selector=".easingsliderpro-icon" data-property="height" value="<?php echo esc_attr( $c->pagination->height ); ?>"> px
                                    </label>
                                </li>
                            </ul>
                        </li>

                        <li class="control-section accordion-section customize-section">
                            <h3 class="accordion-section-title customize-section-title" tabindex="0" title=""><?php _e( 'Border', 'easingsliderpro' ); ?></h3>
                            <ul class="accordion-section-content customize-section-content">
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( 'Color', 'easingsliderpro' ); ?></span>
                                        <input type="text" name="border[color]" class="color-picker-hex" data-selector=".easingsliderpro" data-property="border-color" data-default="#000" value="<?php echo esc_attr( $c->border->color ); ?>">
                                    </label>
                                </li>
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( 'Width', 'easingsliderpro' ); ?></span>
                                        <input type="number" min="0" step="1" name="border[width]" style="width: 90%" data-selector=".easingsliderpro" data-property="border-width" value="<?php echo esc_attr( $c->border->width ); ?>"> px
                                    </label>
                                </li>
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( 'Radius', 'easingsliderpro' ); ?></span>
                                        <input type="number" min="0" step="1" name="border[radius]" style="width: 90%" data-selector=".easingsliderpro" data-property="border-radius" value="<?php echo esc_attr( $c->border->radius ); ?>"> px
                                    </label>
                                </li>
                            </ul>
                        </li>

                        <?php if ( !apply_filters( 'easingsliderpro_disable_shadow', __return_false() ) ) : ?>
                        <li class="control-section accordion-section customize-section">
                            <h3 class="accordion-section-title customize-section-title" tabindex="0" title=""><?php _e( 'Drop Shadow', 'easingsliderpro' ); ?></h3>
                            <ul class="accordion-section-content customize-section-content">
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( 'Display a Drop Shadow', 'easingsliderpro' ); ?></span>
                                        <label for="shadow-enable-true"><input type="radio" name="shadow[enable]" id="shadow-enable-true" data-selector=".easingsliderpro-shadow" data-property="shadow-enable" value="true" style="margin: 0 3px 0 0;" <?php checked( $c->shadow->enable, true ); ?>><?php _e( 'True', 'easingsliderpro' ); ?></label>
                                        <label for="shadow-enable-false"><input type="radio" name="shadow[enable]" id="shadow-enable-false" data-selector=".easingsliderpro-shadow" data-property="shadow-enable" value="false" style="margin: 0 3px 0 20px;" <?php checked( $c->shadow->enable, false ); ?>><?php _e( 'False', 'easingsliderpro' ); ?></label>
                                    </label>
                                </li>
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( 'Shadow Image', 'easingsliderpro' ); ?></span>
                                        <input type="text" name="shadow[image]" data-selector=".easingsliderpro-shadow" data-property="shadow-image" value="<?php echo esc_attr( $c->shadow->image ); ?>">
                                    </label>
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div id="customize-footer-actions" class="wp-full-overlay-footer">
                <a href="#" class="collapse-sidebar button-secondary" title="<?php _e( 'Collapse Sidebar', 'easingsliderpro' ); ?>">
                    <span class="collapse-sidebar-arrow"></span>
                    <span class="collapse-sidebar-label"><?php _e( 'Collapse', 'easingsliderpro' ); ?></span>
                </a>
            </div>

            <input type="hidden" name="id" value="<?php echo esc_attr( $s->id ); ?>">
            <input type="hidden" name="customizations" id="customizations" value="">
            <?php /** This ensures that the JSON is encoded correctly. Using PHP JSON encode can cause magic quote issues */ ?>
            <script type="text/javascript">document.getElementById('customizations').value = '<?php echo addslashes( json_encode( $c ) ); ?>';</script>
        </form>

        <div id="customize-preview" class="wp-full-overlay-main" style="position: relative;">
            <div style="position: absolute; top: 0; left: 0; margin: 45px; width: 100%; height: 100%;">
                <script type="text/javascript">
                    /** Disable automatic playback */
                    jQuery(document).ready(function($) {
                        setTimeout(function() {
                            $('.easingsliderpro-<?php echo esc_attr( $s->id ); ?>').data('easingsliderpro').endPlayback();
                        }, 1000);
                    });
                </script>
                <?php
                    /** Display the slideshow */
                    echo ESP_Slideshow::get_instance()->display_slideshow( $s->id );
                ?>
            </div>
        </div>
    </div>
</div>
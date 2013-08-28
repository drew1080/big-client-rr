<!-- Manage Slides -->
<div class="widgets-holder-wrap exclude">
    <div class="sidebar-name">
        <div class="sidebar-name-arrow"></div>
        <h3><?php _e( 'Manage Slides', 'easingsliderpro' ); ?></h3>
    </div>
    <div class="sidebar-content widgets-sortables clearfix">
        <div class="hide-if-no-js manage-slides-buttons wp-media-buttons" style="margin-top: 1em;">
            <a href="#" id="add-image" class="button button-secondary add-image" data-editor="content" title="<?php _e( 'Add Images', 'easingsliderpro' ); ?>"><span class="wp-media-buttons-icon"></span> <?php _e( 'Add Images', 'easingsliderpro' ); ?></a>
            <a href="#" id="delete-images" class="button button-secondary delete-images" title="<?php _e( 'Delete Images', 'easingsliderpro' ); ?>"><span class="wp-media-buttons-icon"></span> <?php _e( 'Delete Images', 'easingsliderpro' ); ?></a>
            <?php do_action( 'easingsliderpro_manage_slides_buttons', $s ); ?>
        </div>
        <div class="field">
            <label for="randomize">
                <input type="hidden" name="general[randomize]" value="">
                <input type="checkbox" id="randomize" name="general[randomize]" value="true" <?php checked( $s->general->randomize, true ); ?>><span style="display: inline;"><?php _e( 'Randomize the slideshow order.', 'easingsliderpro' ); ?></span>
            </label>
        </div>
        <?php do_action( 'easingsliderpro_manage_slides_metabox', $s ); ?>
    </div>
</div>

<!-- Dimensions -->
<div class="widgets-holder-wrap" <?php if ( (bool) apply_filters( 'easingsliderpro_hide_dimensions_metabox', __return_false() ) ) echo 'style="display: none;"'; ?>>
    <div class="sidebar-name">
        <div class="sidebar-name-arrow"></div>
        <h3><?php _e( 'Dimensions', 'easingsliderpro' ); ?></h3>
    </div>
    <div class="sidebar-content widgets-sortables clearfix">
        <div class="dimension-settings">
            <div class="field">
                <label for="width">
                    <span><?php _e( 'Width:', 'easingsliderpro' ); ?></span>
                    <input type="number" name="dimensions[width]" id="width" value="<?php echo esc_attr( $s->dimensions->width ); ?>">
                </label>
            </div>
            <div class="field">
                <label for="height">
                    <span><?php _e( 'Height:', 'easingsliderpro' ); ?></span>
                    <input type="number" name="dimensions[height]" id="height" value="<?php echo esc_attr( $s->dimensions->height ); ?>">
                </label>
            </div>
            <p class="description"><?php _e( 'Slideshow "width" and "height" values (in pixels).', 'easingsliderpro' ); ?></p>
        </div>
        <div class="divider"></div>
        <div>
            <div class="field">
                <label for="responsive">
                    <input type="hidden" name="dimensions[responsive]" value="">
                    <input type="checkbox" name="dimensions[responsive]" id="responsive" value="true" <?php checked( $s->dimensions->responsive, true ); ?>><span style="display: inline;"><?php _e( 'Make this slideshow responsive.', 'easingsliderpro' ); ?></span>
                </label>
            </div>
            <p class="description"><?php _e( 'Check this option to make this slideshow responsive. If enabled, the "width" and "height" values above will act as maximums.', 'easingsliderpro' ); ?></p>
        </div>
        <?php do_action( 'easingsliderpro_dimensions_metabox', $s ); ?>
    </div>
</div>

<!-- Transitions -->
<div class="widgets-holder-wrap closed" <?php if ( apply_filters( 'easingsliderpro_hide_transitions_metabox', __return_false() ) ) echo 'style="display: none;"'; ?>>
    <div class="sidebar-name">
        <div class="sidebar-name-arrow"></div>
        <h3><?php _e( 'Transitions', 'easingsliderpro' ); ?></h3>
    </div>
    <div class="sidebar-content widgets-sortables clearfix" style="display: none;">
        <div>
            <div class="field">
                <label for="effect">
                    <span><?php _e( 'Effect:', 'easingsliderpro' ); ?></span>
                    <select name="transitions[effect]" id="effect">
                        <option value="slide" <?php selected( $s->transitions->effect, 'slide' ); ?>><?php _e( 'Slide', 'easingsliderpro' ); ?></option>
                        <option value="fade" <?php selected( $s->transitions->effect, 'fade' ); ?>><?php _e( 'Fade', 'easingsliderpro' ); ?></option>
                    </select>
                </label>
            </div>
            <p class="description"><?php _e( 'Choose the transition effect you would like to use.', 'easingsliderpro' ); ?></p>
        </div>
        <div class="divider"></div>
        <div>
            <div class="field">
                <label for="duration">
                    <span><?php _e( 'Duration:', 'easingsliderpro' ); ?></span>
                    <input type="number" name="transitions[duration]" id="duration" value="<?php echo esc_attr( $s->transitions->duration ); ?>">
                </label>
            </div>
            <p class="description"><?php _e( 'Sets the duration (in milliseconds) for the slideshow transition.', 'easingsliderpro' ); ?></p>
        </div>
        <div class="divider"></div>
        <div>
            <div class="field">
                <label for="touch">
                    <input type="hidden" name="transitions[touch]" value="">
                    <input type="checkbox" id="touch" name="transitions[touch]" value="true" <?php checked( $s->transitions->touch, true ); ?>><span style="display: inline;"><?php _e( 'Enable touch gestures (when supported)', 'easingsliderpro' ); ?></span>
                </label>
            </div>
            <p class="description"><?php _e( 'Check this option to enable touch gestures on devices that support them. This allows the user to change slide by swiping across the slideshow.', 'easingsliderpro' ); ?></p>
        </div>
        <?php do_action( 'easingsliderpro_transitions_metabox', $s ); ?>
    </div>
</div>

<!-- Next & Previous Arrows -->
<div class="widgets-holder-wrap closed" <?php if ( apply_filters( 'easingsliderpro_hide_arrows_metabox', __return_false() ) ) echo 'style="display: none;"'; ?>>
    <div class="sidebar-name">
        <div class="sidebar-name-arrow"></div>
        <h3><?php _e( 'Next & Previous Arrows', 'easingsliderpro' ); ?></h3>
    </div>
    <div class="sidebar-content widgets-sortables" style="display: none;">
        <div>
            <div class="radio clearfix">
                <span><?php _e( 'Arrows:', 'easingsliderpro' ); ?></span>
                <div class="buttons">
                    <label for="arrows-enable"><input type="radio" name="navigation[arrows]" id="arrows-enable" value="true" <?php checked( $s->navigation->arrows, true ); ?>>
                        <span><?php _e( 'Enable', 'easingsliderpro' ); ?></span>
                    </label>
                    <label for="arrows-disable"><input type="radio" name="navigation[arrows]" id="arrows-disable" value="false" <?php checked( $s->navigation->arrows, false ); ?>>
                        <span><?php _e( 'Disable', 'easingsliderpro' ); ?></span>
                    </label>
                </div>
            </div>
            <p class="description"><?php _e( 'Toggles the next and previous slide arrows.', 'easingsliderpro' ); ?></p>
        </div>
        <div class="divider"></div>
        <div>
            <div class="radio clearfix">
                <span><?php _e( 'On Hover:', 'easingsliderpro' ); ?></span>
                <div class="buttons">
                    <label for="arrows-hover-true"><input type="radio" name="navigation[arrows_hover]" id="arrows-hover-true" value="true" <?php checked( $s->navigation->arrows_hover, true ); ?>>
                        <span><?php _e( 'True', 'easingsliderpro' ); ?></span>
                    </label>
                    <label for="arrows-hover-false"><input type="radio" name="navigation[arrows_hover]" id="arrows-hover-false" value="false" <?php checked( $s->navigation->arrows_hover, false ); ?>>
                        <span><?php _e( 'False', 'easingsliderpro' ); ?></span>
                    </label>
                </div>
            </div>
            <p class="description"><?php _e( 'Set to "True" to only show the arrows when the user hovers over the slideshow.', 'easingsliderpro' ); ?></p>
        </div>
        <div class="divider"></div>
        <div>
            <div class="field">
                <label for="arrows_position">
                    <span><?php _e( 'Position:', 'easingsliderpro' ); ?></span>
                    <select name="navigation[arrows_position]" id="arrows_position">
                        <option value="inside" <?php selected( $s->navigation->arrows_position, 'inside' ); ?>><?php _e( 'Inside', 'easingsliderpro' ); ?></option>
                        <option value="outside" <?php selected( $s->navigation->arrows_position, 'outside' ); ?>><?php _e( 'Outside', 'easingsliderpro' ); ?></option>
                    </select>
                </label>
            </div>
            <p class="description"><?php _e( 'Select a position for the arrows.', 'easingsliderpro' ); ?></p>
        </div>
        <?php do_action( 'easingsliderpro_arrows_metabox', $s ); ?>
    </div>
</div>

<!-- Pagination Icons -->
<div class="widgets-holder-wrap closed" <?php if ( apply_filters( 'easingsliderpro_hide_pagination_metabox', __return_false() ) ) echo 'style="display: none;"'; ?>>
    <div class="sidebar-name">
        <div class="sidebar-name-arrow"></div>
        <h3><?php _e( 'Pagination Icons', 'easingsliderpro' ); ?></h3>
    </div>
    <div class="sidebar-content widgets-sortables" style="display: none;">
        <div>
            <div class="radio clearfix">
                <span><?php _e( 'Pagination:', 'easingsliderpro' ); ?></span>
                <div class="buttons">
                    <label for="pagination-enable"><input type="radio" name="navigation[pagination]" id="pagination-enable" value="true" <?php checked( $s->navigation->pagination, true ); ?>>
                        <span><?php _e( 'Enable', 'easingsliderpro' ); ?></span>
                    </label>
                    <label for="pagination-disable"><input type="radio" name="navigation[pagination]" id="pagination-disable" value="false" <?php checked( $s->navigation->pagination, false ); ?>>
                        <span><?php _e( 'Disable', 'easingsliderpro' ); ?></span>
                    </label>
                </div>
            </div>
            <p class="description"><?php _e( 'Enable/Disable the Pagination Icons. Each icon represents a slide in their respective order.', 'easingsliderpro' ); ?></p>
        </div>
        <div class="divider"></div>
        <div>
            <div class="radio clearfix">
                <span><?php _e( 'On Hover:', 'easingsliderpro' ); ?></span>
                <div class="buttons">
                    <label for="pagination-hover-true"><input type="radio" name="navigation[pagination_hover]" id="pagination-hover-true" value="true" <?php checked( $s->navigation->pagination_hover, true ); ?>>
                        <span><?php _e( 'True', 'easingsliderpro' ); ?></span>
                    </label>
                    <label for="pagination-hover-false"><input type="radio" name="navigation[pagination_hover]" id="pagination-hover-false" value="false" <?php checked( $s->navigation->pagination_hover, false ); ?>>
                        <span><?php _e( 'False', 'easingsliderpro' ); ?></span>
                    </label>
                </div>
            </div>
            <p class="description"><?php _e( 'Set to "True" to only show the pagination when the user hovers over the slideshow.', 'easingsliderpro' ); ?></p>
        </div>
        <div class="divider"></div>
        <div>
            <div class="field">
                <label for="pagination_position">
                    <span><?php _e( 'Position:', 'easingsliderpro' ); ?></span>
                    <select name="navigation[pagination_position]" id="pagination_position" style="width: 45%; float: left;">
                        <option value="inside" <?php selected( $s->navigation->pagination_position, 'inside' ); ?>><?php _e( 'Inside', 'easingsliderpro' ); ?></option>
                        <option value="outside" <?php selected( $s->navigation->pagination_position, 'outside' ); ?>><?php _e( 'Outside', 'easingsliderpro' ); ?></option>
                    </select>
                    <select name="navigation[pagination_location]" id="pagination_location" style="width: 45%; float: left; margin-left: 10px;">
                        <option value="top-left" <?php selected( $s->navigation->pagination_location, 'top-left' ); ?>><?php _e( 'Top Left', 'easingsliderpro' ); ?></option>
                        <option value="top-right" <?php selected( $s->navigation->pagination_location, 'top-right' ); ?>><?php _e( 'Top Right', 'easingsliderpro' ); ?></option>
                        <option value="top-center" <?php selected( $s->navigation->pagination_location, 'top-center' ); ?>><?php _e( 'Top Center', 'easingsliderpro' ); ?></option>
                        <option value="bottom-left" <?php selected( $s->navigation->pagination_location, 'bottom-left' ); ?>><?php _e( 'Bottom Left', 'easingsliderpro' ); ?></option>
                        <option value="bottom-right" <?php selected( $s->navigation->pagination_location, 'bottom-right' ); ?>><?php _e( 'Bottom Right', 'easingsliderpro' ); ?></option>
                        <option value="bottom-center" <?php selected( $s->navigation->pagination_location, 'bottom-center' ); ?>><?php _e( 'Bottom Center', 'easingsliderpro' ); ?></option>
                    </select>
                </label>
            </div>
            <p class="description"><?php _e( 'Select a position for the pagination icons.', 'easingsliderpro' ); ?></p>
        </div>
        <?php do_action( 'easingsliderpro_pagination_metabox', $s ); ?>
    </div>
</div>

<!-- Playback -->
<div class="widgets-holder-wrap closed" <?php if ( apply_filters( 'easingsliderpro_hide_playback_metabox', __return_false() ) ) echo 'style="display: none;"'; ?>>
    <div class="sidebar-name">
        <div class="sidebar-name-arrow"></div>
        <h3><?php _e( 'Automatic Playback', 'easingsliderpro' ); ?></h3>
    </div>
    <div class="sidebar-content widgets-sortables" style="display: none;">
        <div>
            <div class="radio clearfix">
                <span><?php _e( 'Playback:', 'easingsliderpro' ); ?></span>
                <div class="buttons">
                    <label for="playback-enable"><input type="radio" name="playback[enabled]" id="playback-enable" value="true" <?php checked( $s->playback->enabled, true ); ?>>
                        <span><?php _e( 'Enable', 'easingsliderpro' ); ?></span>
                    </label>
                    <label for="playback-disable"><input type="radio" name="playback[enabled]" id="playback-disable" value="false" <?php checked( $s->playback->enabled, false ); ?>>
                        <span><?php _e( 'Disable', 'easingsliderpro' ); ?></span>
                    </label>
                </div>
            </div>
            <p class="description"><?php _e( 'Enable/Disable slideshow automatic playback.', 'easingsliderpro' ); ?></p>
        </div>
        <div class="divider"></div>
        <div>
            <div class="field">
                <label for="playback_pause">
                    <span><?php _e( 'Pause Duration:', 'easingsliderpro' ); ?></span>
                    <input type="number" name="playback[pause]" id="playback_pause" value="<?php echo esc_attr( $s->playback->pause ); ?>">
                </label>
            </div>
            <p class="description"><?php _e( 'Sets the duration (in milliseconds) for the pause between slide transitions.', 'easingsliderpro' ); ?></p>
        </div>
        <?php do_action( 'easingsliderpro_playback_metabox', $s ); ?>
    </div>
</div>
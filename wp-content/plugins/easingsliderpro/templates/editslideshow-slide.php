<!-- Backbone template -->
<div class="editslideview media-modal wp-core-ui">
    <a class="media-modal-close" href="#" title="Close"><span class="media-modal-icon"></span></a>
    <div class="media-modal-content">
        <div class="media-frame wp-core-ui">
            <div class="media-frame-menu">
                <div class="media-menu">
                    <a href="#" data-tab="general" class="media-menu-item active"><?php _e( 'General Settings', 'easingsliderpro' ); ?></a>
                    <a href="#" data-tab="html" class="media-menu-item"><?php _e( 'HTML Markup', 'easingsliderpro' ); ?></a>
                </div>
            </div>

            <div class="media-frame-title">
                <h1><?php _e( 'Edit a Slide: #{{ data.id }}', 'easingsliderpro' ); ?></h1>
            </div>

            <div class="media-frame-router" style="height: 0;"></div>

            <div class="media-frame-content" style="top: 45px;">
                <div class="media-main media-embed">
                    <div id="general" class="media-tab">
                        <div class="embed-link-settings" style="top: 0;">
                            <div class="thumbnail">
                                <# var thumbnail = data.sizes.large || data.sizes.medium || data.sizes.thumbnail || { url: data.url }; #>
                                <img src="{{ thumbnail.url }}" class="slide-thumbnail" alt="{{ data.alt }}" />
                                <a href="#" id="change-image" class="button button-primary button-large change-image" data-editor="content" title="<?php _e( 'Change Image', 'easingsliderpro' ); ?>"><span class="wp-media-buttons-icon"></span> <?php _e( 'Change Image', 'easingsliderpro' ); ?></a>
                            </div>

                            <label for="link" class="setting">
                                <span><?php _e( 'Link URL', 'easingsliderpro' ); ?></span>
                                <input type="text" id="link" value="{{ data.link }}">
                                <select id="linkTarget" style="margin-top: 5px;">
                                    <option value="_self" <# ( '_self' == data.linkTarget ) ? print('selected="selected"'): ''; #>><?php _e( 'Open link same tab/window', 'easingsliderpro' ); ?></option>
                                    <option value="_blank" <# ( '_blank' == data.linkTarget ) ? print('selected="selected"'): ''; #>><?php _e( 'Open link in new tab/window', 'easingsliderpro' ); ?></option>
                                </select>
                                <p class="description"><?php _e( 'Enter a slide link. Videos will automatically be detected and played within the slideshow when clicked.', 'easingsliderpro' ); ?></p>
                            </label>

                            <label for="title" class="setting">
                                <span><?php _e( 'Title', 'easingsliderpro' ); ?></span>
                                <input type="text" id="title" value="{{ data.title }}">
                                <p class="description"><?php _e( 'Enter a value for the image "title" attribute.', 'easingsliderpro' ); ?></p>
                            </label>

                            <label for="alt" class="setting">
                                <span><?php _e( 'Alt Text', 'easingsliderpro' ); ?></span>
                                <input type="text" id="alt" value="{{ data.alt }}">
                                <p class="description"><?php _e( 'Enter a value for the image "alt" text attribute.', 'easingsliderpro' ); ?></p>
                            </label>
                        </div>
                    </div>

                    <div id="html" class="media-tab" style="display: none;">
                        <div class="embed-link-settings" style="top: 0;">
                            <label for="content" class="setting">
                                <span><?php _e( 'HTML Markup', 'easingsliderpro' ); ?></span>
                                <textarea id="content"><# print( _.unescape(data.content) ) #></textarea>
                                <p class="description"><?php _e( 'Here you can add HTML markup to the slide.', 'easingsliderpro' ); ?></p>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="media-frame-toolbar">
                <div class="media-toolbar">
                    <div class="media-toolbar-primary">
                        <a href="#" class="button media-button button-primary button-large media-button-select media-modal-save"><?php _e( 'Apply Changes', 'easingsliderpro' ); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="media-modal-backdrop"></div>
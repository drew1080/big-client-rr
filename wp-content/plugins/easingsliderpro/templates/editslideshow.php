<?php

    /** Flag for telling script that we are editing a slideshow (or not) */
    $editing = ( isset( $_GET['edit'] ) ) ? true : false;

    /** Get the slideshow if we are editing, or get the default values if we are creating a new one */
    if ( isset( $_GET['edit'] ) )
        $slideshow = $s = ESP_Database::get_instance()->get_slideshow( $_GET['edit'] );
    else
        $slideshow = $s = ESP_Database::get_instance()->get_slideshow_defaults();

?>
<div class="wrap">
    <form name="post" action="admin.php?page=easingsliderpro_edit_slideshows&amp;edit=<?php echo esc_attr( $s->id ); ?>" method="post">
        <div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
        <h2><?php printf( __( '%s Slideshow: ', 'easingsliderpro' ), ( $editing ) ? 'Edit' : 'Add' ); ?><input type="text" name="name" id="name" size="30" autocomplete="off" placeholder="<?php _e( 'Enter a slideshow name', 'easingsliderpro' ); ?>" value="<?php echo esc_attr( $s->name ); ?>"></h2>   
        <?php
            /** Security nonce field */
            wp_nonce_field( "easingsliderpro-save_easingsliderpro_edit_slideshows", "easingsliderpro-save_easingsliderpro_edit_slideshows", false );
        ?>
        <div class="main-panel">
            <div class="messages-container">
                <?php do_action( 'easingsliderpro_admin_messages' ); ?>
            </div>

            <div class="clearfix">
                <div class="thumbnails-container">
                    <div class="inner clearfix">
                        <?php
                            /** We display the current slides anyway using PHP (rather than Javascript) to avoid any rendering delays */
                            if ( $s->slides )
                                foreach ( $s->slides as $slide )
                                    echo "<div class='thumbnail' data-id='{$slide->id}'><a href='#' class='delete-button'></a><img src='{$slide->sizes->thumbnail->url}' alt='{$slide->alt}' /></div>";
                        ?>
                    </div>
                </div>
                <div class="settings-container">
                    <?php require 'editslideshow-sidebar.php'; ?>
                </div>
            </div>
            <div class="divider"></div>

            <input type="hidden" name="author" value="<?php echo esc_attr( $s->author ); ?>">
            <input type="hidden" name="slides" id="slideshow-images" value="">
            <input type="submit" name="save" class="button button-primary button-large" id="save" accesskey="p" value="<?php _e( 'Save Slideshow', 'easingsliderpro' ); ?>">
            <?php /** This ensures that the slide's JSON is encoded correctly. Using PHP JSON encode can cause magic quote issues */ ?>
            <script type="text/javascript">document.getElementById('slideshow-images').value = '<?php echo addslashes( json_encode( $s->slides ) ); ?>';</script>
        </div>
    </form>
</div>
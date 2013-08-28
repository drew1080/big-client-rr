<?php

    /** Get all of the slideshows */
    $slideshows = ESP_Database::get_instance()->get_all_slideshows();

    /** Store the current page */
    $page = $_GET['page'];

?>
<div class="wrap">
    <div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
    <h2><?php _e( 'Slideshows ', 'easingsliderpro' ); ?>
        <a href="admin.php?page=easingsliderpro_add_slideshow" class="add-new-h2"><?php _e( 'Add New', 'easingsliderpro' ); ?></a>
        <?php if ( isset( $_GET['s'] ) && !empty( $_GET['s'] ) ) { ?><span class="subtitle"><?php printf( __( 'Search results for “%s”', 'easingsliderpro' ), $_GET['s'] ); ?></span><?php } ?>
    </h2>
    <form id="welcome-actions" action="" method="post">
        <?php
            /** Display welcome message */
            require 'editslideshow-welcome.php';
        ?>
    </form>
    <form id="posts-filter" action="" method="get">
        <input type="hidden" name="page" id="page" value="<?php echo $page; ?>" />
        <p class="search-box">
            <label class="screen-reader-text" for="post-search-input"><?php _e( 'Search Slideshows:', 'easingsliderpro' ); ?></label>
            <input type="search" id="post-search-input" name="s" value="<?php if ( isset( $_GET['s'] ) ) echo esc_attr( $_GET['s'] ); ?>">
            <input type="submit" name="" id="search-submit" class="button" value="<?php _e( 'Search Slideshows', 'easingsliderpro' ); ?>">
        </p>
    </form>
    <div class="messages-container">
        <?php do_action( 'easingsliderpro_admin_messages' ); ?>
    </div>
    <form id="slideshows-list" action="admin.php?page=<?php echo $page; ?>" method="get">
        <input type="hidden" name="page" id="page" value="<?php echo $page; ?>" />
        <?php
            /** Security nonce field */
            wp_nonce_field( "easingsliderpro-bulk_{$_GET['page']}", "easingsliderpro-bulk_{$_GET['page']}", false );
        ?>
        <div class="tablenav top">
            <div class="alignleft actions">
                <select name="action" id="action">
                    <option value="-1" selected="selected"><?php _e( 'Bulk Actions', 'easingsliderpro' ); ?></option>
                    <option value="duplicate"><?php _e( 'Duplicate', 'easingsliderpro' ); ?></option>
                    <option value="delete"><?php _e( 'Delete', 'easingsliderpro' ); ?></option>
                </select>
                <input type="submit" name="" id="doaction" class="button action" value="Apply">
            </div>
            <br class="clear">
        </div>
        <table class="wp-list-table widefat fixed posts" cellspacing="0">
            <?php foreach ( array( 'thead', 'tfoot' ) as $element ) : ?>
            <<?php echo $element; ?>>
                <tr>
                    <th scope="col" id="cb" class="manage-column column-cb check-column" style="">
                        <label class="screen-reader-text" for="cb-select-all-1"><?php _e( 'Select All', 'easingsliderpro' ); ?></label><input id="cb-select-all-1" type="checkbox">
                    </th>
                    <th scope="col" id="id" class="manage-column column-id" style="">
                        <span><?php _e( 'ID', 'easingsliderpro' ); ?></span><span class="sorting-indicator"></span>
                    </th>
                    <th scope="col" id="name" class="manage-column column-name <?php echo ( isset( $_GET['orderby'] ) ) ? 'sorted ' : 'sortable '; echo ( isset( $_GET['order'] ) && $_GET['order'] == 'asc' ) ? 'desc' : 'asc'; ?>" style="">
                        <a href="admin.php?page=<?php echo $page; ?>&amp;orderby=name&amp;order=<?php echo ( isset( $_GET['order'] ) && $_GET['order'] == 'asc' ) ? 'desc' : 'asc'; ?>">
                            <span><?php _e( 'Title', 'easingsliderpro' ); ?></span><span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th scope="col" id="author" class="manage-column column-author" style=""><?php _e( 'Author', 'easingsliderpro' ); ?></th>
                </tr>
            </<?php echo $element; ?>>
            <?php endforeach; ?>
            <tbody>
            <?php if ( empty( $slideshows ) ) : ?>
                <tr class="no-items">
                    <td class="colspanchange" colspan="4"><?php _e( 'No slideshows found.', 'easingsliderpro' ); ?></td>
                </tr>
            <?php else : foreach ( $slideshows as $index => $s ) : ?>
                <tr id="slideshow-<?php echo esc_attr( $s->id ); ?>" class="slideshow-<?php echo esc_attr( $s->id ); ?> <?php if ( $index+1 & 1 ) echo 'alternate'; ?>" valign="top">
                    <th scope="row" class="check-column">
                        <label class="screen-reader-text" for="cb-select-<?php echo esc_attr( $s->id ); ?>"><?php _e( 'Select ', 'easingsliderpro' ); ?><?php echo esc_html( $s->name ); ?></label>
                        <input id="cb-select-<?php echo esc_attr( $s->id ); ?>" type="checkbox" name="id[]" value="<?php echo esc_attr( $s->id ); ?>">
                    </th>
                    <td class="slideshow-id column-id">
                        <?php echo esc_html( $s->id ); ?>
                    </td>
                    <td class="slideshow-name column-name">
                        <strong>
                            <a class="row-name" href="admin.php?page=<?php echo $page; ?>&amp;edit=<?php echo esc_attr( $s->id ); ?>" title="<?php printf( __( 'Edit &#8220;%s&#8221;', 'easingsliderpro' ), $s->name ); ?>"><?php echo esc_html( $s->name ); ?></a>
                        </strong>
                        <div class="row-actions">
                            <?php

                                /** Get our plugin class instance. This isn't required (could use $this), but is handy if we ever want to use this file outside of the plugin class */
                                $plugin = EasingSliderPro::get_instance();

                                /** Let's define our URLs here for brevity */
                                $duplicate = $plugin->nonce_url( "admin.php?page={$page}&id={$s->id}&action=duplicate", "easingsliderpro-duplicate_{$page}", "easingsliderpro-duplicate_{$page}" );
                                $delete = $plugin->nonce_url( "admin.php?page={$page}&id={$s->id}&action=delete", "easingsliderpro-delete_{$page}", "easingsliderpro-delete_{$page}" );

                            ?>
                            <span class="edit"><a href="admin.php?page=<?php echo $page; ?>&amp;edit=<?php echo esc_attr( $s->id ); ?>" title="<?php _e( 'Edit this slideshow', 'easingsliderpro' ); ?>"><?php _e( 'Edit', 'easingsliderpro' ); ?></a> | </span>
                            <span class="duplicate"><a href="<?php echo esc_url( $duplicate ); ?>" title="<?php _e( 'Duplicate this slideshow', 'easingsliderpro' ); ?>"><?php _e( 'Duplicate', 'easingsliderpro' ); ?></a> | </span>
                            <span class="trash"><a href="<?php echo esc_url( $delete ); ?>" class="submitdelete" title="<?php _e( 'Delete this slideshow', 'easingsliderpro' ); ?>"><?php _e( 'Delete', 'easingsliderpro' ); ?></a></span>
                        </div>
                    </td>
                    <td class="author column-author">
                        <a href="admin.php?page=<?php echo $page; ?>&amp;filterby=author&amp;filter=<?php echo esc_attr( $s->author ); ?>"><?php echo esc_html( $s->author ); ?></a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
        <div class="tablenav bottom">
            <div class="alignleft actions">
                <select name="action2" id="action2">
                    <option value="-1" selected="selected"><?php _e( 'Bulk Actions', 'easingsliderpro' ); ?></option>
                    <option value="duplicate"><?php _e( 'Duplicate', 'easingsliderpro' ); ?></option>
                    <option value="delete"><?php _e( 'Delete', 'easingsliderpro' ); ?></option>
                </select>
                <input type="submit" name="" id="doaction2" class="button action" value="<?php _e( 'Apply', 'easingsliderpro' ); ?>">
            </div>
            <br class="clear">
        </div>
    </form>
</div>
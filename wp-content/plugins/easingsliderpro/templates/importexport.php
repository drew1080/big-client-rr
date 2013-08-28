<?php
    /** Get all of the slideshows */
    $slideshows = ESP_Database::get_instance()->get_all_slideshows();
?>
<div class="wrap">
    <div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
    <h2><?php _e( 'Import/Export Slideshows', 'easingsliderpro' ); ?></h2>
    <form action="admin.php?page=easingsliderpro_import_export_slideshows" method="post" enctype="multipart/form-data">
        <?php
            /** Security nonce field */
            wp_nonce_field( "easingsliderpro-import_{$_GET['page']}", "easingsliderpro-import_{$_GET['page']}", false );
        ?>
        <div class="main-panel">
            <div class="messages-container">
                <?php do_action( 'easingsliderpro_admin_messages' ); ?>
            </div>

            <h3><?php _e( 'Import Slideshows', 'easingsliderpro' ); ?></h3>
            <table class="form-table import">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><label for="import_file"><?php _e( 'Upload slideshows file', 'easingsliderpro' ); ?></label></th>
                        <td>
                            <input type="file" name="import_file" id="import_file">
                            <p>
                                <input type="hidden" name="replace_urls" value="">
                                <label for="replace_urls">
                                    <input type="checkbox" name="replace_urls" id="replace_urls" value="true">
                                    <span class=""><?php _e( 'Replace domain name within URLs (check this if you are changing domain name).', 'easingsliderpro' ); ?></span>
                                </label>
                            </p>
                            <p class="submit">
                                <input type="submit" name="import" class="button button-secondary" value="<?php _e( 'Import Slideshows', 'easingsliderpro' ); ?>">
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>

    <div class="divider"></div>

    <form action="admin.php?page=easingsliderpro_import_export_slideshows" method="post">
        <?php
            /** Security nonce field */
            wp_nonce_field( "easingsliderpro-export_{$_GET['page']}", "easingsliderpro-export_{$_GET['page']}", false );
        ?>
        <div class="main-panel">
            <h3><?php _e( 'Export Slideshows', 'easingsliderpro' ); ?></h3>
            <table class="form-table export">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><span><?php _e( 'Select slideshows to export', 'easingsliderpro' ); ?></span></th>
                        <td>
                            <div class="multiple-checkbox" style="width: 50%;">
                                <ul>
                                    <li>
                                        <label for="all">
                                            <input type="checkbox" name="-1" id="all" class="select-all" value="-1"><strong><?php _e( 'Select all slideshows', 'easingsliderpro' ); ?></strong>
                                        </label>
                                    </li>
                                    <?php
                                    if ( !empty( $slideshows ) ) :
                                        foreach ( $slideshows as $index => $s ) :
                                            ?>
                                            <li class="<?php if ( $index+1 & 1 ) echo 'odd'; ?>">
                                                <label for="<?php echo esc_attr( $s->id ); ?>">
                                                    <input type="checkbox" name="id[]" id="<?php echo esc_attr( $s->id ); ?>" value="<?php echo esc_attr( $s->id ); ?>"><?php echo esc_html( $s->name ); ?><?php printf( __( ' (ID #%s)', 'easingsliderpro' ), $s->id ); ?>
                                                </label>
                                            </li>
                                            <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </ul>
                            </div>
                            <p class="submit">
                                <input type="submit" name="export" class="button button-primary" value="<?php _e( 'Export Slideshows', 'easingsliderpro' ); ?>">
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>
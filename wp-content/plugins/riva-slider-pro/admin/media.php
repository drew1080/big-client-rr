<?php

/**
 * Class for adding a Media Uploader tab
 *
 * @author Matthew Ruddy
 * @since 1.0.6
 * 
 **/
class RSPMediaUploader {
    
    var $action = null;
    var $uri = null;
    var $search = array();
    var $fields = array();
    
    /**
     * Setup the class
     *
     * @author Matthew Ruddy
     * @since 1.0.6
     * 
     **/
    function __construct() {
        // Only initiate if value isset
        if ( !isset( $_GET[ 'tab' ] ) )
            return;
	
	if ( $_GET[ 'tab' ] != 'riva_slider_pro_tab' )
	    return;
        
        // Action
        add_filter( 'media_upload_tabs', array( $this, 'tab' ) );
        add_action( 'media_upload_riva_slider_pro_tab', array( $this, 'iframe' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
        add_action( 'admin_print_scripts', array( $this, 'print_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'styles' ) );
        
        // Set the URI
        $this->uri = $_SERVER[ 'REQUEST_URI' ];
        
        // Remove filter/search is not filtering/searching
        if ( !isset( $_GET[ 'dofilter' ] ) && isset( $_GET[ 'filter' ] ) && $_GET[ 'filter' ] == -1 )
            $this->uri = remove_query_arg( array( 'filter' ), $this->uri );
        if ( !isset( $_GET[ 'dosearch' ] ) && isset( $_GET[ 'search' ] ) && empty( $_GET[ 'search' ] ) )
            $this->uri = remove_query_arg( array( 'search' ), $this->uri );
        
        // Remove 'do's
        $this->uri = remove_query_arg( array( 'dofilter', 'dosearch' ), $this->uri );
        
        // Construct the hidden fields
        $this->fields = $this->constructfields();
    }
    
    
    
    /**
     * Adds the tab
     *
     * @author Matthew Ruddy
     * @since 1.0.6
     * 
     **/
    function tab( $tabs ) {
        $tab = array( 'riva_slider_pro_tab' => __( 'Riva Slider Pro', 'riva_slider_pro' ) );
        return array_merge( $tabs, $tab );
    }
    
    
    
    /**
     * Tab iframe
     *
     * @author Matthew Ruddy
     * @since 1.0.6
     * 
     **/
    function iframe( $errors ) {
        return wp_iframe( array( $this, 'content' ), $errors );
    }
    
    
    
    /**
     * Tab scripts
     *
     * @author Matthew Ruddy
     * @since 1.0.6
     * 
     **/
    function scripts() {
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'rsp-admin-media', riva_slider_pro_path( 'scripts/jquery.rs.pro.media.js' ), array( 'jquery' ), RIVA_VERSION, false );
    }
    
    
    
    /**
     * Tab printed scripts
     *
     * @author Matthew Ruddy
     * @since 1.0.6
     * 
     **/
    function print_scripts() {
	?>
	<script type="text/javascript">
	//<![CDATA[
	var sendImagetoPlugin = {
	    
	    insert : function(id) {
		    var loc = window.location.href;
		    var win = window.dialogArguments || opener || parent || top;
		    var parent = jQuery('#rs-media-item-'+ id);
		    var image_url = jQuery('#rs-image-url', parent).val();
		    var image_title = jQuery('#rs-image-title', parent).val();
		    var image_alt = jQuery('#rs-image-alt', parent).val();
		    var image_resized = jQuery('#rs-image-resized', parent).val();
		    var html = '<a href="'+ image_resized +'"><img src="'+ image_url +'" title="'+ image_title +'" alt="'+ image_alt +'" /></a>';
		    if ( loc.indexOf( 'change=true' ) != -1 ) {
			var id = loc.split( 'riva_id=' );
			id = id[ 1 ].split( '&' );
			id = id[ 0 ];
			win.riva_change_image(html, id);
		    } else {
			win.riva_send_image_to_plugin(html);
		    }
		    return false;
	    }
	    
	}
	//]]>
	</script>
        <?php
    }
    
    
    
    /**
     * Tab styles
     *
     * @author Matthew Ruddy
     * @since 1.0.6
     * 
     **/
    function styles() {
        wp_enqueue_style( 'media' );
        wp_enqueue_style( 'rsp-admin-media', riva_slider_pro_path( 'scripts/jquery.rs.pro.admin.thickbox.css' ), null, RIVA_VERSION );
    }
    
    
    
    /**
     * Contruct the dates array to be filtered
     *
     * @author Matthew Ruddy
     * @since 1.0.6
     * 
     **/
    function dates( $array ) {
        // Check we have an array
        if ( !is_array( $array ) )
            return null;
        
        // Construct the array
        $dates = array();
        foreach ( $array as $image ) {
            if ( !isset( $image->post_date ) )
                continue;
            $date = explode( '-', $image->post_date );
            $date = $date[ 0 ] .'-'. $date[ 1 ];
            $format = new DateTime( $date );
            if ( !in_array( $format->format( 'F Y' ), $dates ) )
                $dates = array_merge( $dates, array( $date => $format->format( 'F Y' ) ) );
        }
        
        // Return the array
        return $dates;
    }
    
    
    
    /**
     * Filter the media by date
     *
     * @author Matthew Ruddy
     * @since 1.0.6
     * 
     **/
    function filter( $query, $value ) {
        // Check we have an array
        if ( !is_array( $query ) )
            return $query;
        
        // Break the time into month and year
        $time = explode( '-', $value );
        $month = $time[ 1 ];
        $year = $time[ 0 ];
        
        // Modify query array
        $query = array_merge( $query, array( 'monthnum' => $month, 'year' => $year ) );
        
        // Return query array
        return $query;
    }
    
    
    
    /**
     * Search through media
     *
     * @author Matthew Ruddy
     * @since 1.0.6
     * 
     **/
    function search( $query, $value ) {
        // Check we have an array
        if ( !is_object( $query ) )
            return $query;
        
        if ( !isset( $query->posts ) && !is_array( $query->posts ) )
            return $query;
        
        foreach ( $query->posts as $item ) {
            if ( !isset( $item->post_title ) )
                continue;
            
            if ( stripos( $item->post_title, $value ) === false ) {
                if ( isset( $item->ID ) )
                    array_push( $this->search, $item->ID );
            }
        }
        
        // Return query
        return $query;
    }
    
    

    /**
     * Construct the hidden input fields
     *
     * @author Matthew Ruddy
     * @since 1.0.6
     * 
     **/
    function constructfields() {
        $fields = array(
            array(
                'id' => 'guid'
            ),
            array(
                'id' => 'resizedimg',
                'function' => array(
                    'function' => 'wp_get_attachment_image_src',
                    'params' => array( 'thumbnail' ),
                    'key' => 0
                )
            )
        );
        return $fields;
    }
    
    

    /**
     * Tab pagination
     *
     * @author Matthew Ruddy
     * @since 1.0.6
     * 
     **/
    function pagination( $type, $var, $count = null ) {
        switch ( $type ) {
            
            case 'value' :
                if ( isset( $_GET[ $var ] ) )
                    return $_GET[ $var ];
                else
                    return '1';
                break;
            
            case 'list' :
                if ( is_null( $count ) )
                    return null;
                
                // Some variables
                $current = 1;
                $uri = remove_query_arg( array( $var ), $this->uri );
                
                // Change $current to the current page if it is set
                if ( isset( $_GET[ $var ] ) )
                   $current = $_GET[ $var ];
                
                // Check for filters
                if ( isset( $_GET[ 'filter' ] ) && $_GET[ 'filter' ] != -1 )
                    $uri = add_query_arg( array( 'dofilter' => true ), $uri );
                
                // Begin $string
                $string = '<span><i>'. __( 'Pages: ', 'riva_slider_pro' ) .'</i></span>';
                
                // Take appropriate actions if we are already on the first page
                if ( ( $current-1 ) <= 0 )
                    $atts = array( $var => '1', 'disabled' => 'disabled' );
                else
                    $atts = array( $var => ( $current-1 ), 'disabled' => null );
                $string .= '<a href="'. $uri .'" class="first-page '. $atts[ 'disabled' ] .'">&laquo;</a>';
                $string .= '<a href="'. add_query_arg( array( $var => $atts[ $var ] ), $uri ) .'" class="prev-page '. $atts[ 'disabled' ] .'">&lsaquo;</a>';
                
                // Display the page
                $string .= '<span class="paging-input"><input type="text" class="current-page" name="'. $var .'" id="page" title="'. __( 'Current Page', 'riva_slider_pro' ) .'" value="'. esc_attr( $current ) .'" size="1" />'. __( ' of ', 'riva_slider_pro' ) . $count .'</span>';
                
                // Take appropriate actions if we are already on the last page
                if ( ( $current+1 ) > $count )
                    $atts = array( $var => $count, 'disabled' => 'disabled' );
                else
                    $atts = array( $var => ( $current+1 ), 'disabled' => null );
                $string .= '<a href="'. add_query_arg( array( $var => $atts[ $var ] ), $uri ) .'" class="next-page '. $atts[ 'disabled' ] .'">&rsaquo;</a>';
                $string .= '<a href="'. add_query_arg( array( $var => $count ), $uri ) .'" class="last-page '. $atts[ 'disabled' ] .'">&raquo;</a>';
                
                return $string;
                break;
            
        }
    }
    
    

    /**
     * Tab content
     *
     * @author Matthew Ruddy
     * @since 1.0.6
     * 
     **/
    function content( $errors ) {
        // Global variables
        global $post;
        
        // Establish other variables
        $posts_per_page = 52;
        $var = 'rsp_page';
        
        // Display the media uploader tabs
        media_upload_header();
        
        // Query array
        $query = array(
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'post_status' => 'inherit',
            'posts_per_page' => -1
        );
        
        // Custom Wordpress query used to get the images from the Media Library
        $wp_query = new WP_Query( $query );
        
        // Construct post dates for date filter
        $dates = self::dates( $wp_query->posts );
        
        // Check if we have set any filters
        if ( isset( $_GET[ 'filter' ] ) && $_GET[ 'filter' ] != -1 ) {
            $query = self::filter( $query, $_GET[ 'filter' ] );
            $wp_query->query( $query );
        }
        
        // Search function
        if ( isset( $_GET[ 'search' ] ) && !empty( $_GET[ 'search' ] ) )
            $wp_query = self::search( $wp_query, $_GET[ 'search' ] );
        
        // Modify the query for pagination
        $query = array_merge( $query,
            array(
                'paged' => self::pagination( 'value', $var ),
                'posts_per_page' => $posts_per_page,
                'post__not_in' => $this->search
            )
        );
        $wp_query->query( $query );
        
        // Set the pagination
        $pagination = null;
        if ( $wp_query->max_num_pages > 0 )
            $pagination = self::pagination( 'list', $var, $wp_query->max_num_pages );
        
        ?>
        <!-- Tab content -->
        <form action="<?php echo esc_url_raw( $this->uri ); ?>" method="get" class="rsp-media-form">
            <input type="hidden" name="post_id" id="post_id" value="0" />
            <input type="hidden" name="riva_slider_pro" id="riva_slider_pro" value="true" />
            <input type="hidden" name="tab" id="tab" value="riva_slider_pro_tab" />
            <div class="tablenav top">
                <div class="rivasliderpro-media-top">
                    <input type="submit" name="dosearch" id="dosearch" class="button-secondary" value="<?php _e( 'Search Media', 'riva_slider_pro' ); ?>" />
                    <input type="text" name="search" id="rsp-search" value="<?php if ( isset( $_GET[ 'search' ] ) ) echo $_GET[ 'search' ]; ?>" />
                </div>
            </div>
            <div class="tablenav top">
                <div class="rivasliderpro-media-top">
                    <select name="filter">
                        <option value="-1"><?php _e( 'Show all dates', 'riva_slider_pro' ); ?></option>
                        <?php if ( is_array( $dates ) ) : ?>
                        <?php foreach ( $dates as $key => $value ) : ?>
                        <option value="<?php echo esc_attr( $key ); ?>" <?php if ( isset( $_GET[ 'filter' ] ) ) selected( $_GET[ 'filter' ], $key ); ?>><?php echo esc_html( $value ); ?></option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <input type="submit" name="dofilter" id="dofilter" class="button-secondary" value="<?php _e( 'Filter', 'riva_slider_pro' ); ?>" />
                    <div class="rsp-page-icons tablenav-pages">
                        <span class="pagination-links">
                            <?php echo $pagination; ?>
                        </span>
                    </div>
                </div>
            </div>
	</form>
	<!-- Tab content -->
	<div id="rs-media-items">   
	    <ul id="rs-media-items-list">
	
	    <?php foreach ( $wp_query->posts as $image ) {
    
		$thumb_url = wp_get_attachment_image_src( $image->ID, 'thumbnail' );
		$thumb_url = $thumb_url[ 0 ];
		$resized_url = wp_get_attachment_image_src( $image->ID, 'riva-slider-pro-admin-image' );
		$resized_url = $resized_url[ 0 ]; ?>
	
		<li id="rs-media-item-<?php echo esc_attr( $image->ID ); ?>">
		    <img src="<?php echo esc_url_raw( $thumb_url ); ?>" />
		    <a href="" onclick="sendImagetoPlugin.insert(<?php echo esc_attr( $image->ID ); ?>)" class="button-primary use-this-image"><?php esc_attr_e( 'Use this image', 'riva_slider_pro' ); ?></a>
		    <input type="hidden" name="rs-image-url" id="rs-image-url" value="<?php echo esc_url_raw( $image->guid ); ?>" />
		    <input type="hidden" name="rs-image-title" id="rs-image-title" value="<?php echo esc_attr( $image->post_title ); ?>" />
		    <input type="hidden" name="rs-image-alt" id="rs-image-alt" value="<?php echo esc_attr( $image->post_excerpt ); ?>" />
		    <input type="hidden" name="rs-image-resized" id="rs-image-resized" value="<?php echo esc_url_raw( $resized_url ); ?>" />
		</li>
		
	    <?php } ?>
	
	    </ul>
	</div>
        <?php
    }
    
}

?>
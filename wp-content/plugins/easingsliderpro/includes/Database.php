<?php

/**
 * Database connection singleton
 *
 * @author Matthew Ruddy
 * @since 2.0
 */
class ESP_Database {

    /**
     * Class instance
     *
     * @since 2.0
     */
    private static $instance;

    /**
     * Plugin database table
     *
     * @since 2.0
     */
    private static $db_table = 'easingsliderpro';

    /**
     * Getter method for retrieving the database connection
     *
     * @since 2.0
     */
    public static function get_instance() {

        if ( !self::$instance instanceof self )
            self::$instance = new self;
        return self::$instance;

    }

    /**
     * Returns our database table name (prefixed)
     *
     * @since 2.0
     */
    public function get_table_name() {

        global $wpdb;
        return $wpdb->prefix . self::$db_table;

    }

    /**
     * Creates the plugin's database table
     *
     * @since 2.0
     */
    public function create_table() {

        global $wpdb;

        /** Get charset & collation */
        if ( !empty( $wpdb->charset ) )
            $charset_collation = "DEFAULT CHARACTER SET $wpdb->charset";
        if ( !empty( $wpdb->collation ) )
            $charset_collation .= " COLLATE $wpdb->collate";

        /** Table creation query */
        $table_name = $this->get_table_name();
        $query = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            name varchar(200) NOT NULL,
            author varchar(100) NOT NULL,
            slides longtext NOT NULL,
            general longtext NOT NULL,
            dimensions longtext NOT NULL,
            transitions longtext NOT NULL,
            navigation longtext NOT NULL,
            playback longtext NOT NULL,
            customizations longtext NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collation;";
        
        /** Run the WordPress upgrade schema script and create the table */
        require_once( ABSPATH .'wp-admin/includes/upgrade.php' );
        dbDelta( $query );

    }

    /**
     * Deletes the plugin's database table
     *
     * @since 2.0
     */
    public function delete_table() {
        
        global $wpdb;
        $table_name = self::get_table_name();
        $wpdb->query( "DROP TABLE $table_name" );

    }

    /**
     * Decodes the slideshow JSON values
     *
     * @since 2.0
     */
    public function decode_json( $slideshow ) {

        $slideshow->slides = json_decode( $slideshow->slides );
        $slideshow->general = json_decode( $slideshow->general );
        $slideshow->dimensions = json_decode( $slideshow->dimensions );
        $slideshow->transitions = json_decode( $slideshow->transitions );
        $slideshow->navigation = json_decode( $slideshow->navigation );
        $slideshow->playback = json_decode( $slideshow->playback );
        $slideshow->customizations = json_decode( $slideshow->customizations );
        return $slideshow;

    }

    /**
     * Returns a slideshow's settings from the database (a table row)
     *
     * @since 2.0
     */
    public function get_slideshow( $id ) {
        
        global $wpdb;

        $table_name = $this->get_table_name();
        $query = $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $id );
        $results = $wpdb->get_row( $query );

        /** Bail if no slideshow was found */
        if ( !$results )
            return false;

        /** JSON decode and validate slideshow */
        $slideshow = EasingSliderPro::get_instance()->validate( $this->decode_json( $results ) );

        /** Return slideshow JSON decoded and validated */
        return apply_filters( 'easingsliderpro_get_slideshow', $slideshow, $id );

    }

    /**
     * Returns all of the slideshows
     *
     * @since 2.0
     */
    public function get_all_slideshows() {
        
        global $wpdb;
        $table_name = $this->get_table_name();

        /** Get sort order */
        $orderby = ( isset( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'id';
        $order = ( isset( $_GET['order'] ) ) ? $_GET['order'] : 'asc';

        /** Construct & execute the query */
        $start = "SELECT * FROM $table_name";
        $middle = ( isset( $_GET['s'] ) ) ? $wpdb->prepare( " WHERE INSTR(name, %s) > 0 ", $_GET['s'] ): " ";
        $end = "ORDER BY $orderby $order";
        $results = $wpdb->get_results( $start . $middle . $end );

        /** Bail if no slideshows found */
        if ( !$results )
            return false;

        /** Do some decoding & validation */
        foreach ( $results as $index => $result )
            $results[ $index ] = EasingSliderPro::get_instance()->validate( $this->decode_json( $result ) );

        /** Return the query results */
        return apply_filters( 'easingsliderpro_get_all_slideshows', $results );

    }

    /**
     * Returns default slideshow values
     *
     * @since 2.0
     */
    public function get_slideshow_defaults() {

        $object = new stdClass();
        $object->id = $this->next_index();
        $object->name = __( 'New Slideshow', 'easingsliderpro' );
        $object->author = 'MatthewRuddy';
        $object->slides = array();
        $object->general = (object) array( 'randomize' => '' );
        $object->dimensions = (object) array( 'width' => 640, 'height' => 240, 'responsive' => false );
        $object->transitions = (object) array( 'effect' => 'slide', 'duration' => 500, 'touch' => true );
        $object->navigation = (object) array( 'arrows' => true, 'arrows_hover' => false, 'arrows_position' => 'inside', 'pagination' => true, 'pagination_hover' => false, 'pagination_position' => 'inside', 'pagination_location' => 'bottom-center' );
        $object->playback = (object) array( 'enabled' => true, 'pause' => 4000 );
        $object->customizations = (object) array(
            'arrows' => (object) array(
                'next' => plugins_url( dirname( plugin_basename( EasingSliderPro::get_file() ) ) . DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'slideshow_arrow_next.png' ),
                'prev' => plugins_url( dirname( plugin_basename( EasingSliderPro::get_file() ) ) . DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'slideshow_arrow_prev.png' ),
                'width' => 30,
                'height' => 30
            ),
            'pagination' => (object) array(
                'inactive' => plugins_url( dirname( plugin_basename( EasingSliderPro::get_file() ) ) . DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'slideshow_icon_inactive.png' ),
                'active' => plugins_url( dirname( plugin_basename( EasingSliderPro::get_file() ) ) . DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'slideshow_icon_active.png' ),
                'width' => 15,
                'height' => 15
            ),
            'border' => (object) array(
                'color' => '#000000',
                'width' => 0,
                'radius' => 0
            ),
            'shadow' => (object) array(
                'enable' => false,
                'image' => plugins_url( dirname( plugin_basename( EasingSliderPro::get_file() ) ) . DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'slideshow_shadow.png' )
            )
        );
        return apply_filters( 'easingsliderpro_slideshow_defaults', $object );

    }

    /**
     * Adds or updates a slideshow, depending on whether it already exists
     *
     * @since 2.0
     */
    public function add_or_update_slideshow( $id ) {

        global $wpdb;

        $table_name = $this->get_table_name();
        $query = $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $id );
        $results = $wpdb->get_row( $query );

        /** Save or update slideshow */
        if ( $results !== null )
            return $this->update_slideshow( $id );
        else
            return $this->add_slideshow();

    }

    /**
     * Updates a slideshow
     *
     * @since 2.0
     */
    public function update_slideshow( $id, $values = false ) {
        
        global $wpdb;

        /** Allow user to specify values, otherwise get them from $_POST */
        $request = ( is_array( $values ) ) ? $values : $_POST;

        /** Update the slideshow */
        $table_name = $this->get_table_name();
        return $wpdb->update(
            $table_name,
            array(
                'name' => ( isset( $request['name'] ) ) ? stripslashes_deep( $request['name'] ) : '',
                'author' => ( isset( $request['author'] ) ) ? stripslashes_deep( $request['author'] ) : '',
                'slides' => ( isset( $request['slides'] ) ) ? stripslashes_deep( $request['slides'] ) : '', /** Already JSON encoded via Javascript */
                'general' => ( isset( $request['general'] ) ) ? json_encode( stripslashes_deep( $request['general'] ) ) : '',
                'dimensions' => ( isset( $request['dimensions'] ) ) ? json_encode( stripslashes_deep( $request['dimensions'] ) ) : '',
                'transitions' => ( isset( $request['transitions'] ) ) ? json_encode( stripslashes_deep( $request['transitions'] ) ) : '',
                'navigation' => ( isset( $request['navigation'] ) ) ? json_encode( stripslashes_deep( $request['navigation'] ) ) : '',
                'playback' => ( isset( $request['playback'] ) ) ? json_encode( stripslashes_deep( $request['playback'] ) ) : ''
            ),
            array( 'id' => $id ),
            array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ),
            array( '%d' )
        );

    }

    /**
     * Updates a slideshow customizations
     *
     * @since 2.0
     */
    public function update_customizations( $id, $values = false ) {
        
        global $wpdb;

        /** Allow user to specify values, otherwise get them from $_POST */
        $request = ( is_array( $values ) ) ? $values : $_POST;

        /** Update the slideshow */
        $table_name = $this->get_table_name();
        return $wpdb->update(
            $table_name,
            array(
                'customizations' => ( isset( $request['customizations'] ) ) ? json_encode( stripslashes_deep( $request['customizations'] ) ) : ''
            ),
            array( 'id' => $id ),
            array( '%s' ),
            array( '%d' )
        );

    }

    /**
     * Adds a new slideshow
     *
     * @since 2.0
     */
    public function add_slideshow( $values = false ) {
        
        global $wpdb;

        /** Get defaults */
        $defaults = $this->get_slideshow_defaults();

        /** Allow user to specify values, otherwise get them from $_POST */
        $request = ( is_array( $values ) ) ? $values : $_POST;

        /** Add the slideshow */
        $table_name = $this->get_table_name();
        return $wpdb->insert( $table_name,
            array(
                'name' => ( isset( $request['name'] ) ) ? stripslashes_deep( $request['name'] ) : '',
                'author' => ( isset( $request['author'] ) ) ? stripslashes_deep( $request['author'] ) : '',
                'slides' => ( isset( $request['slides'] ) ) ? stripslashes_deep( $request['slides'] ) : '', /** Already JSON encoded via Javascript */
                'general' => ( isset( $request['general'] ) ) ? json_encode( stripslashes_deep( $request['general'] ) ) : '',
                'dimensions' => ( isset( $request['dimensions'] ) ) ? json_encode( stripslashes_deep( $request['dimensions'] ) ) : '',
                'transitions' => ( isset( $request['transitions'] ) ) ? json_encode( stripslashes_deep( $request['transitions'] ) ) : '',
                'navigation' => ( isset( $request['navigation'] ) ) ? json_encode( stripslashes_deep( $request['navigation'] ) ) : '',
                'playback' => ( isset( $request['playback'] ) ) ? json_encode( stripslashes_deep( $request['playback'] ) ) : '',
                'customizations' => ( isset( $request['customizations'] ) ) ? json_encode( stripslashes_deep( $request['customizations'] ) ) : ''
            ),
            array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
        );

    }

    /**
     * Duplicates a slideshow
     *
     * @since 2.0
     */
    public function duplicate_slideshow( $id ) {

        /** Get the slideshow to be duplicated */
        $slideshow = $this->get_slideshow( $id );

        /** Remove the slideshow ID */
        unset( $slideshow->id );

        /** Append 'Copy' to the slideshow name */
        $slideshow->name = $slideshow->name . __( ' Copy', 'easingsliderpro' );

        /** Escape the HTML content of each slide to prevent errors */
        if ( !empty( $slideshow->slides ) )
            foreach ( $slideshow->slides as $index => $slide )
                if ( !empty( $slide->content ) )
                    $slideshow->slides[ $index ]->content = mysql_real_escape_string( $slide->content );

        /** Re-encode the slides into JSON (when we get a slideshow they are decoded for ease of use) */
        $slideshow->slides = json_encode( $slideshow->slides );

        /** Convert slideshow object to array (necessary for database insertion) */
        $slideshow = get_object_vars( $slideshow );

        /** Add the new duplicated slideshow */
        return $this->add_slideshow( $slideshow );

    }

    /**
     * Deletes a slideshow
     *
     * @since 2.0
     */
    public function delete_slideshow( $id ) {

        global $wpdb;

        /** Delete the slideshow from the database */
        $table_name = $this->get_table_name();
        $query = $wpdb->prepare( "DELETE FROM $table_name WHERE id = %d", $id );
        $results = $wpdb->query( $query );
        return $results;

    }

    /**
     * Returns the next table index
     *
     * @since 2.0
     */
    public function next_index() {
        
        $table_name = $this->get_table_name();
        $query = "SHOW TABLE STATUS LIKE '$table_name'";
        $mysql_query = mysql_query( $query );
        $mysql_fetch_assoc = mysql_fetch_assoc( $mysql_query );
        return $mysql_fetch_assoc['Auto_increment'];
    
    }

}
<?php

/*
    Plugin Name: Easing Slider "Pro"
    Plugin URI: http://easingslider.com/
    Version: 2.0.2
    Author: Matthew Ruddy
    Author URI: http://matthewruddy.com/
    Description: Easing Slider "Pro" is a premium, easy to use slideshow plugin for WordPress. Simple, lightweight & designed to get the job done, it allows you to create hundreds of slideshows with ease.
    License: GNU General Public License v2.0 or later
    License URI: http://www.opensource.org/licenses/gpl-license.php

    Copyright 2013

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/** Load all of the necessary class files for the plugin */
spl_autoload_register( 'EasingSliderPro::autoload' );

/** Let's go! */
if ( class_exists( 'EasingSliderPro' ) )
    EasingSliderPro::get_instance();

/**
 * Main plugin class
 *
 * @author Matthew Ruddy
 * @since 2.0
 */
class EasingSliderPro {

    /**
     * Class instance
     *
     * @since 2.0
     */
    private static $instance;

    /**
     * String name of the main plugin file
     *
     * @since 2.0
     */
    private static $file = __FILE__;

    /**
     * Our plugin version
     *
     * @since 2.0
     */
    public static $version = '2.0.2';

    /**
     * Our array of Easing Slider "Pro" admin pages. These are used to conditionally load scripts.
     *
     * @since 2.0
     */
    public $whitelist = array();

    /**
     * Arrays of admin messages
     *
     * @since 2.0
     */
    public $admin_messages = array();

    /**
     * Flag for indicating that we are on a EasingSliderPro plugin page
     *
     * @since 2.0
     */
    private $is_easingsliderpro_page = false;
    
    /**
     * PSR-0 compliant autoloader to load classes as needed.
     *
     * @since 2.0
     */
    public static function autoload( $classname ) {
    
        if ( 'ESP' !== substr( $classname, 0, 3 ) )
            return;
            
        $filename = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . str_replace( 'ESP_', '', $classname ) . '.php';
        require $filename;
    
    }

    /**
     * Getter method for retrieving the class instance.
     *
     * @since 2.0
     */
    public static function get_instance() {
    
        if ( !self::$instance instanceof self )
            self::$instance = new self;
        return self::$instance;
    
    }

    /**
     * Gets the main plugin file
     *
     * @since 2.0
     */
    public static function get_file() {
        return self::$file;
    }
    
    /**
     * Constructor
     *
     * @since 2.0
     */
    private function __construct() {

        /** Load plugin textdomain for language capabilities */
        load_plugin_textdomain( 'easingsliderpro', false, dirname( plugin_basename( self::get_file() ) ) . '/languages' );

        /** Activation and deactivation hooks. Static methods are used to avoid activation/uninstallation scoping errors. */
        if ( is_multisite() ) {
            register_activation_hook( __FILE__, array( __CLASS__, 'do_network_activation' ) );
            register_uninstall_hook( __FILE__, array( __CLASS__, 'do_network_uninstall' ) );
        }
        else {
            register_activation_hook( __FILE__, array( __CLASS__, 'do_activation' ) );
            register_uninstall_hook( __FILE__, array( __CLASS__, 'do_uninstall' ) );
        }

        /** Legacy functionality */
        if ( apply_filters( 'easingsliderpro_legacy_functionality', __return_true() ) )
            ESP_Legacy::init( $this );

        /** Plugin shortcodes */
        add_shortcode( 'easingsliderpro', array( $this, 'do_shortcode' ) );

        /** Plugin actions */
        add_action( 'init', array( $this, 'register_all_styles' ) );
        add_action( 'init', array( $this, 'register_all_scripts' ) );
        add_action( 'admin_menu', array( $this, 'add_menus' ) );
        add_action( 'admin_menu', array( $this, 'do_actions' ) );
        add_action( 'admin_menu', array( $this, 'display_action_messages' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        add_action( 'admin_head', array( $this, 'print_global_styles' ) );
        add_action( 'admin_footer', array( $this, 'display_media_thickbox' ) );
        add_action( 'media_buttons', array( $this, 'add_media_button' ), 11 );
        add_action( 'print_media_templates', array( $this, 'print_backbone_templates' ) );
        add_action( 'wp_before_admin_bar_render', array( $this, 'add_admin_bar_links' ) );

        /** Register our custom widget */
        add_action( 'widgets_init', create_function( '', 'register_widget( "ESP_Widget" );' ) );

        /** Some hooks for our own custom actions */
        add_action( 'easingsliderpro_add_slideshow_actions', array( $this, 'do_slideshow_actions' ) );
        add_action( 'easingsliderpro_edit_slideshows_actions', array( $this, 'do_slideshow_actions' ) );
        add_action( 'easingsliderpro_customizer_actions', array( $this, 'do_customizer_actions' ) );
        add_action( 'easingsliderpro_import_export_slideshows_actions', array( $this, 'do_import_export_actions' ) );
        add_action( 'easingsliderpro_edit_settings_actions', array( $this, 'do_settings_actions' ) );

        /** Get plugin settings */
        $settings = get_option( 'easingsliderpro_settings' );

        /** Load slideshow scripts & styles in the header if set to do so */
        if ( isset( $settings['load_scripts'] ) && $settings['load_scripts'] == 'header' )
            add_action( 'wp_enqueue_scripts', array( 'ESP_Slideshow', 'enqueue_scripts' ) );
        if ( isset( $settings['load_styles'] ) && $settings['load_styles'] == 'header' ) {
            add_action( 'wp_enqueue_scripts', array( 'ESP_Slideshow', 'enqueue_styles' ) );
            if ( !ESP_Slideshow::$printed )
                add_action( 'wp_head', array( 'ESP_Slideshow', 'print_all_custom_styles') );
        }

        /** Queue plugin update & license validity checks. Check if plugin is active to prevent errors on uninstallation */
        if ( class_exists( 'ESP_HTTP' ) ) {
            add_filter( 'transient_update_plugins', array( 'ESP_HTTP', 'check_updates' ) );
            add_filter( 'site_transient_update_plugins', array( 'ESP_HTTP', 'check_updates' ) );
            add_filter( 'plugins_api_result', array( 'ESP_HTTP', 'update_information' ), 10, 3 );
            add_filter( 'after_plugin_row_'. plugin_basename( self::get_file() ), array( 'ESP_HTTP', 'plugin_row' ) );
        }

        /** Initialization hook for adding external functionality */
        do_action_ref_array( 'easingsliderpro', array( $this ) );

    }
    
    /**
     * Executes a network activation
     *
     * @since 2.0
     */
    public static function do_network_activation() {
        self::get_instance()->network_activate();
    }
    
    /**
     * Executes a network uninstall
     *
     * @since 2.0
     */
    public static function do_network_uninstall() {
        self::get_instance()->network_uninstall();
    }
    
    /**
     * Executes an activation
     *
     * @since 2.0
     */
    public static function do_activation() {
        self::get_instance()->activate();
    }
    
    /**
     * Executes an uninstall
     *
     * @since 2.0
     */
    public static function do_uninstall() {
        self::get_instance()->uninstall();
    }
    
    /**
     * Network activation hook
     *
     * @since 2.0
     */
    public function network_activate() {

        /** Do plugin version check */
        if ( !$this->version_check() )
            return;

        /** Get all of the blogs */
        $blogs = $this->get_multisite_blogs();

        /** Execute acivation for each blog */
        foreach ( $blogs as $blog_id ) {
            switch_to_blog( $blog_id );
            $this->activate();
            restore_current_blog();
        }

        /** Trigger hooks */
        do_action_ref_array( 'easingsliderpro_network_activate', array( $this ) );

    }
    
    /**
     * Network uninstall hook
     *
     * @since 2.0
     */
    public function network_uninstall() {

        /** Get all of the blogs */
        $blogs = $this->get_multisite_blogs();

        /** Execute uninstall for each blog */
        foreach ( $blogs as $blog_id ) {
            switch_to_blog( $blog_id );
            $this->uninstall();
            restore_current_blog();
        }

        /** Trigger hooks */
        do_action_ref_array( 'easingsliderpro_network_uninstall', array( $this ) );

    }
    
    /**
     * Activation hook
     *
     * @since 2.0
     */
    public function activate() {

        /** Do plugin version check */
        if ( !$this->version_check() )
            return;

        /** Add database table */
        ESP_Database::get_instance()->create_table();

        /** Add "wp_options" table options */
        add_option( 'easingsliderpro_version', self::$version );
        add_option( 'easingsliderpro_license_key', '' );
        add_option( 'easingsliderpro_settings',
            array(
                'license_key' => '',
                'resizing' => false,
                'load_styles' => 'header',
                'load_scripts' => 'header'
            )
        );
        add_option( 'easingsliderpro_lite_upgrade', 0 );
        add_option( 'easingsliderpro_major_upgrade', 0 );
        add_option( 'easingsliderpro_disable_welcome_panel', 0 );

        /** Add user capabilities */
        $this->manage_capabilities( 'add', $this->capabilities() );

        /** Trigger hooks */
        do_action_ref_array( 'easingsliderpro_activate', array( $this ) );

    }
    
    /**
     * Uninstall Hook
     *
     * @since 2.0
     */
    public function uninstall() {

        /** Remove database table */
        ESP_Database::get_instance()->delete_table();

        /** Delete "wp_options" table options */
        delete_option( 'easingsliderpro_version' );
        delete_option( 'easingsliderpro_license_key' );
        delete_option( 'easingsliderpro_settings' );
        delete_option( 'easingsliderpro_lite_upgrade' );
        delete_option( 'easingsliderpro_major_upgrade' );
        delete_option( 'easingsliderpro_disable_welcome_panel' );

        /** Delete transients set throughout execution */
        delete_transient( 'easingsliderpro_available_updates' );

        /** Remove user capabilities */
        $this->manage_capabilities( 'remove', $this->capabilities() );

        /** Trigger hooks */
        do_action_ref_array( 'easingsliderpro_uninstall', array( $this ) );

    }
    
    /**
     *  Does a plugin version check, making sure the current Wordpress version is supported. If not, the plugin is deactivated and an error message is displayed.
     *
     *  @version 2.0
     */
    public function version_check() {
        global $wp_version;
        if ( version_compare( $wp_version, '3.5', '<' ) ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            wp_die( __( sprintf( 'Sorry, but your version of WordPress, <strong>%s</strong>, is not supported. The plugin has been deactivated. <a href="%s">Return to the Dashboard.</a>', $wp_version, admin_url() ), 'easingsliderpro' ) );
            return false;
        }
        return true;
    }
    
    /**
     * Returns the ids of the various multisite blogs. Returns false if not a multisite installation.
     *
     * @since 2.0
     */
    public function get_multisite_blogs() {

        global $wpdb;

        /** Bail if not multisite */
        if ( !is_multisite() )
            return false;

        /** Get the blogs ids from database */
        $query = "SELECT blog_id from $wpdb->blogs";
        $blogs = $wpdb->get_col($query);

        /** Push blog ids to array */
        $blog_ids = array();
        foreach ( $blogs as $blog )
            $blog_ids[] = $blog;

        /** Return the multisite blog ids */
        return $blog_ids;

    }

    /**
     * Returns the plugin capabilities
     *
     * @since 2.0
     */
    public function capabilities() {
        $capabilities = array(
            'easingsliderpro_edit_slideshows',
            'easingsliderpro_add_slideshow',
            'easingsliderpro_can_customize',
            'easingsliderpro_import_export_slideshows',
            'easingsliderpro_edit_settings'
        );
        $capabilities = apply_filters( 'easingsliderpro_capabilities', $capabilities );
        return $capabilities; 
    }
    
    /**
     * Manages (adds or removes) user capabilities
     *
     * @since 2.0
     */
    public function manage_capabilities( $action, $capabilities ) {

        global $wp_roles;
        
        /** Add capability for each applicable user roel */
        foreach ( $wp_roles->roles as $role => $info ) {
            $user_role = get_role( $role );
            foreach ( $capabilities as $capability ) {
                if ( $action == 'add' )
                    $this->add_capability( $capability, $user_role );
                elseif ( $action == 'remove' )
                    $this->remove_capability( $capability, $user_role );
            }
        }

    }
    
    /**
     * Adds a user capability
     *
     * @since 2.0
     */
    public function add_capability( $capability, $role ) {
        if ( $role->has_cap( 'edit_plugins' ) )
            $role->add_cap( $capability );
    }
    
    /**
     * Removes a user capability
     *
     * @since 2.0
     */
    public function remove_capability( $capability, $role ) {
        if ( $role->has_cap( $capability ) )
            $role->remove_cap( $capability );
    }
    
    /**
     * Adds the admin menus
     *
     * @since 2.0
     */
    public function add_menus() {

        global $menu;

        /** Hook suffixs for admin menus */
        $pages = apply_filters( 'easingsliderpro_menus', array(
            'easingsliderpro_edit_slideshows',
            'easingsliderpro_add_slideshow',
            'easingsliderpro_customizer',
            'easingsliderpro_import_export_slideshows',
            'easingsliderpro_edit_settings'
        ) );

        /** Default menu positioning */
        $position = '100.1';

        /** If enabled, relocate the plugin menus higher */
        if ( apply_filters( 'easingsliderpro_relocate_menus', __return_true() ) ) {
            $position = '40.1';
            while ( isset( $menu[ $position ] ) && isset( $menu[ $position + '0.1' ] ) && isset( $menu[ $position - '0.1' ] ) )
                $position + '0.1';
        }

        /** Toplevel menu */
        $this->whitelist[] = add_menu_page(
            __( 'Slideshows', 'easingsliderpro' ),
            __( 'Slideshows', 'easingsliderpro' ),
            'easingsliderpro_edit_slideshows',
            'easingsliderpro_edit_slideshows',
            null,
            plugins_url( dirname( plugin_basename( self::get_file() ) ) . DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'menu_icon_single.png' ),
            $position
        );

        /** Submenus */
        $this->whitelist[] = add_submenu_page(
            'easingsliderpro_edit_slideshows',
            __( 'Slideshows', 'easingsliderpro' ),
            __( 'All Slideshows', 'easingsliderpro' ),
            'easingsliderpro_edit_slideshows',
            'easingsliderpro_edit_slideshows',
            array( $this, 'choose_slideshow_view' )
        );
        $this->whitelist[] = add_submenu_page(
            'easingsliderpro_edit_slideshows',
            __( 'Add New Slideshow', 'easingsliderpro' ),
            __( 'Add New', 'easingsliderpro' ),
            'easingsliderpro_add_slideshow',
            'easingsliderpro_add_slideshow',
            array( $this, 'choose_slideshow_view' )
        );
        $this->whitelist[] = add_submenu_page(
            'easingsliderpro_edit_slideshows',
            __( 'Customizer', 'easingsliderpro' ),
            __( 'Customize', 'easingsliderpro' ),
            'easingsliderpro_can_customize',
            'easingsliderpro_customizer',
            array( $this, 'customizer_view' )
        );
        $this->whitelist[] = add_submenu_page(
            'easingsliderpro_edit_slideshows',
            __( 'Import/Export Slideshows', 'easingsliderpro' ),
            __( 'Import/Export', 'easingsliderpro' ),
            'easingsliderpro_import_export_slideshows',
            'easingsliderpro_import_export_slideshows',
            array( $this, 'import_export_view' )
        );
        $this->whitelist[] = add_submenu_page(
            'easingsliderpro_edit_slideshows',
            __( 'Edit Settings', 'easingsliderpro' ),
            __( 'Settings', 'easingsliderpro' ),
            'easingsliderpro_edit_settings',
            'easingsliderpro_edit_settings',
            array( $this, 'edit_settings_view' )
        );

        /** Add the menu separators if menus have been relocated (they are by default) */
        if ( apply_filters( 'easingsliderpro_relocate_menus', __return_true() ) ) {
            $this->add_menu_separator( $position - '0.1' );
            $this->add_menu_separator( $position + '0.1' );
        }

        /** Set flag if we are on one of our own plugin pages */
        if ( isset( $_GET['page'] ) && in_array( $_GET['page'], $pages ) )
            $this->is_easingsliderpro_page = true;

    }
    
    /**
     *  Create a separator in the admin menus, above and below our plugin menus
     *
     *  @version 2.0
     */
    public function add_menu_separator( $position = '40.1' ) {

        global $menu;

        $index = 0;
        foreach ( $menu as $offset => $section ) {
            if ( substr( $section[2], 0, 9 ) == 'separator' )
                $index++;
            if ( $offset >= $position ) {
                $menu[ $position ] = array( '', 'read', "separator{$index}", '', 'wp-menu-separator' );
                break;
            }
        }
        ksort( $menu );
        
    }

    /**
     *  Adds plugin links to the admin bar
     *
     *  @author Matthew Ruddy
     *  @version 2.0.1
     */
    public function add_admin_bar_links() {
        
        global $wp_admin_bar;

        /** Bail if user cannot edit slideshows */
        if ( !current_user_can( 'easingsliderpro_edit_slideshows' ) )
            return;
        
        /** Add 'Slideshow' link to the 'New' admin bar menu */
        if ( current_user_can( 'easingsliderpro_add_slideshow' ) ) {
            $wp_admin_bar->add_menu(
                array(
                    'parent' => 'new-content',
                    'id' => 'easingsliderpro_add_slideshow',
                    'title' => __( 'Slideshow', 'rivasliderpro' ),
                    'href' => admin_url( 'admin.php?page=easingsliderpro_add_slideshow' )
                )
            );
        }

        /** Add the new toplevel menu */
        $wp_admin_bar->add_menu(
            array(
                'id' => 'slideshows-top_menu',
                'title' => __( 'Slideshows', 'easingsliderpro' ),
                'href' => admin_url( "admin.php?page=easingsliderpro_edit_slideshows" )
            )
        );

        /** Add submenu links to our toplevel menu */
        $wp_admin_bar->add_menu(
            array(
                'parent' => 'slideshows-top_menu',
                'id' => 'edit-slideshows-sub_menu',
                'title' => __( 'All Slideshows', 'easingsliderpro' ),
                'href' => admin_url( "admin.php?page=easingsliderpro_edit_slideshows" )
            )
        );
        if ( current_user_can( 'easingsliderpro_add_slideshow' ) ) {
            $wp_admin_bar->add_menu(
                array(
                    'parent' => 'slideshows-top_menu',
                    'id' => 'add-slideshow-sub_menu',
                    'title' => __( 'Add New', 'easingsliderpro' ),
                    'href' => admin_url( "admin.php?page=easingsliderpro_add_slideshow" )
                )
            );
        }
        if ( current_user_can( 'easingsliderpro_can_customize' ) ) {
            $wp_admin_bar->add_menu(
                array(
                    'parent' => 'slideshows-top_menu',
                    'id' => 'customizer-sub_menu',
                    'title' => __( 'Customize', 'easingsliderpro' ),
                    'href' => admin_url( "admin.php?page=easingsliderpro_customizer" )
                )
            );
        }
        if ( current_user_can( 'easingsliderpro_import_export_slideshows' ) ) {
            $wp_admin_bar->add_menu(
                array(
                    'parent' => 'slideshows-top_menu',
                    'id' => 'import-export-slideshows-sub_menu',
                    'title' => __( 'Import/Export', 'easingsliderpro' ),
                    'href' => admin_url( "admin.php?page=easingsliderpro_import_export_slideshows" )
                )
            );
        }
        if ( current_user_can( 'easingsliderpro_edit_settings' ) ) {
            $wp_admin_bar->add_menu(
                array(
                    'parent' => 'slideshows-top_menu',
                    'id' => 'edit-settings-sub_menu',
                    'title' => __( 'Settings', 'easingsliderpro' ),
                    'href' => admin_url( "admin.php?page=easingsliderpro_edit_settings" )
                )
            );
        }

    }

    /**
     * Adds a media button (for inserting a slideshow) to the Post Editor
     *
     * @since 2.0
     */
    public function add_media_button( $editor_id ) {
        $img = '<span class="insert-slideshow-icon"></span>';
        ?>
        <style type="text/css">
            .insert-slideshow.button .insert-slideshow-icon {
                width: 16px;
                height: 16px;
                margin-top: -1px;
                margin-left: -1px;
                margin-right: 4px;
                display: inline-block;
                vertical-align: text-top;
                background: url(<?php echo plugins_url( dirname( plugin_basename( self::get_file() ) ) . DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'menu_icon_single_grey.png' ); ?>) no-repeat top left;
            }
        </style>
        <a href="#TB_inline?width=480&amp;inlineId=select-slideshow" class="button thickbox insert-slideshow" data-editor="<?php echo esc_attr( $editor_id ); ?>" title="<?php _e( 'Add a slideshow', 'rivasliderpro' ); ?>"><?php echo $img . __( 'Add Slideshow', 'rivasliderpro' ); ?></a>
        <?php
    }

    /**
     * Content for 'Insert Slideshow' media button
     *
     * @since 2.0
     */
    public function display_media_thickbox() {

        global $pagenow;

        /** Bail if not in the post/page editor */
        if ( $pagenow != 'post.php' && $pagenow != 'post-new.php' )
            return;

        /** Get all of the slideshows */
        $slideshows = ESP_Database::get_instance()->get_all_slideshows();

        /** Content HTML */
        ?>
        <style type="text/css">
            .section {
                padding: 15px 15px 0 15px;
            }
        </style>
        <script type="text/javascript">
            function insertSlideshow() {

                /** Get selected slideshow ID */
                var id = jQuery('#slideshow').val();

                /** Display alert and bail if no slideshow was selected */
                if ( id === '-1' )
                    return alert("<?php _e( 'Please select a slideshow', 'easingsliderpro' ); ?>");

                /** Send shortcode to editor */
                send_to_editor('[easingsliderpro id="'+ id +'"]');

                /** Close thickbox */
                tb_remove();

            }
        </script>
        <div id="select-slideshow" style="display: none;">
            <div class="section">
                <h2><?php _e( 'Add a slideshow', 'easingsliderpro' ); ?></h2>
                <span><?php _e( 'Select a slideshow to insert from the box below.', 'easingsliderpro' ); ?></span>
            </div>

            <div class="section">
                <select name="slideshow" id="slideshow">
                    <option value="-1"><?php _e( 'Select a slideshow', 'rivasliderpro' ); ?></option>
                    <?php
                        foreach ( $slideshows as $slideshow )
                            echo "<option value='{$slideshow->id}'>{$slideshow->name} (ID #{$slideshow->id})</option>";
                    ?>
                </select>
            </div>

            <div class="section">
                <button id="insert-slideshow" class="button-primary" onClick="insertSlideshow();"><?php _e( 'Insert Slideshow', 'rivasliderpro' ); ?></button>
                <button id="close-slideshow-thickbox" class="button-secondary" style="margin-left: 5px;" onClick="tb_remove();"><?php _e( 'Close', 'rivasliderpro' ); ?></a>
            </div>
        </div>
        <?php

    }
    
    /**
     * Queues an admin message to be displayed
     *
     * @since 2.0
     */
    public function queue_message( $text, $type ) {
        if ( !$this->is_easingsliderpro_page )
            return;
        $message = "<div class='message $type'><p>$text</p></div>";
        add_action( 'admin_notices', create_function( '', 'echo "'. $message .'";' ) );
    }

    /**
     * Does security nonce checks
     *
     * @since 2.0
     */
    public function security_check( $action, $page ) {
        if ( check_admin_referer( "easingsliderpro-{$action}_{$page}", "easingsliderpro-{$action}_{$page}" ) )
            return true;
        return false;
    }

    /**
     * Nonce URL function, polyfill for upcoming trac contribution by me! :)
     * http://core.trac.wordpress.org/ticket/22423
     *
     * @since 2.0
     */
    public function nonce_url( $actionurl, $action, $arg = '_wpnonce' ) {
        $actionurl = str_replace( '&amp;', '&', $actionurl );
        return esc_html( add_query_arg( $arg, wp_create_nonce( $action, $actionurl ), $actionurl ) );
    }
    
    /**
     * Does admin actions (if appropriate)
     *
     * @since 2.0
     */
    public function do_actions() {

        /** Bail if we aren't on a EasingSliderPro page */
        if ( !$this->is_easingsliderpro_page )
            return;

        /** Do admin actions */
        do_action( "{$_GET['page']}_actions", $_GET['page'] );

    }
    
    /**
     * Slideshow based actions
     *
     * @since 2.0
     */
    public function do_slideshow_actions( $page ) {

        /** Disable welcome panel if it is dismissed */
        if ( isset( $_GET['disable_welcome_panel'] ) )
            update_option( 'easingsliderpro_disable_welcome_panel', filter_var( $_GET['disable_welcome_panel'], FILTER_VALIDATE_BOOLEAN ) );

        /** Save or update a slideshow. Whichever is appropriate. */
        if ( isset( $_POST['save'] ) ) {

            /** Security check. */
            if ( !$this->security_check( 'save', 'easingsliderpro_edit_slideshows' ) ) {
                wp_die( __( 'Security check has failed. Save has been prevented. Please try again.', 'easingsliderpro' ) );
                exit();
            }

            /** Saves or adds slideshow and return the response */
            $response = ESP_Database::get_instance()->add_or_update_slideshow( $_GET['edit'] );

            /** Check for false response explicity to prevent incorrect error reports. MySQL returns 0 if save is successful but no rows were affected. */
            if ( $response === false )
                return $this->queue_message( __( 'Failed to save slideshow. An error has occurred. Please try again or contact support.', 'easingsliderpro' ), 'error' );
            else
                return $this->queue_message( __( 'Slideshow has been <strong>saved</strong> successfully.', 'easingsliderpro' ), 'updated' );

        }

        /** Bulk actions */
        if ( isset( $_GET['action'] ) && isset( $_GET['action2'] ) ) {

            /** Top bulk actions option always takes preference. If both actions are set, we bail to avoid confusion */
            if ( $_GET['action'] !== '-1' && $_GET['action2'] !== '-1' ) {
                wp_redirect( "admin.php?page={$page}" );
                return;
            }
            elseif ( $_GET['action'] !== '-1' )
                $action = $_GET['action'];
            elseif ( $_GET['action2'] !== '-1' )
                $action = $_GET['action2'];
            else {
                wp_redirect( "admin.php?page={$page}" );
                return;
            }

            /** Bail if IDs aren't an array */
            if ( !isset( $_GET['id'] ) || !is_array( $_GET['id'] ) ) {
                wp_redirect( "admin.php?page={$page}" );
                return;
            }

            /** Security check. Page is hardcoded to prevent errors when adding a new slidesow) */
            if ( !$this->security_check( 'bulk', $page ) ) {
                wp_die( __( 'Security check has failed. Bulk action has been prevented. Please try again.', 'easingsliderpro' ) );
                exit();
            }

            /** Do appropriate action */
            if ( $action == 'duplicate' ) {

                /** Duplicate slideshows */
                foreach ( $_GET['id'] as $id )
                    $response = ESP_Database::get_instance()->duplicate_slideshow( $id );

                /** Check for success or failure */
                if ( $response === false ) {
                    wp_redirect( "admin.php?page={$page}&message=slideshows_not_duplicated" );
                    return;
                }
                else {
                    wp_redirect( "admin.php?page={$page}&message=slideshows_duplicated" );
                    return;
                }

            }
            elseif ( $action == 'delete' ) {

                /** Delete slideshows */
                foreach ( $_GET['id'] as $id )
                    $response = ESP_Database::get_instance()->delete_slideshow( $id );

                /** Check for success or failure */
                if ( $response === false ) {
                    wp_redirect( "admin.php?page={$page}&message=slideshows_not_deleted" );
                    return;
                }
                else {
                    wp_redirect( "admin.php?page={$page}&message=slideshows_deleted" );
                    return;
                }

            }

        }
        /** Single actions */
        elseif ( isset( $_GET['action'] ) ) {

            /** Bail if no slideshow ID has been specified */
            if ( !isset( $_GET['id'] ) )
                return;

            /** Do appropriate action */
            if ( $_GET['action'] == 'duplicate' ) {

                /** Security check. Page is hardcoded to prevent errors when adding a new slidesow) */
                if ( !$this->security_check( 'duplicate', $page ) ) {
                    wp_die( __( 'Security check has failed. Duplicate has been prevented. Please try again.', 'easingsliderpro' ) );
                    exit();
                }

                /** Duplicate slideshow */
                $response = ESP_Database::get_instance()->duplicate_slideshow( $_GET['id'] );

                /** Check for success or failure */
                if ( $response === false ) {
                    wp_redirect( "admin.php?page={$page}&message=slideshow_not_duplicated" );
                    return;
                }
                else {
                    wp_redirect( "admin.php?page={$page}&message=slideshow_duplicated" );
                    return;
                }

            }
            elseif ( $_GET['action'] == 'delete' ) {

                /** Security check. Page is hardcoded to prevent errors when adding a new slidesow) */
                if ( !$this->security_check( 'delete', $page ) ) {
                    wp_die( __( 'Security check has failed. Delete has been prevented. Please try again.', 'easingsliderpro' ) );
                    exit();
                }

                /** Delete slideshow */
                $response = ESP_Database::get_instance()->delete_slideshow( $_GET['id'] );

                /** Check for success or failure */
                if ( $response === false ) {
                    wp_redirect( "admin.php?page={$page}&message=slideshow_not_deleted" );
                    return;
                }
                else {
                    wp_redirect( "admin.php?page={$page}&message=slideshow_deleted" );
                    return;
                }

            }

        }

    }
    
    /**
     * Customization page actions
     *
     * @since 2.0
     */
    public function do_customizer_actions( $page ) {

        /** Save customizations */
        if ( isset( $_POST['save'] ) ) {

            /** Security check */
            if ( !$this->security_check( 'save', $page ) ) {
                wp_die( __( 'Security check has failed. Save has been prevented. Please try again.', 'easingsliderpro' ) );
                exit();
            }

            /** Save the customizations */
            ESP_Database::get_instance()->update_customizations( $_POST['id'],
                $this->validate(
                    array( 'customizations' => (object) array(
                        'arrows' => (object) $_POST['arrows'],
                        'pagination' => (object) $_POST['pagination'],
                        'border' => (object) $_POST['border'],
                        'shadow' => (object) $_POST['shadow']
                    ) )
                )
            );

        }

    }
    
    /**
     * Import/Export page actions
     *
     * @since 2.0
     */
    public function do_import_export_actions( $page ) {

        /** Import/export slideshows*/
        if ( isset( $_POST['import'] ) ) {

            /** Security check */
            if ( !$this->security_check( 'import', $page ) ) {
                wp_die( __( 'Security check has failed. Import has been prevented. Please try again.', 'easingsliderpro' ) );
                exit();
            }

            /** Bail if no file uploaded */
            if ( !isset( $_FILES['import_file'] ) )
                return;

            /** Get file info */
            $file_info = $_FILES['import_file'];

            /** Check for file upload errors or incorrect file type */
            if ( $file_info['error'] > 0 )
                return $this->queue_message( __( "File upload error: {$file_info['error']}", 'easingsliderpro' ), 'error' );
            if ( $file_info['type'] !== 'application/json' )
                return $this->queue_message( __( 'Uploaded file is not a valid JSON file. Please upload a .json file exported from EasingSliderPro.', 'easingsliderpro' ), 'error' );

            /** Get imported file contents (and bail if failed) */
            $file_contents = @file_get_contents( $file_info['tmp_name'] );
            if ( !$file_contents )
                return $this->queue_message( __( 'Import slideshows failed: file_get_contents not successful.', 'easingsliderpro' ), 'error' );

            /** Decode imported file */
            $json = json_decode( $file_contents );

            /** Do URLs replace (if selected). Bit of a hack, but it works */
            if ( isset( $_POST['replace_urls'] ) && $_POST['replace_urls'] )
                $json = json_decode( str_replace( $json->domain, get_bloginfo( 'url' ), json_encode( $json ) ) );

            /** Loop through each slideshow */
            foreach ( $json->slideshows as $s ) {

                /** Remove ID */
                unset( $s->id );

                /** Re-encode the slides into JSON (when we get a slideshow they are decoded for ease of use) */
                $s->slides = json_encode( $s->slides );

                /** Change slideshow author */
                $s->author = wp_get_current_user()->user_login;

                /** Convert slideshow object to array */
                $s = get_object_vars( $s );

                /** Add the slideshow */
                ESP_Database::get_instance()->add_slideshow( $s );

            }

            /** Redirect if successfully imported (prevents re-importing slideshows if user refreshes the page) */
            wp_redirect( "admin.php?page={$page}&message=import_success" );
            return;

        }
        elseif ( isset( $_POST['export'] ) ) {

            /** Security check */
            if ( !$this->security_check( 'export', $page ) ) {
                wp_die( __( 'Security check has failed. Export has been prevented. Please try again.', 'easingsliderpro' ) );
                exit();
            }

            /** Bail if no slideshows have been selected */
            if ( !isset( $_POST['id'] ) )
                return $this->queue_message( __( 'No slideshows have been selected to export. Please select some and try again.', 'easingsliderpro' ), 'error' );

            /** Get the slideshows into an array */
            $export = array();
            foreach ( $_POST['id'] as $id )
                $export[] = ESP_Database::get_instance()->get_slideshow( $id );

            /** Get the current date, time & site URL */
            $time = date( "Y-m-d-H-i-s" );
            $blog = str_replace( 'http://', '', get_bloginfo( 'url' ) );

            /** JSON encode the array */
            $json_export = json_encode( array( 'domain' => get_bloginfo( 'url' ), 'slideshows' => $export ) );

            /** Make sure the encode has happened successfully */
            if ( empty( $json_export ) )
                return $this->queue_message( __( 'PHP json_encode has failed to encode slideshows correctly. Please try again.', 'easingsliderpro' ), 'error' );

            /** Download the JSON file to users browser */
            header( "Content-Description: File Transfer" );
            header( "Content-Disposition: attachment; filename=easingsliderpro-slideshows-$blog-$time.json" );
            header( "Content-Type: application/json" );
            print $json_export;
            die();

        }

    }
    
    /**
     * Settings page actions
     *
     * @since 2.0
     */
    public function do_settings_actions( $page ) {

        /** Reset plugin */
        if ( isset( $_POST['reset'] ) ) {

            /** Security check */
            if ( !$this->security_check( 'reset', $page ) ) {
                wp_die( __( 'Security check has failed. Reset has been prevented. Please try again.', 'easingsliderpro' ) );
                exit();
            }

            /** Do reset */
            $this->uninstall();
            $this->activate();

            /** Queue message */
            return $this->queue_message( __( 'Plugin has been reset successfully.', 'easingsliderpro' ), 'updated' );

        }

        /** Save the settings */
        if ( isset( $_POST['save'] ) ) {

            /** Security check */
            if ( !$this->security_check( 'save', $page ) ) {
                wp_die( __( 'Security check has failed. Save has been prevented. Please try again.', 'easingsliderpro' ) );
                exit();
            }

            /** Get settings and do some validation */
            $settings = $this->validate( $_POST['settings'] );

            /** Check if we need to do license key validation */
            if ( ESP_HTTP::should_do_validation( $settings ) ) {

                /** Do a validation check */
                $response = ESP_HTTP::do_validation( $settings['license_key'] );

                /** Handle response */
                if ( $response ) {

                    /** Queue message */
                    $this->queue_message( $response->message, $response->status );

                    /** Clear license key if we couldn't make contact with the service */
                    if ( $response->checked === false )
                        $settings['license_key'] = '';

                }

            }

            /** Update database option */
            update_option( 'easingsliderpro_settings', stripslashes_deep( $settings ) );

            /** Show update message */
            return $this->queue_message( __( 'Settings have been <strong>saved</strong> successfully.', 'easingsliderpro' ), 'updated' );

        }

    }

    /**
     * Does validation
     *
     * @since 2.0
     */
    public function validate( $values ) {

        /** Object flag */
        $is_object = ( is_object( $values ) ) ? true : false;

        /** Convert objects to arrays */
        if ( $is_object )
            $values = (array) $values;

        /** Get settings and do some validation */
        foreach ( $values as $key => $value ) {

            /** Validators */
            if ( is_numeric( $value ) )
                $values[ $key ] = filter_var( $value, FILTER_VALIDATE_INT );
            elseif ( $value === 'true' || $value === 'false' )
                $values[ $key ] = filter_var( $value, FILTER_VALIDATE_BOOLEAN );

            /** Recurse if necessary */
            if ( is_object( $value ) || is_array( $value ) )
                $values[ $key ] = $this->validate( $value );

        }

        /** Convert back to an object */
        if ( $is_object )
            $values = (object) $values;

        return stripslashes_deep( $values );

    }
    
    /**
     * Displays messages after actions are completed
     *
     * @since 2.0
     */
    public function display_action_messages() {

        /** Bail if we aren't on a EasingSliderPro page */
        if ( !$this->is_easingsliderpro_page )
            return;

        if ( isset( $_GET['message'] ) ) {

            /** Get the message */
            $message = $_GET['message'];

            /** Display appropriate message */
            switch ( $message ) {

                /** Bulk action responses */
                case 'slideshows_duplicated' :
                    $this->queue_message( __( 'Slideshows have been <strong>duplicated</strong> successfully.', 'easingsliderpro' ), 'updated' );
                    break;

                case 'slideshows_not_duplicated' :
                    $this->queue_message( __( 'Failed to duplicate slideshows. An error has occurred. Please try again or contact support.', 'easingsliderpro' ), 'error' );
                    break;

                case 'slideshows_deleted' :
                    $this->queue_message( __( 'Slideshows have been <strong>deleted</strong> successfully.', 'easingsliderpro' ), 'updated' );
                    break;

                case 'slideshows_not_deleted' :
                    $this->queue_message( __( 'Failed to delete slideshows. An error has occurred. Please try again or contact support.', 'easingsliderpro' ), 'error' );
                    break;

                /** Single action responses */
                case 'slideshow_duplicated' :
                    $this->queue_message( __( 'Slideshow has been <strong>duplicated</strong> successfully.', 'easingsliderpro' ), 'updated' );
                    break;

                case 'slideshow_not_duplicated' :
                    $this->queue_message( __( 'Failed to duplicate slideshow. An error has occurred. Please try again or contact support.', 'easingsliderpro' ), 'error' );
                    break;

                case 'slideshow_deleted' :
                    $this->queue_message( __( 'Slideshow has been <strong>deleted</strong> successfully.', 'easingsliderpro' ), 'updated' );
                    break;

                case 'slideshow_not_deleted' :
                    $this->queue_message( __( 'Failed to delete slideshow. An error has occurred. Please try again or contact support.', 'easingsliderpro' ), 'error' );
                    break;

                /** Import slideshows response */
                case 'import_success' :
                    $this->queue_message( __( 'Slideshows have successfully been <strong>imported</strong>.', 'easingsliderpro' ), 'updated' );
                    break;

            }

        }

    }
    
    /**
     * Executes a shortcode handler
     *
     * @since 2.0
     */
    public function do_shortcode( $atts ) {

        /** Extract shortcode attributes */
        extract(
            shortcode_atts(
                array( 'id' => false ),
                $atts
            )
        );

        /** Display error message if no ID has been entered */
        if ( !$id )
            return __( "Looks like you've forgotten to add a slideshow ID to this shortcode. Oh dear!", 'easingsliderpro' );

        /** Get the slideshow */
        $slideshow = ESP_Slideshow::get_instance()->display_slideshow( $id );

        /** Display the slideshow (or error message if it doesn't exist) */
        if ( is_wp_error( $slideshow ) )
            return $slideshow->get_error_message();
        else
            return $slideshow;

    }
    
    /**
     * Register all admin stylesheets
     *
     * @since 2.0
     */
    public function register_all_styles() {

        /** Get the extension */
        $ext = ( apply_filters( 'easingsliderpro_debug_styles', __return_false() ) === true ) ? '.css' : '.min.css';

        /** Register styles */
        wp_register_style( 'esp-admin', plugins_url( dirname( plugin_basename( self::get_file() ) ) . DIRECTORY_SEPARATOR .'css'. DIRECTORY_SEPARATOR .'admin'. $ext ), false, self::$version );
        wp_register_style( 'esp-slideshow', plugins_url( dirname( plugin_basename( self::get_file() ) ) . DIRECTORY_SEPARATOR .'css'. DIRECTORY_SEPARATOR .'slideshow'. $ext ), false, self::$version );

    }
    
    /**
     * Register all admin scripts
     *
     * @since 2.0
     */
    public function register_all_scripts() {

        /** Get the extension */
        $ext = ( apply_filters( 'easingsliderpro_debug_scripts', __return_false() ) ) ? '.js' : '.min.js';

        /** Register scripts */
        wp_register_script( 'esp-admin',  plugins_url( dirname( plugin_basename( self::get_file() ) ) . DIRECTORY_SEPARATOR .'js'. DIRECTORY_SEPARATOR .'admin'. $ext ), array( 'jquery', 'backbone', 'jquery-ui-sortable', 'jquery-touch-punch' ), self::$version, true );
        wp_register_script( 'esp-customizer',  plugins_url( dirname( plugin_basename( self::get_file() ) ) . DIRECTORY_SEPARATOR .'js'. DIRECTORY_SEPARATOR .'customizer'. $ext ), array( 'jquery', 'backbone' ), self::$version );
        wp_register_script( 'esp-slideshow',  plugins_url( dirname( plugin_basename( self::get_file() ) ) . DIRECTORY_SEPARATOR .'js'. DIRECTORY_SEPARATOR .'slideshow'. $ext ), false, self::$version );

    }
    
    /**
     * Loads admin stylesheets
     *
     * @since 2.0
     */
    public function enqueue_admin_styles( $hook ) {

        /** Bail if not an Easing Slider "Pro" page */
        if ( !in_array( $hook, $this->whitelist ) )
            return;

        /** Load styles */
        wp_enqueue_style( 'esp-admin' );
        do_action( 'easingsliderpro_enqueue_admin_styles' );

    }
    
    /**
     * Loads admin javascript files
     *
     * @since 2.0
     */
    public function enqueue_admin_scripts( $hook ) {

        /** Bail if not an Easing Slider "Lite" page */
        if ( !in_array( $hook, $this->whitelist ) )
            return;

        /** Print Localized variables */
        wp_localize_script( 'esp-admin', 'easingsliderpro', $this->localizations() );

        /** Load scripts */
        wp_enqueue_media();
        wp_enqueue_script( 'esp-admin' );
        do_action( 'easingsliderpro_enqueue_admin_scripts' );

    }

    /**
     * Prints the global admin styling
     *
     * @since 2.0
     */
    public function print_global_styles() {

        /** Get plugin images URL */
        $image_url = plugins_url( dirname( plugin_basename( self::get_file() ) ) . DIRECTORY_SEPARATOR .'images' );

        /** Print the CSS */
        ob_start();
        ?>
        <style type="text/css">
            #toplevel_page_easingsliderpro_edit_slideshows .wp-menu-image {
                background: url(<?php echo esc_attr( $image_url ); ?>/menu_icon.png) no-repeat 7px -18px;
            }

            #toplevel_page_easingsliderpro_edit_slideshows:hover .wp-menu-image,
            #toplevel_page_easingsliderpro_edit_slideshows.wp-has-current-submenu .wp-menu-image {
                background-position: 7px 6px;
                opacity: 1;
            }

            #toplevel_page_easingsliderpro_edit_slideshows .wp-menu-image img {
                visibility: hidden;
            }

            @media
                only screen and (-webkit-min-device-pixel-ratio: 2),
                only screen and ( min--moz-device-pixel-ratio: 2),
                only screen and ( -o-min-device-pixel-ratio: 2.0),
                only screen and ( min-device-pixel-ratio: 2),
                only screen and ( min-resolution: 2.0dppx) {
                    
                    #toplevel_page_easingsliderpro_edit_slideshows .wp-menu-image {
                        background-image: url(<?php echo esc_attr( $image_url ); ?>/menu_icon_2x.png);
                        background-size: 16px 40px;
                    }
                    
                }
        </style>
        <?php
        print preg_replace( '/\s+/', ' ', ob_get_clean() );

    }
    
    /**
     * Translations localized via Javascript
     *
     * @since 2.0
     */
    public function localizations() {
        return array(
            'plugin_url' => '/wp-content/plugins/'. dirname( plugin_basename( self::get_file() ) ) .'/',
            'warn' => __( 'Are you sure you wish to do this? This cannot be reversed.', 'easingsliderpro' ),
            'warn_reset' => __( 'Are you sure you wish to do this? Your current settings and slideshows will be removed. This cannot be reversed.', 'easingsliderpro' ),
            'delete_image' => __( 'Are you sure you wish to delete this image? This cannot be reversed.', 'easingsliderpro' ),
            'delete_images' => __( 'Are you sure you wish to delete all of this slideshows images? This cannot be reversed.', 'easingsliderpro' ),
            'delete_slideshow' => __( 'Are you sure you wish to delete this slideshow? This cannot be reversed.', 'easingsliderpro' ),
            'delete_slideshows' => __( 'Are you sure you wish to delete these slideshows? This cannot be reversed.', 'easingsliderpro' ),
            'media_upload' => array(
                'title' => __( 'Add Images to Slideshow', 'easingsliderpro' ),
                'button' => __( 'Insert into slideshow', 'easingsliderpro' ),
                'change' => __( 'Use this image', 'easingsliderpro' ),
                'discard_changes' => __( 'Are you sure you wish to discard your changes?', 'easingsliderpro' )
            )
        );
    }
    
    /**
     * Prints the backbone templates used in the admin area
     *
     * @since 2.0
     */
    public function print_backbone_templates() {

        /** Bail if not a EasingSliderPro page */
        if ( !$this->is_easingsliderpro_page )
            return;

        /** Slide template */
        echo '<script type="text/html" id="tmpl-slide"><div class="thumbnail" data-id="{{ data.id }}"><a href="#" class="delete-button"></a><img src="{{ data.sizes.thumbnail.url }}" alt="{{ data.alt }}" /></div></script>';
        
        /** Slide editor template */
        echo '<script type="text/html" id="tmpl-edit-slide">';
        require dirname( self::get_file() ) . DIRECTORY_SEPARATOR .'templates'. DIRECTORY_SEPARATOR .'editslideshow-slide.php';
        echo '</script>';

        /** Media Library custom sidebar */
        echo '<script type="text/html" id="tmpl-image-details">';
        require dirname( self::get_file() ) . DIRECTORY_SEPARATOR .'templates'. DIRECTORY_SEPARATOR .'editslideshow-media-details.php';
        echo '</script>';
        
    }

    /**
     * Shows the appropriate slideshow view based on URL queries
     *
     * @since 2.0
     */
    public function choose_slideshow_view() {

        /** Redirect to 'Edit Slideshow' page if appropriate */
        if ( isset( $_GET['edit'] ) && $_GET['edit'] ) {
            $this->edit_slideshow_view();
            return;
        }

        /** Redirect to 'Add New' page if appropriate */
        if ( $_GET['page'] == 'easingsliderpro_add_slideshow' ) {
            $this->add_slideshow_view();
            return;
        }

        /** Redirect to slideshow list view (if all else fails) */
        $this->slideshow_list_view();

    }
    
    /**
     * List view for managing slideshows
     *
     * @since 2.0
     */
    public function slideshow_list_view() {

        /** Load the list view template */
        require dirname( self::get_file() ) . DIRECTORY_SEPARATOR .'templates'. DIRECTORY_SEPARATOR .'listslideshows.php';

    }
    
    /**
     * Edit a slideshow view
     *
     * @since 2.0
     */
    public function edit_slideshow_view() {

        /** Get and extract slideshow variables */
        $slideshow = ESP_Database::get_instance()->get_slideshow( $_GET['edit'] );

        /** Load the edit view template */
        require dirname( self::get_file() ) . DIRECTORY_SEPARATOR .'templates'. DIRECTORY_SEPARATOR .'editslideshow.php';

    }
    
    /**
     * Add a slideshow view
     *
     * @since 2.0
     */
    public function add_slideshow_view() {

        /** Load the list view template */
        require dirname( self::get_file() ) . DIRECTORY_SEPARATOR .'templates'. DIRECTORY_SEPARATOR .'editslideshow.php';

    }

    /**
     * Customizer view
     *
     * @since 2.0
     */
    public function customizer_view() {

        /** Load the customizer view template */
        require dirname( self::get_file() ) . DIRECTORY_SEPARATOR .'templates'. DIRECTORY_SEPARATOR .'customizer.php';

    }
    
    /**
     * Import/Export slideshows view
     *
     * @since 2.0
     */
    public function import_export_view() {

        /** Load the edit settings view */
        require dirname( self::get_file() ) . DIRECTORY_SEPARATOR .'templates'. DIRECTORY_SEPARATOR .'importexport.php';

    }
    
    /**
     * Edit settings view
     *
     * @since 2.0
     */
    public function edit_settings_view() {

        /** Load the edit settings view */
        require dirname( self::get_file() ) . DIRECTORY_SEPARATOR .'templates'. DIRECTORY_SEPARATOR .'editsettings.php';

    }

}

/**
 * Handy helper & legacy functions for displaying a slideshow
 *
 * @author Matthew Ruddy
 * @since 2.0
 */
if ( !function_exists( 'easingsliderpro' ) ) {
    function easingsliderpro( $id ) {
        echo ESP_Slideshow::get_instance()->display_slideshow( $id );
    }
}
if ( !function_exists( 'riva_slider_pro' ) ) {
    function riva_slider_pro( $id ) {
        echo ESP_Slideshow::get_instance()->display_slideshow( $id );
    }
}
<?php

add_action('init', 'of_options');

if (!function_exists('of_options')) {
    function of_options()
    {
        $shortname = "coll";

        //Access the WordPress Categories via an Array
        $of_categories = array();
        $of_categories_obj = get_categories('hide_empty=0');
        foreach ($of_categories_obj as $of_cat) {
            $of_categories[$of_cat->cat_ID] = $of_cat->cat_name;
        }
        $categories_tmp = array_unshift($of_categories, "Select a category:");

        //Access the WordPress Pages via an Array
        $of_pages = array();
        $of_pages_obj = get_pages('sort_column=post_parent,menu_order');
        foreach ($of_pages_obj as $of_page) {
            $of_pages[$of_page->ID] = $of_page->post_title;
        }
        $of_pages_tmp = array_unshift($of_pages, "Select a page:");

        //Testing
        $of_options_select = array("one", "two", "three", "four", "five");
        $of_options_radio = array("one" => "One", "two" => "Two", "three" => "Three", "four" => "Four", "five" => "Five");
        $of_options_homepage_blocks = array(
            "disabled" => array(
                "placebo" => "placebo", //REQUIRED!
                "block_one" => "Block One",
                "block_two" => "Block Two",
                "block_three" => "Block Three",
            ),
            "enabled" => array(
                "placebo" => "placebo", //REQUIRED!
                "block_four" => "Block Four",
            ),
        );


        //Stylesheets Reader
        $alt_stylesheet_path = LAYOUT_PATH;
        $alt_stylesheets = array();

        if (is_dir($alt_stylesheet_path)) {
            if ($alt_stylesheet_dir = opendir($alt_stylesheet_path)) {
                while (($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false) {
                    if (stristr($alt_stylesheet_file, ".css") !== false) {
                        $alt_stylesheets[] = $alt_stylesheet_file;
                    }
                }
            }
        }

        //Background Images Reader
        $bg_images_path = get_stylesheet_directory() . '/images/bg/'; // change this to where you store your bg images
        $bg_images_url = get_template_directory_uri() . '/images/bg/'; // change this to where you store your bg images
        $bg_images = array();

        if (is_dir($bg_images_path)) {
            if ($bg_images_dir = opendir($bg_images_path)) {
                while (($bg_images_file = readdir($bg_images_dir)) !== false) {
                    if (stristr($bg_images_file, ".png") !== false || stristr($bg_images_file, ".jpg") !== false) {
                        $bg_images[] = $bg_images_url . $bg_images_file;
                    }
                }
            }
        }

        /*-----------------------------------------------------------------------------------*/
        /* TO DO: Add options/functions that use these */
        /*-----------------------------------------------------------------------------------*/

        //More Options
        $uploads_arr = wp_upload_dir();
        $all_uploads_path = $uploads_arr['path'];
        $all_uploads = get_option('of_uploads');
        $other_entries = array("Select a number:", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19");
        $body_repeat = array("no-repeat", "repeat-x", "repeat-y", "repeat");
        $body_pos = array("top left", "top center", "top right", "center left", "center center", "center right", "bottom left", "bottom center", "bottom right");

        // Image Alignment radio box
        $of_options_thumb_align = array("alignleft" => "Left", "alignright" => "Right", "aligncenter" => "Center");

        // Image Links to Options
        $of_options_image_link_to = array("image" => "The Image", "post" => "The Post");


        /*-----------------------------------------------------------------------------------*/
        /* The Options Array */
        /*-----------------------------------------------------------------------------------*/

        // Set the Options Array
        global $of_options;
        $of_options = array();
        //
        //
        // General Settings
        //
        //
        $of_options[] = array("name" => __("General Settings", 'framework'),
            "type" => "heading");
        $of_options[] = array("name" => __("Site Logo", 'framework'),
            "desc" => __("Select the site logo.", 'framework'),
            "id" => $shortname . "_site_logo",
            "std" => "",
            "type" => "media");

        $of_options[] = array("name" => __("Site Favicon", 'framework'),
            "desc" => __("Select the site favicon", 'framework'),
            "id" => $shortname . "_custom_favicon",
            "std" => "",
            "type" => "media");

        $of_options[] = array("name" => __("Footer Text", 'framework'),
            "desc" => __("Insert the footer text thats on the bottom right of the screen. You can use html tags", 'framework'),
            "id" => $shortname . "_footer_text",
            "std" => "Copyright © 2012 by <a href='http://www.themeforest.net'>collision</a>",
            "type" => "textarea");
        $of_options[] = array("name" => __("Tracking Code", 'framework'),
            "desc" => __("Insert your analytics code", 'framework'),
            "id" => $shortname . "_analytics",
            "std" => "",
            "type" => "textarea");

        //
        //
        // STYLE Settings
        //
        //
        $of_options[] = array("name" => __("Styling Options", 'framework'),
            "type" => "heading");

        $of_options[] = array("name" => __("Skin Stylesheet", 'framework'),
            "desc" => __("Select your themes alternative color scheme.", 'framework'),
            "id" => $shortname . "_alt_stylesheet",
            "std" => "skin.css",
            "type" => "select",
            "options" => $alt_stylesheets);

        $of_options[] = array("name" => "sad",
            "id" => $shortname . "_default_menu_info",
            "std" => __('The following options allow you to set the colors used by the menu', 'framework'),
            "type" => "info");
        $of_options[] = array("name" => __('Menu Background Color', 'framework'),
            "desc" => __('Select the default background color for the menu.', 'framework'),
            "id" => $shortname  . "_default_menu_color",
            "std" => "",
            "type" => "color");
        $of_options[] = array("name" => __('Menu Item Color', 'framework'),
            "desc" => __('Select the default menu item color.', 'framework'),
            "id" => $shortname  . "_default_menu_item_color",
            "std" => "",
            "type" => "color");
        $of_options[] = array("name" => __('Menu Item Over Color', 'framework'),
            "desc" => __('Select the over menu item color.', 'framework'),
            "id" => $shortname  . "_default_menu_item_over_color",
            "std" => "",
            "type" => "color");



        $of_options[] = array("name" => "asd",
            "id" => $shortname . "_default_bg_info",
            "std" => __('The following options allow you to set the default background behavior for each page. Each of these options can be overridden on the individual post/page/portfolio level. You are in complete control.', 'framework'),
            "type" => "info");

        $of_options[] = array("name" => __('Default Background Image', 'framework'),
            "desc" => __('Set the default background image to be used on all pages.', 'framework'),
            "id" => $shortname . "_default_bg_image",
            "std" => "",
            "type" => "media");
        $repeat_options = array('no-repeat' => 'No Repeat', 'repeat' => "Repeat", 'repeat-x' => 'Repeat Horizontally', 'repeat-y' => 'Repeat Vertically');
        $of_options[] = array("name" => __('Default Background Repeat', 'framework'),
            "desc" => __('Select the default background repeat for the background image', 'framework'),
            "id" => $shortname  . "_default_bg_repeat",
            "std" => "no-repeat",
            "type" => "radio",
            "options" => $repeat_options);
        $position_options = array('left' => 'Left', 'right' => "Right", 'center' => 'Centered', 'full' => 'Full Screen');
        $of_options[] = array("name" => __('Default Background Position', 'framework'),
            "desc" => __('Select the default background position for the background image', 'framework'),
            "id" => $shortname  . "_default_bg_position",
            "std" => "left",
            "type" => "radio",
            "options" => $position_options);
        $of_options[] = array("name" => __('Default Background Color', 'framework'),
            "desc" => __('Select the default background color for pages.', 'framework'),
            "id" => $shortname  . "_default_bg_color",
            "std" => "",
            "type" => "color");
        //
        //
        // Front Page Settings
        // .........................................................................
        //

        $of_options[] = array("name" => __("Front Page Settings", 'framework'),
            "type" => "heading");
        $of_options[] = array("name" => __("Menu Position", 'framework'),
            "desc" => __("Check this if you don't want the menu to be visible on the first section", 'framework'),
            "id" => $shortname . "_no_fp_menu",
            "std" => "",
            "type" => "checkbox");
        //
        //
        // Blog Settings
        // .........................................................................
        //
        $of_options[] = array("name" => __("Blog Page Settings", 'framework'),
            "type" => "heading");
        $of_options[] = array("name" => __("Blog Page", 'framework'),
            "desc" => __("Select your blog page. this is necessary for some backend configuration", 'framework'),
            "id" => $shortname . "_blog_page",
            "std" => "",
            "type" => "select",
            "options" => $of_pages);
    }
}


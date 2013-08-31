<?php

/*-----------------------------------------------------------------------------------*/
/* create custom post => technology
/*-----------------------------------------------------------------------------------*/
if (!function_exists('create_technology')) {
    function create_technology()
    {
        $tech_args = array(
            'label' => __('Technology Items', 'framework'),
            'singular_label' => __('Technology', 'framework'),
            'public' => true,
            'show_ui' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => true,
            'menu_icon' => get_bloginfo('template_directory').'/img/ico-admin-technology.png', // 16px16
            'supports' => array('title','editor')
         );
        register_post_type('technology_item', $tech_args);
    }

    add_action('init', 'create_technology');
}

$tech_box_data = array(
    'id' => 'data-meta-box',
    'title' => 'Tech Icon',
    'page' => 'technology_item',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => __('Icon', 'framework'),
            'desc' => __('Upload the tech icon. Once uploaded, click "Insert to Post".', 'framework'),
            'id' => 'tech_icon',
            "type" => "text",
            'std' => ''
        ),
        array(
            'name' => '',
            'desc' => '',
            'id' => 'tech_icon_button',
            'type' => 'button',
            'std' => 'Browse'
        ),
    )
);

add_action('admin_menu', 'add_tech_meta_boxes');

function add_tech_meta_boxes()
{
    global $tech_box_data;

    add_meta_box(
    	$tech_box_data['id'], 
    	$tech_box_data['title'], 
    	'show_tech_box_data', 
    	$tech_box_data['page'], 
    	$tech_box_data['context'], 
    	$tech_box_data['priority']
    	);
}

function show_tech_box_data()
{
    global $tech_box_data, $post;

    // Use nonce for verification
    echo '<input type="hidden" name="meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

    echo '<table class="form-table">';

    foreach ($tech_box_data['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);

        switch ($field['type']) 
        {
            //If Text
            case 'text':

                echo '<tr>',
                '<th style="width:25%"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><span style=" display:block; color:#999; margin:5px 0 0 0; line-height: 18px;">' . $field['desc'] . '</span></label></th>',
                '<td>';
                echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta
                    : stripslashes(htmlspecialchars(($field['std']), ENT_QUOTES)), '" size="30" style="width:75%; margin-right: 20px; float:left;" />';

                break;

            //If Button
            case 'button':
                echo '<input style="float: left;" type="button" class="button browse img" name="', $field['id'], '" id="', $field['id'], '" value="Browse" />';
                echo     '</td>',
                '</tr>';

                break;

            //If Select
            case 'checkbox':

                echo '<tr>',
                '<th style="width:25%"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><span style=" display:block; color:#999; margin:5px 0 0 0; line-height: 18px;">' . $field['desc'] . '</span></label></th>',
                '<td>';

                echo '<input type="hidden" name="' . $field['id'] . '" id="' . $field['id'] . '" ', $meta ? ' checked="checked"' : '', '/>';
                echo '<input type="checkbox" name="' . $field['id'] . '" id="' . $field['id'] . '" ', $meta ? ' checked="checked"' : '', '/>';

                break;
        }
    }

    echo '</table>';

}

add_action('save_post', 'save_tech_meta_data');

function save_tech_meta_data($post_id)
{
    global $tech_box_data;
    $new = '';
    // verify nonce
    if (isset($_POST['meta_box_nonce']) && !wp_verify_nonce($_POST['meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    // check quickedit
    if (defined('DOING_AJAX') && DOING_AJAX)
        return;

    // check permissions
    if (isset($_POST['post_type']) && 'team' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }


    foreach ($tech_box_data['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        if (isset($_POST[$field['id']])) {
            $new = $_POST[$field['id']];
        }
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}



/*------------------------------------*\
	Section Shortcode
\*------------------------------------*/
add_shortcode('technology', 'create_tech_func');

function create_tech_func($atts) {

	//[footag foo="bar"]
	//return "foo = {$atts['foo']}";
	
	$tech_items = get_tech_items();
	//print_r($tech_items);
	

	
	foreach ($tech_items as $item) 
	{

		if (!empty($item)) 
		{
			$lis .= '<li>
						<img src="' . $item["img"]  . '" />\
						<div>
							<h4>' . $item["title"]  . '</h4>
							<p>' . $item["content"]  . '</p>
						</div>
					</li>';
			
		}
	}
	
	$content .= '<ul id="section_items">' . $lis . '</ul>';
	
	/* $html = '<section role="'.$atts['role'].'" class="' . esc_attr($bg) . ' ' . esc_attr($align) . '" ><div class="wrap" >'.do_shortcode($content).'</div><div class="' . esc_attr($class) . '"></div></section>'; */
	
	return $content; 
}
/*-----------------------------------------------------------------------------------*/
/* Get technology items
/*-----------------------------------------------------------------------------------*/
if (!function_exists('get_tech_items')) {
    function get_tech_items()
    {
    	/** first get the posts **/
        global $wpdb;
		$querystr = "
			SELECT $wpdb->posts.post_title,$wpdb->posts.post_content,$wpdb->posts.ID
			FROM $wpdb->posts
			WHERE $wpdb->posts.post_type = 'technology_item'
			";

			$query = $wpdb->get_results($querystr, OBJECT);
		
		$data = array();
		
        foreach ($query as $key => $item ) 
        {
        	$data[$key]['img'] = get_post_meta($item->ID,'tech_icon',TRUE);
            $data[$key]['title'] = get_the_title($item->ID);
            $data[$key]['content'] = $item->post_content;
        }
       
        return $data;
    }
}

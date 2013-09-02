<?php

/*-----------------------------------------------------------------------------------*/
/* create custom post => Core Values
/*-----------------------------------------------------------------------------------*/
if (!function_exists('create_core_values')) {
    function create_core_values()
    {
        $core_args = array(
            'label' => __('Core Values', 'framework'),
            'singular_label' => __('Core Value', 'framework'),
            'public' => true,
            'show_ui' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => true,
            'menu_icon' => get_bloginfo('template_directory').'/img/ico-admin-core.png', // 16px16
            'supports' => array('title','editor')
         );
        register_post_type('core_values_item', $core_args);
    }
    
    // Disabling core values
    //add_action('init', 'create_core_values');
}

$core_box_data = array(
    'id' => 'data-meta-box',
    'title' => 'Core Icon',
    'page' => 'core_values_item',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => __('Icon', 'framework'),
            'desc' => __('Upload the core icon. Once uploaded, click "Insert to Post".', 'framework'),
            'id' => 'core_icon',
            "type" => "text",
            'std' => ''
        ),
        array(
            'name' => '',
            'desc' => '',
            'id' => 'core_icon_button',
            'type' => 'button',
            'std' => 'Browse'
        ),
    )
);

add_action('admin_menu', 'add_core_meta_boxes');

function add_core_meta_boxes()
{
    global $core_box_data;

    add_meta_box(
    	$core_box_data['id'], 
    	$core_box_data['title'], 
    	'show_core_box_data', 
    	$core_box_data['page'], 
    	$core_box_data['context'], 
    	$core_box_data['priority']
    	);
}

function show_core_box_data()
{
    global $core_box_data, $post;

    // Use nonce for verification
    echo '<input type="hidden" name="meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

    echo '<table class="form-table">';

    foreach ($core_box_data['fields'] as $field) {
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

add_action('save_post', 'save_core_meta_data');

function save_core_meta_data($post_id)
{
    global $core_box_data;
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


    foreach ($core_box_data['fields'] as $field) {
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
add_shortcode('core_values', 'create_core_func');

function create_core_func($atts) {
	
	$core_items = get_core_items();
	
	foreach ($core_items as $item) 
	{

		if (!empty($item)) 
		{
			$lis .= '<li>
						<img src="' . $item["img"]  . '" />
						<div>
							<h4>' . $item["title"]  . '</h4>
							<p>' . $item["content"]  . '</p>
						</div>
					</li>';
		}
	}
	
	$content .= '<ul id="section_items">' . $lis . '</ul>';
		
	return $content; 
}
/*-----------------------------------------------------------------------------------*/
/* Get Core Values Items
/*-----------------------------------------------------------------------------------*/
if (!function_exists('get_core_items')) {
    function get_core_items()
    {
    	/** first get the posts **/
        global $wpdb;
		$querystr = "
			SELECT $wpdb->posts.post_title,$wpdb->posts.post_content,$wpdb->posts.ID
			FROM $wpdb->posts
			WHERE $wpdb->posts.post_type = 'core_values_item'
			";

			$query = $wpdb->get_results($querystr, OBJECT);
		
		$data = array();
		
        foreach ($query as $key => $item ) 
        {
        	$data[$key]['img'] = get_post_meta($item->ID,'core_icon',TRUE);
            $data[$key]['title'] = get_the_title($item->ID);
            $data[$key]['content'] = $item->post_content;
        }
       
        return $data;
    }
}






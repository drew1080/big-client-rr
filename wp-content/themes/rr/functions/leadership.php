<?php

/*-----------------------------------------------------------------------------------*/
/* create custom post => Leadership Page
/*-----------------------------------------------------------------------------------*/
if (!function_exists('create_leadership')) {
    function create_leadership()
    {
        $tech_args = array(
            'label' => __('Leadership-Test', 'framework'),
            'singular_label' => __('Leadership', 'framework'),
            'public' => true,
            'show_ui' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => true,
            'menu_icon' => get_bloginfo('template_directory').'/img/ico-admin-leadership.png', // 16px16
            'supports' => array('title','editor','thumbnail')
         );
        register_post_type('leadership_gallery', $tech_args);
    }

    add_action('init', 'create_leadership');
}

$leadership_box_data = array(
    'id' => 'data-meta-box',
    'title' => 'Leader Personal Info',
    'page' => 'leadership_gallery',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => __('Image', 'framework'),
            'desc' => __('Upload the leader image. Once uploaded, click "Insert to Post".', 'framework'),
            'id' => 'hover_image',
            "type" => "text",
            'std' => ''
        ),
        array(
            'name' => '',
            'desc' => '',
            'id' => 'hover_image_button',
            'type' => 'button',
            'std' => 'Browse'
        ),
         array(
            'name' => __('Twitter', 'framework'),
            'desc' => __('Enter twitter acount.', 'framework'),
            'id' => 'leader_twitter',
            "type" => "text",
            'std' => ''
        ),
         array(
            'name' => __('LinkedIn', 'framework'),
            'desc' => __('Enter linkedin acount.', 'framework'),
            'id' => 'leader_linkedin',
            "type" => "text",
            'std' => ''
        ),
       
    )
);

add_action('admin_menu', 'add_leadership_meta_boxes');

function add_leadership_meta_boxes()
{
    global $leadership_box_data;

    add_meta_box(
    	$leadership_box_data['id'], 
    	$leadership_box_data['title'], 
    	'show_leadership_box_data', 
    	$leadership_box_data['page'], 
    	$leadership_box_data['context'], 
    	$leadership_box_data['priority']
    	);
}

function show_leadership_box_data()
{
    global $leadership_box_data, $post;

    // Use nonce for verification
    echo '<input type="hidden" name="meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

    echo '<table class="form-table">';

    foreach ($leadership_box_data['fields'] as $field) {
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

add_action('save_post', 'save_leadership_meta_data');

function save_leadership_meta_data($post_id)
{
    global $leadership_box_data;
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


    foreach ($leadership_box_data['fields'] as $field) {
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
add_shortcode('leadership', 'create_leadership_func');

function create_leadership_func($atts) {
	
	$leadership = get_leadership();
	
	foreach ($leadership as $item) 
	{

		if (!empty($item)) 
		{
			$lis .= '<li>
			      <img src="' . $item["thumbnail"]  . '" />
						<img src="' . $item["hover_image"]  . '" />
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
/* Get Leadership People
/*-----------------------------------------------------------------------------------*/
if (!function_exists('get_leadership')) {
    function get_leadership()
    {
    	/** first get the posts **/
        global $wpdb;
		$querystr = "
			SELECT $wpdb->posts.post_title,$wpdb->posts.post_content,$wpdb->posts.ID
			FROM $wpdb->posts
			WHERE $wpdb->posts.post_type = 'leadership_gallery'
			";

			$query = $wpdb->get_results($querystr, OBJECT);
		
		$data = array();
		
        foreach ($query as $key => $item ) 
        {
        	$data[$key]['hover_image'] = get_post_meta($item->ID,'hover_image',TRUE);
        	$data[$key]['thumbnail'] = wp_get_attachment_url( get_post_thumbnail_id($item->ID) );
        	$data[$key]['cartoon'] = get_post_meta($item->ID,'leader_cartoon',TRUE);
        	$data[$key]['twitter'] = get_post_meta($item->ID,'leader_cartoon',TRUE);
        	$data[$key]['linkedin'] = get_post_meta($item->ID,'leader_cartoon',TRUE);
          $data[$key]['title'] = get_the_title($item->ID);
          $data[$key]['content'] = $item->post_content;
        }
       
        return $data;
    }
}

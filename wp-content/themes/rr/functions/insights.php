<?php

/*-----------------------------------------------------------------------------------*/
/* create custom post => Insight Page
/*-----------------------------------------------------------------------------------*/

if (!function_exists('create_insight')) {
  function create_insight_taxonomies() {
  	register_taxonomy(
  		'rr-format',
  		array( 'insight' ),
  		array(
  			'label' => __( 'Format' ),
  			'rewrite' => array( 'slug' => 'rr-format' ),
  			'hierarchical' => true
  		)
  	);
  	
  	register_taxonomy(
  		'rr-topic',
  		array( 'insight' ),
  		array(
  			'label' => __( 'Topic' ),
  			'rewrite' => array( 'slug' => 'rr-topic' ),
  			'hierarchical' => true
  		)
  	);
  	register_taxonomy(
  		'rr-region',
  		array( 'insight' ),
  		array(
  			'label' => __( 'Region' ),
  			'rewrite' => array( 'slug' => 'rr-region' ),
  			'hierarchical' => true
  		)
  	);
  }
  
  add_action( 'init', 'create_insight_taxonomies' );
  
}
if (!function_exists('create_insight')) {
    function create_insight()
    {
        $insight_args = array(
            'label' => __('Insights', 'framework'),
            'singular_label' => __('Insight', 'framework'),
            'public' => true,
            'show_ui' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => true,
            'supports' => array('title','editor','thumbnail'),
            'taxonomies' => array('rr-format', 'rr-topic', 'rr-region')
         );
        register_post_type('insights_gallery', $insight_args);
    }

    add_action('init', 'create_insight');
}

$insight_box_data = array(
    'id' => 'data-meta-box',
    'title' => 'Insight Info',
    'page' => 'insights_gallery',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => __('Marketo iframe', 'framework'),
            'desc' => __('Please enter the Marketo iframe.', 'framework'),
            'id' => 'marketo_iframe',
            "type" => "text",
            'std' => ''
        ),
        array(
            'name' => __('Webinar Video URL', 'framework'),
            'desc' => __('Please enter the webinar video URL.', 'framework'),
            'id' => 'webinar_video_url',
            "type" => "text",
            'std' => ''
        ),
        array(
            'name' => __('Infographic Media URL', 'framework'),
            'desc' => __('Please enter the infographic media URL.', 'framework'),
            'id' => 'infographic_url',
            "type" => "text",
            'std' => ''
        ),
    )
);

add_action('admin_menu', 'add_insight_meta_boxes');

function add_insight_meta_boxes()
{
    global $insight_box_data;

    add_meta_box(
    	$insight_box_data['id'], 
    	$insight_box_data['title'], 
    	'show_insight_box_data', 
    	$insight_box_data['page'], 
    	$insight_box_data['context'], 
    	$insight_box_data['priority']
    	);
}

function show_insight_box_data()
{
    global $insight_box_data, $post;

    // Use nonce for verification
    echo '<input type="hidden" name="meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

    echo '<table class="form-table">';

    foreach ($insight_box_data['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);

        switch ($field['type']) 
        {
            //If Text
            case 'text':

                echo '<tr>',
                '<th style="width:25%"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><span style=" display:block; color:#999; margin:5px 0 0 0; line-height: 18px;">' . $field['desc'] . '</span></label></th>',
                '<td>';
                echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? esc_html($meta)
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

add_action('save_post', 'save_insight_meta_data');

function save_insight_meta_data($post_id)
{
    global $insight_box_data;
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


    foreach ($insight_box_data['fields'] as $field) {
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
add_shortcode('insights', 'create_insight_func');

function create_insight_func($atts) {
  extract( shortcode_atts( array(
    'class' => '',
		'bg' => 'white',
		'title' => ''), $atts ) );
	
  $insights = get_insights();
  
  $count = 1;
  $last_class = "";
	
	foreach ($insights as $key => $item ) 
	{
	  if ( $count % 4 == 0 ) {
	    $last_class = 'class="last-insight"';
	  }

		if (!empty($item)) 
		{
      switch ($item["format"]) {
          case "WHITE PAPERS":
          case "RESEARCH":
              $lis .= '<li ' . $last_class . ' >
                        <a class="insight-popouts item ' . $item["format_id"] . ' ' . $item["topic_ids"] . ' ' . $item["region_ids"] . '" rel="insights" href="#insight-'. $key . '" style="background-image: url(' . $item["thumbnail"]  . ')">
                          <span class="tagline">' . $item["format"]  . '</span>
                          <h4>' . $item["title"]  . '</h4>
                        </a>
                        <div id="insight-'. $key . '" class="custom-fancybox-popout">
                          <div class="custom-fancybox-popout-left">
                            <img class="insight-thumbnail" src="' . $item["thumbnail"]  . '" />
                            <span class="insight-category-popout">' . $item["format"] . '</span>
                            <h4>' . $item["title"]  . '</h4>
                          	<div class="custom-fancybox-popout-content">' . apply_filters('the_content', $item["content"])  . '</div>
                          </div>
                          <div class="custom-fancybox-popout-right">' . $item["marketo_iframe"] . '</div>
                        </div>
                      </li>';
              break;
          case "INFOGRAPHICS":
              $lis .= '<li ' . $last_class . ' >
                        <a class="item ' . $item["format_id"] . ' ' . $item["topic_ids"] . ' ' . $item["region_ids"] . '" target="_blank" href="' . $item["infographic_url"] . '" style="background-image: url(' . $item["thumbnail"]  . ')">
                          <span class="tagline">' . $item["format"]  . '</span>
                          <h4>' . $item["title"]  . '</h4>
                        </a>
                      </li>';
              break;
          case "WEBINARS":
              $lis .= '<li ' . $last_class . ' >
                        <a class="fancybox-media item ' . $item["format_id"] . ' ' . $item["topic_ids"] . ' ' . $item["region_ids"] . '" rel="insights" href="' . $item["webinar_video_url"] . '" style="background-image: url(' . $item["thumbnail"]  . ')">
                          <span class="tagline">' . $item["format"]  . '</span>
                          <h4>' . $item["title"]  . '</h4>                          
                        </a>
                      </li>';
              break;
      }
		}
		
		$last_class = "";
		$count++;
	}
	
	$format_args = array(
    'show_option_all'    => 'All',
	  'title_li'           => __( '' ),
    // 'id'        => 'format',
    // 'class'     => 'option-set',
    'taxonomy'  => 'rr-format',
    'echo' => 0
  );
  
  $topic_args = array(
    'show_option_all'    => 'All',
	  'title_li'           => __( '' ),
    // 'id'        => 'topic',
    // 'class'     => 'option-set',
    'taxonomy'  => 'rr-topic',
    'echo' => 0
  );
  
  $region_args = array(
    'show_option_all'    => 'All',
	  'title_li'           => __( '' ),
    // 'id'        => 'region',
    // 'class'     => 'option-set',
    'taxonomy'  => 'rr-region',
    'echo' => 0
  );
	
  $content .= '<div class="filter-options">

              <div class="format-wrap filter-wrap">
                <span>FORMAT: </span>
                <div class="format-dropdown insight-dropdown">
                  <span id="format-select" class="selector-box">All</span>
                  <ul id="format" class="option-set">
                  ' . wp_list_categories($format_args) . '
                  </ul>
                </div>
              </div>
              <div class="topic-wrap filter-wrap">
                <span>TOPIC: </span>
                <div class="topic-dropdown insight-dropdown">
                  <span id="topic-select" class="selector-box">All</span>
                  <ul id="topic" class="option-set">
                  ' . wp_list_categories($topic_args) . '
                  </ul>
                </div>
              </div>
              <div class="region-wrap filter-wrap">
                <span>REGION: </span>
                <div class="region-dropdown insight-dropdown">
                  <span id="region-select" class="selector-box">All</span>
                  <ul id="region" class="option-set">
                  ' . wp_list_categories($region_args) . '
                  </ul>
                </div>
              </div>
              </div>
              <div class="clear"></div>
              <section role="insight" class="custom-fancybox-wrapper '. esc_attr($class).' '. esc_attr($bg).'">
                <div id="insights" class="wrap">
                  <h3>'. esc_attr($title).'</h3>
                  <ul>' . $lis . '</ul>
                </div>
                <div class="clear"></div></section>';
	
	/* $html = '<section role="'.$atts['role'].'" class="' . esc_attr($bg) . ' ' . esc_attr($align) . '" ><div class="wrap" >'.do_shortcode($content).'</div><div class="' . esc_attr($class) . '"></div></section>'; */
	
	return $content; 
}
/*-----------------------------------------------------------------------------------*/
/* Get Insights
/*-----------------------------------------------------------------------------------*/
if (!function_exists('get_insights')) {
    function get_insights()
    { 
      $args=array(
          'post_type' => 'insights_gallery',
          'post_status' => 'publish',
          'posts_per_page' => 100,
          'caller_get_posts'=> 1
        );
        
      $my_query = null;
      $my_query = new WP_Query($args);
      $insight_posts = $my_query->get_posts();
      
      $data = array();
      
      foreach ($insight_posts as $key => $item ) 
      {
        $data[$key]['thumbnail'] = wp_get_attachment_url( get_post_thumbnail_id($item->ID) );
        $data[$key]['title'] = get_the_title($item->ID);
        $data[$key]['content'] = $item->post_content;
        $data[$key]['marketo_iframe'] = get_post_meta($item->ID,'marketo_iframe',TRUE);
        $data[$key]['webinar_video_url'] = get_post_meta($item->ID,'webinar_video_url',TRUE);
        $data[$key]['infographic_url'] = get_post_meta($item->ID,'infographic_url',TRUE);
        
        $format_list = wp_get_post_terms($item->ID, 'rr-format');
        $data[$key]['format'] = strtoupper($format_list[0]->name);
        $data[$key]['format_id'] = 'format-' . $format_list[0]->term_id;
        
        
        $topic_ids = '';
        $topic_list = wp_get_post_terms($item->ID, 'rr-topic');
        
        foreach ($topic_list as $topic) {
          $topic_ids .= 'topic-' .  $topic->term_id . ' ';
        }
        
        $data[$key]['topic_ids'] = $topic_ids;
        
        
        $region_ids = '';
        $region_list = wp_get_post_terms($item->ID, 'rr-region');
        
        foreach ($region_list as $region) {
          $region_ids .= 'region-' .  $region->term_id . ' ';
        }
        
        $data[$key]['region_ids'] = $region_ids;
      }
      
      wp_reset_query();
    
      return $data;
    }
}

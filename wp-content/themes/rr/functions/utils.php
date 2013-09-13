<?php
/*-----------------------------------------------------------------------------------*/
/* get trusted by logos
/*-----------------------------------------------------------------------------------*/
if (!function_exists('get_trusted_by_logos')) {
    function get_trusted_by_logos()
    {        
        $args=array(
            'post_type' => 'clients',
            'post_status' => 'publish',
            'posts_per_page' => 100,
            'caller_get_posts'=> 1
          );
        
        $my_query = null;
        $my_query = new WP_Query($args);
        $posts = $my_query->get_posts();
        
        $data = array();
        
        foreach ($posts as $key => $item ) 
        {
          $data[$key]['coll_image'] = get_post_meta($item->ID,'coll_image',TRUE);
        }
        
        wp_reset_query();
        

        return $data;
    }
}

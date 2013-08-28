<?php
/*-----------------------------------------------------------------------------------*/
/* get trusted by logos
/*-----------------------------------------------------------------------------------*/
if (!function_exists('get_trusted_by_logos')) {
    function get_trusted_by_logos()
    {
    	/** first get the posts **/
        global $wpdb;
        $query = "SELECT child.guid FROM $wpdb->posts parent 
LEFT JOIN $wpdb->posts as child ON child.post_type = 'attachment' AND child.post_title = REPLACE(parent.post_name, \"-\", \"\")
WHERE parent.post_type = 'clients' AND parent.post_status='publish'";
        $content = $wpdb->get_results($query);
        sort($content);
		
        $data = array();

        foreach ($content as $content_item) {
            $data[] = $content_item->guid;
        }

        return $data;
    }
}

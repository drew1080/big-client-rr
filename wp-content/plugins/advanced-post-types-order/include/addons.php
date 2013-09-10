<?php

    //woocomerce archive fix
    add_action ('apto_order_update_hierarchical', 'wooc_apto_order_update_hierarchical', 10);
    function wooc_apto_order_update_hierarchical($data)
        {
            global $wpdb, $blog_id;
            
            
            $post_type  = $_POST['post_type'];
            $term_id    = $_POST['term_id'];
            $taxonomy   = $_POST['taxonomy'];
            $lang       = $_POST['lang'];

                 
            $query = "INSERT INTO `". $wpdb->base_prefix ."apto` 
                        (`post_id`, `term_id`, `post_type`, `taxonomy`, `blog_id`, `lang`) 
                        VALUES ('".$data['post_id']."', '".$term_id."', '".$post_type."', '".$taxonomy."', ".$blog_id.", '".$lang."');";
            $results = $wpdb->get_results($query);
            
            //return if not woocommerce
            if ($post_data->post_type != "product" || !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
                return $data;
                
            //clear the transients
            global $woocommerce;
            $woocommerce->clear_product_transients($data['post_id']);
        }
        
    //woocommerce grouped / simple icons
    add_filter ('apto_reorder_item_additional_details', 'wooc_apto_reorder_item_additional_details', 10, 2);
    function wooc_apto_reorder_item_additional_details($additiona_details, $post_data)
        {
            if ($post_data->post_type != "product" || !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
                return $additiona_details;
            
            //to be updated
                            
            return $additiona_details;
        }

?>
<?php
$client_box_data = array(
    'id' => 'data-meta-box',
    'title' => 'Client Settings',
    'page' => 'clients',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => __('Image', 'framework'),
            'desc' => __('Upload the client logo. Once uploaded, click "Insert to Post".', 'framework'),
            'id' => 'coll_image',
            "type" => "text",
            'std' => ''
        ),
        array(
            'name' => '',
            'desc' => '',
            'id' => 'coll_image_button',
            'type' => 'button',
            'std' => 'Browse'
        ),
    )
);


add_action('admin_menu', 'add_clients_meta_boxes');
function add_clients_meta_boxes()
{
    global $client_box_data;

    add_meta_box($client_box_data['id'], $client_box_data['title'], 'show_clients_box_data', $client_box_data['page'], $client_box_data['context'], $client_box_data['priority']);


}


function show_clients_box_data()
{
    global $client_box_data, $post;

    // Use nonce for verification
    echo '<input type="hidden" name="meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

    echo '<table class="form-table">';

    foreach ($client_box_data['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);

        switch ($field['type']) {


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


add_action('save_post', 'save_clients_meta_data');
function save_clients_meta_data($post_id)
{
    global $client_box_data;
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


    foreach ($client_box_data['fields'] as $field) {
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
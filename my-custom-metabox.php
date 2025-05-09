<?php

/**
 * Plugin Name:       My Custom Metabox
 * Plugin URI:        https://github.com/mominsarder12/My-Custom-Metabox
 * Description:       This is a custom plugin for WordPress
 * Version:           1.0.0
 * Author:            Momin Sarder
 * Author URI:        https://github.com/mominsarder12
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       my-custom-plugin
 * Domain Path:       /languages
 */

// Your plugin code starts here
if (!defined("ABSPATH")) {
    exit;
}

add_action('add_meta_boxes', 'my_custom_page_metabox_callback', 10);

function my_custom_page_metabox_callback() {
    //display the metabox in side panel of page
    add_meta_box(
        'my_custom_metabox',
        'My Custom Metabox - SEO',
        'create_my_custom_metabox',
        'page'

    );
}

//display the metabox in admin panel
function create_my_custom_metabox($post) {

    //create nonce field

    //include file form template/page-metabox.php using buffer
    ob_start();
    include_once plugin_dir_path(__FILE__) . 'template/page-metabox.php';
    $template = ob_get_contents();
    ob_end_clean();
    echo $template;
}

//save the data of metabox
add_action('save_post', 'save_my_custom_metabox_callback');

function save_my_custom_metabox_callback($post_id) {
    //check and verify nonce value

    if (!wp_verify_nonce($_POST['mcm_nonce_field'], 'save_my_custom_metabox_callback')) {
        return;
    }

    //check the autosave feature
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (isset($_POST['mcm-meta-title'])) {
        update_post_meta($post_id, 'mcm-meta-title', $_POST['mcm-meta-title']);
    }
    if (isset($_POST['mcm-meta-description'])) {
        update_post_meta($post_id, 'mcm-meta-description', $_POST['mcm-meta-description']);
    }
}

//display the data of metabox in frontend in page as meta tag
add_action('wp_head', 'display_my_custom_metabox_callback');

function display_my_custom_metabox_callback() {
    if (is_page()) {
        $post_id = get_the_ID();
        $title = get_post_meta($post_id, 'mcm-meta-title', true);
        $description = get_post_meta($post_id, 'mcm-meta-description', true);
        echo "<meta name='title' content='$title'>";
        echo "<meta name='description' content='$description'>";
    }
}

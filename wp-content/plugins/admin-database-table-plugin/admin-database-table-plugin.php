<?php
/*
Plugin Name: Admin Database Table Plugin
Description: Displays data from a database table in a table in the WordPress admin area.
*/

function display_admin_database_table() {
    global $wpdb;

    $table_name = $wpdb->prefix.'wp_users'; // Ganti dengan nama tabel Anda
    $data = $wpdb->get_results("SELECT * FROM books");
	// print_r($data);
    if ($data) {
        echo '<table class="widefat">';
        echo '<thead><tr><th>ID</th><th>Title</th><th>Description</th></tr></thead>';
        echo '<tbody>';
        foreach ($data as $row) {
            echo '<tr>';
            echo '<td>' . $row->id . '</td>';
            echo '<td>' . $row->title . '</td>';
            echo '<td>' . $row->author . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo 'No data available.';
    }
}

function admin_database_table_page() {
    echo '<div class="wrap">';
    echo '<h1>Database Table in Admin</h1>';
    display_admin_database_table();
    echo '</div>';
}

function admin_database_table_menu() {
    add_menu_page('Database Table', 'Database Table', 'manage_options', 'admin-database-table', 'admin_database_table_page');
}

add_action('admin_menu', 'admin_database_table_menu');
// add_shortcode('simple_db_table_plugin', 'simple_db_table_plugin_content');
// add_action('wp_head', 'add_whatsapp_styles');
add_action('the_content', 'display_admin_database_table');
?>
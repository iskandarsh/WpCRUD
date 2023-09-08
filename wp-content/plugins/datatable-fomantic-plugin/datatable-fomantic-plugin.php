<?php
/*
Plugin Name: DataTables Fomantic UI Plugin
Description: Plugin untuk menampilkan data dengan DataTables dan Fomantic UI di WordPress.
Version: 1.0
Author: Nama Anda
*/

function enqueue_fomantic_ui_scripts() {
    // Tambahkan Fomantic UI CSS dan JavaScript
    wp_enqueue_style('fomantic-ui-css', 'https://cdn.jsdelivr.net/npm/fomantic-ui-css/semantic.min.css');
    wp_enqueue_script('jquery');
    wp_enqueue_script('fomantic-ui-js', 'https://cdn.jsdelivr.net/npm/fomantic-ui-css/semantic.min.js', array('jquery'), '', true);
}

add_action('wp_enqueue_scripts', 'enqueue_fomantic_ui_scripts');

function datatable_fomantic_ui_shortcode($atts) {
    // Kode untuk mengambil data dari sumber Anda (contoh: database)
    $data = array(
        array('1', 'John Doe', 'john@example.com'),
        array('2', 'Jane Smith', 'jane@example.com'),
        // Tambahkan data lainnya sesuai kebutuhan
    );

    // Kode untuk menghasilkan tabel HTML dengan data
    $table = '<table id="datatable" class="ui celled table">';
    $table .= '<thead><tr><th>ID</th><th>Nama</th><th>Email</th></tr></thead>';
    $table .= '<tbody>';
    
    foreach ($data as $row) {
        $table .= '<tr>';
        foreach ($row as $cell) {
            $table .= '<td>' . esc_html($cell) . '</td>';
        }
        $table .= '</tr>';
    }
    
    $table .= '</tbody></table>';

    // Kode JavaScript untuk menginisialisasi DataTables
    $script = '
    <script>
        jQuery(document).ready(function($) {
            $("#datatable").DataTable();
        });
    </script>';

    return $table . $script;
}

add_shortcode('datatable_fomantic_ui', 'datatable_fomantic_ui_shortcode');
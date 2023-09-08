<?php
/*
Plugin Name: Semantic UI DataTable Plugin
Description: Integrasi Semantic UI DataTable di WordPress
Version: 1.0
Author: Nama Anda
*/

// Enqueue Semantic UI assets
function semantic_ui_datatable_enqueue_assets() {
    wp_enqueue_style('semantic-ui', 'https://cdn.jsdelivr.net/npm/semantic-ui/dist/semantic.min.css');
    wp_enqueue_script('semantic-ui', 'https://cdn.jsdelivr.net/npm/semantic-ui/dist/semantic.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'semantic_ui_datatable_enqueue_assets');

// Shortcode for displaying Semantic UI DataTable
function semantic_ui_datatable_shortcode($atts) {
    // Proses atribut yang diberikan dalam shortcode
    
    // Contoh penggunaan DataTable Semantic UI
    $output = '<table class="ui celled table">';
    // ... Isi tabel ...
    $output .= '<tr>';
    $output .= '<td>ad</td>';
    $output .= '<td>sad</td>';
    $output .= '<td>asd</td>';
    $output .= '</table>';
    
    return $output;
}
add_shortcode('semantic_ui_datatable', 'semantic_ui_datatable_shortcode');
?>
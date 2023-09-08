<?php
/*
Plugin Name: WhatsApp Button
Description: Adds a WhatsApp contact button to your website.
*/

function add_whatsapp_button() {
    // Replace with your WhatsApp number
    $phone_number = '1234567890';

    // WhatsApp message
    $message = 'Hello, I have a question.';

    echo '<a href="https://wa.me/' . $phone_number . '?text=' . urlencode($message) . '" target="_blank" rel="nofollow noopener" class="whatsapp-button">Contact Us on WhatsApp</a>';
}

function add_whatsapp_styles() {
    echo '<style>.whatsapp-button { display: inline-block; background-color: #25D366; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; }</style>';
}

add_action('wp_head', 'add_whatsapp_styles');
add_action('the_content', 'add_whatsapp_button');

?>
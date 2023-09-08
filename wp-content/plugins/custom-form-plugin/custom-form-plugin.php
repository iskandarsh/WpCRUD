<?php
/*
Plugin Name: Custom Form Plugin
Description: Adds a custom form input to your website.
*/

function custom_form_shortcode() {
    ob_start();
    ?>
    <form action="" method="post">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
        
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        
        <label for="message">Message:</label>
        <textarea name="message" id="message" rows="5" required></textarea>
        
        <input type="submit" name="submit_form" value="Submit">
    </form>
    <?php
    return ob_get_clean();
}

add_shortcode('custom_form', 'custom_form_shortcode');

function handle_custom_form_submission() {
    if (isset($_POST['submit_form'])) {
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $message = sanitize_textarea_field($_POST['message']);

        // You can add further processing here (e.g., sending an email)
        // For this example, we'll just display the submitted data
        echo "Submitted Name: $name<br>";
        echo "Submitted Email: $email<br>";
        echo "Submitted Message: $message<br>";
    }
}

add_action('init', 'handle_custom_form_submission');

?>
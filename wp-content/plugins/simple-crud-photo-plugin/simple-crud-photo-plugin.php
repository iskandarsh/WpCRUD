<?php
/*
Plugin Name: Simple CRUD Photo Plugin
Description: A simple WordPress plugin for CRUD operations with photo upload.
Version: 1.0
Author: Your Name
*/

// Shortcode for displaying the CRUD form
function simple_crud_photo_form() {
    ob_start();
    ?>
    <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo isset($_GET['edit_id']) ? $_GET['edit_id'] : ''; ?>">
        <label for="title">Title:</label>
        <input type="text" name="title" required>
        
        <label for="description">Author:</label>
        <input type="text" name="author" required>
        
        <label for="photo">Photo:</label>
        <input type="file" name="photo" accept="image/*" required>
        
        <input type="submit" name="submit" value="Add Entry">
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('simple_crud_photo_form', 'simple_crud_photo_form');

// Process form submission
function process_simple_crud_photo_form() {
    global $wpdb;

    if (isset($_POST['submit'])) {
        $title = sanitize_text_field($_POST['title']);
        $author = sanitize_text_field($_POST['author']);
        
        $photo = $_FILES['photo'];
        $upload_dir = wp_upload_dir();
        $target_dir = $upload_dir['path'] . '/';
        $target_file = $target_dir . basename($photo['name']);
        
        if (move_uploaded_file($photo['tmp_name'], $target_file)) {
            $photo_url = $upload_dir['url'] . '/' . basename($photo['name']);
            $table_name = 'books';
            $book_data = array(
                'title' => $title,
                'author' => $author,
                'photo' => $photo_url,
            );
            $result_check= $wpdb->insert( $table_name, $book_data);
            if($result_check){
                //successfully inserted.
                echo 'sukses';
             }else{
               //something gone wrong
               echo 'gamasok'.$wpdb->last_error ;
             }
            // $wpdb->insert(
            //      'books',
            //     array(
            //         'title' => $title,
            //         'author' => $author,
            //         'photo' => $photo_url,
            //     )
            // );
           
            echo "Entry added successfully!";
        } else {
            echo "Failed to upload photo.";
        }
    }
}
add_action('init', 'process_simple_crud_photo_form');
?>
<?php
/**
 * Plugin Name: DataTable Plugin
 * Description: A simple plugin to display a DataTable on a page.
 * Version: 1.0
 */

// Enqueue DataTables assets
function datatable_plugin_enqueue_assets() {
    wp_enqueue_script('jquery');
    wp_enqueue_style('fomantic-ui-css', 'https://cdn.jsdelivr.net/npm/fomantic-ui-css/semantic.min.css');
    wp_enqueue_script('fomantic-ui-js', 'https://cdn.jsdelivr.net/npm/fomantic-ui-css/semantic.min.js', array('jquery'), '', true);

    wp_enqueue_style('datatables-css', 'https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css');
    wp_enqueue_script('datatables-js', 'https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js', array('jquery'), '1.10.25', true);

    wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css');
    wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array('jquery'), '4.0.13', true);
}

add_action('wp_enqueue_scripts', 'datatable_plugin_enqueue_assets');

// Create or update a book
function save_book($data) {
    global $wpdb;

    if (isset($data['submit'])) {
        $title = sanitize_text_field($data['title']);
        $author = sanitize_text_field($data['author']);
        
        $photo = $_FILES['photo'];
        $upload_dir = wp_upload_dir();
        $target_dir = $upload_dir['path'] . '/';
        $target_file = $target_dir . basename($photo['name']);
        
        if (move_uploaded_file($photo['tmp_name'], $target_file)) {
            $photo_url = $upload_dir['url'] . '/' . basename($photo['name']);
            $table_name = $wpdb->prefix . 'books'; // Use proper naming convention
            $book_data = array(
                'title' => $title,
                'author' => $author,
                'photo' => $photo_url,
            );

            if (isset($data['id']) && !empty($data['id'])) {
                $result_check =  $wpdb->update($table_name, $book_data, array('id' => $data['id']));
               
            } else {
                $result_check = $wpdb->insert($table_name, $book_data);
            }

           
            if ($result_check) {
                echo 'Entry added successfully!';
            } else {
                echo 'Failed to insert data: ' . $wpdb->last_error;
            }
        } else {
            echo "Failed to upload photo.";
        }
    }
}

// Delete a book
function delete_book($book_id) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'books'; // Use proper naming convention
    $wpdb->delete($table_name, array('id' => $book_id));
}

// Handle form submissions and delete actions
function handle_form_actions() {
    if (isset($_POST['submit'])) {
        save_book($_POST);
    }
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        $book_id = intval($_GET['id']);
        delete_book($book_id);
    }
}
add_action('init', 'handle_form_actions');

// Display the form and book list
function custom_crud_display() {
    ?>
    <!-- Add your HTML form here -->
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id']) ? $_GET['id'] : ''; ?>">
        <label for="title">Title:</label>
        <input type="text" name="title" value="<?php echo isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id']) ?  get_book_data($_GET['id'])->title : ''; ?>" required>
        
        <label for="author">Author:</label>
        <input type="text" name="author" value="<?php echo isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id']) ?  get_book_data($_GET['id'])->author : ''; ?>" required>
        
        <label for="photo">Photo:</label>
        <input type="file" name="photo" accept="image/*"  required>

        <select class="select2" name="my_select_field">
            <option value="option1">Option 1</option>
            <option value="option2">Option 2</option>
            <option value="option3">Option 3</option>
        </select>
        
        <input type="submit" name="submit" value="Add Entry">
    </form>
    <?php
}
function get_book_data($book_id) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'books'; // Use proper table name
    $query = $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $book_id);
    $book_data = $wpdb->get_row($query);

    return $book_data;
}
// Shortcode to display the DataTable
function datatable_shortcode($atts) {
    // Example data for demonstration
    global $wpdb;
    $data = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "books"); // Use proper table name

    custom_crud_display();

    // Table header
    $header = array('ID', 'Title', 'Author', 'Photo Url', 'Photo IMG', 'Action');

    // Generate table HTML
    $table_html = '<table id="datatable" class="ui celled table"><thead><tr>';

    foreach ($header as $header_item) {
        $table_html .= '<th>' . esc_html($header_item) . '</th>';
    }

    $table_html .= '</tr></thead><tbody>';

    foreach ($data as $row) {
        $table_html .= '<tr>';
        foreach ($row as $cell) {
            $table_html .= '<td>' . esc_html($cell) . '</td>';
        }
        $table_html .= '
        <td><img src="' . esc_url($row->photo) . '" alt="Book Cover" width="100" height="100"></td>
        <td>
            <a href="?action=edit&id=' . esc_attr($row->id) . '">Edit</a>
            <a href="?action=delete&id=' . esc_attr($row->id) . '">Delete</a>
        </td>';
        $table_html .= '</tr>';
    }

    $table_html .= '</tbody></table>';

    // JavaScript to initialize DataTables
    $script = '
    <script>
        jQuery(document).ready(function($) {
            $("#datatable").DataTable();
        });
        jQuery(document).ready(function($) {
            $(".select2").select2();
        });
    </script>';

    return $table_html . $script;
    
}
add_shortcode('datatable_plugin', 'datatable_shortcode');

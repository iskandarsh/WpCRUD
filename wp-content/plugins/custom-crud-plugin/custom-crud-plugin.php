<?php
/*
Plugin Name: Custom CRUD Plugin
Description: Adds a custom CRUD functionality for Books.
*/

// Create or update a book
function save_book($data) {
    global $wpdb;

    $table_name = 'books';

    $book_data = array(
        'title' => sanitize_text_field($data['title']),
        'author' => sanitize_text_field($data['author']),
    );

    if (isset($data['id']) && !empty($data['id'])) {
        $wpdb->update($table_name, $book_data, array('id' => $data['id']));
    } else {
        $wpdb->insert($table_name, $book_data);
    }
}

// Delete a book
function delete_book($book_id) {
    global $wpdb;

    $table_name = 'books';
    $wpdb->delete($table_name, array('id' => $book_id));
   
}

// Display the book list
function display_books() {
    global $wpdb;

    // $table_name ='books';
    // $books = $wpdb->get_results("SELECT * FROM $table_name");

    // echo '<table>';
    // echo '<tr><th>ID</th><th>Title</th><th>Author</th><th>Action</th></tr>';
    // foreach ($books as $book) {
    //     echo '<tr>';
    //     echo '<td>' . $book->id . '</td>';
    //     echo '<td>' . $book->title . '</td>';
    //     echo '<td>' . $book->author . '</td>';
    //     echo '<td><a href="?edit_id=' . $book->id . '">Edit</a> | <a href="?delete_id=' . $book->id . '">Delete</a></td>';
    //     echo '</tr>';
    // }
    // echo '</table>';

    $data = $wpdb->get_results("SELECT * FROM books");
	// print_r($data);
    if ($data) {
        echo '<br>';
        echo '<table class="widefat">';
        echo '<thead><tr><th>ID</th><th>Title</th><th>Description</th><th>Action</th></tr></thead>';
        echo '<tbody>';
        foreach ($data as $row) {
            echo '<tr>';
            echo '<td>' . $row->id . '</td>';
            echo '<td>' . $row->title . '</td>';
            echo '<td>' . $row->author . '</td>';
            echo '<td><a href="?edit_id=' . $row->id . '">Edit</a> | <a href="?delete_id=' . $row->id . '">Delete</a></td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo 'No data available.';
    }
}

// Handle form submissions
if (isset($_POST['submit'])) {
    $book_data = array(
        'title' => $_POST['title'],
        'author' => $_POST['author'],
    );
    save_book($book_data);
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $book_id = intval($_GET['delete_id']);
    delete_book($book_id);
}

// Display the form and book list
function custom_crud_display() {
    ?>
    <h2>Books</h2>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo isset($_GET['edit_id']) ? $_GET['edit_id'] : ''; ?>">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" value="<?php echo isset($_GET['edit_id']) ? get_book_data($_GET['edit_id'])->title : ''; ?>" required>
        <label for="author">Author:</label>
        <input type="text" name="author" id="author" value="<?php echo isset($_GET['edit_id']) ? get_book_data($_GET['edit_id'])->author : ''; ?>" required>
        <input type="submit" name="submit" value="Save">
    </form>
    <?php
    display_books();
}

// Hook into admin menu
function custom_crud_menu() {
    add_menu_page('Custom CRUD Plugin', 'Custom CRUD', 'manage_options', 'custom-crud', 'custom_crud_display');
}

add_action('admin_menu', 'custom_crud_menu');

?>
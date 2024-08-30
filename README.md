Pro Custom Fields Plugin Documentation
Overview
The Custom Fields Plugin for WordPress allows users to add and manage custom fields, including dynamic repeater fields, for various post types. This plugin enables users to create custom fields with different types such as text, number, image, and repeater. It integrates seamlessly into the WordPress admin interface and provides an easy way to manage custom fields for posts.

Features
Add Custom Fields: Create fields of various types (text, number, image, repeater).
Dynamic Repeater Fields: Add and manage multiple sets of fields dynamically.
Post Type Integration: Add fields to specific post types and manage their display.
Admin Interface: User-friendly admin page for managing custom fields.
Installation
Upload the Plugin:

Download the plugin ZIP file.
Go to WordPress Admin Dashboard > Plugins > Add New > Upload Plugin.
Upload the ZIP file and activate the plugin.
Activate the Plugin:

Go to WordPress Admin Dashboard > Plugins.
Find the Custom Fields Plugin and click Activate.
Configuration
Add Custom Fields:

Navigate to WordPress Admin Dashboard > Custom Fields.
Fill out the form to create a new custom field:
Field Name: The name of the field.
Field Type: Choose between text, number, image URL, or repeater.
Post Type: Select the post type to which this field will be added (e.g., post, page, custom post type).
Click the Add Field button to save.
Manage Existing Fields:

On the same page, you will see a table listing all added custom fields.
Each field displays its name, type, and associated post type.
Using Custom Fields
Adding Fields to Posts:

Edit or create a new post.
You will find a meta box labeled "Custom Fields" on the post edit screen.
Fill in the values for the custom fields as required. For repeater fields, use the "Add Item" button to add multiple sets of fields dynamically.
Displaying Custom Fields in single.php:

To display custom fields in your theme, use the following code snippet in your single.php file:

php
Copy code
<?php
// Fetch custom fields data from post meta
$fields = get_post_meta(get_the_ID(), '_cfp_fields', true);

// Check if there are any custom fields to display
if ($fields && is_array($fields)) {
    echo '<div class="custom-fields-section">';
    echo '<h3>Custom Fields</h3>';
    echo '<ul>';

    // Loop through each field and display based on type
    foreach ($fields as $field) {
        // Extract field data
        $name = isset($field['name']) ? esc_html($field['name']) : '';
        $type = isset($field['type']) ? esc_html($field['type']) : '';
        $value = isset($field['value']) ? esc_html($field['value']) : '';

        // Display based on field type
        echo '<li>';
        echo '<strong>' . $name . ':</strong> ';

        switch ($type) {
            case 'text':
                // Display text field value
                echo $value;
                break;

            case 'number':
                // Display number field value
                echo $value;
                break;

            case 'image':
                // Display image field (assuming the value is a URL)
                if (!empty($value) && filter_var($value, FILTER_VALIDATE_URL)) {
                    // Display the image using the URL
                    echo '<img src="' . esc_url($value) . '" alt="' . $name . '" style="max-width:200px;"/>';
                } else {
                    echo 'Invalid image URL';
                }
                break;

            case 'repeater':
                // Display repeater fields
                if (!empty($value) && is_array($value)) {
                    echo '<ul>';
                    foreach ($value as $sub_value) {
                        echo '<li>' . esc_html($sub_value) . '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo 'No repeater data available';
                }
                break;

            default:
                // Default display for other types if added later
                echo $value;
                break;
        }

        echo '</li>';
    }

    echo '</ul>';
    echo '</div>';
}
?>
Advanced Usage
Handling Repeater Fields
For repeater fields, the plugin provides JavaScript functionality to dynamically add and remove items. This allows users to manage multiple sets of data easily. Repeater fields are saved as arrays in the post meta and displayed accordingly in the single.php file.

Custom Post Types
You can specify the post types when adding custom fields. This ensures that fields are only shown in relevant post types. Make sure to select the correct post type in the plugin settings.

Troubleshooting
Custom Fields Not Displaying: Ensure that the single.php file is correctly modified to fetch and display custom fields. Verify that fields are saved in the post meta.
Repeater Fields Not Working: Check if JavaScript is enabled in your browser and ensure no JavaScript errors occur on the admin page.
Contact chhotelaljatav.clj@gmail.com.


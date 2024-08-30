Pro Custom Fields
Author: Chhote Lal Jatav
Version: 1.0.0
Description: A WordPress plugin to create, manage, and display custom fields dynamically, including text, number, image, and repeater fields.

Overview
The Pro Custom Fields plugin extends the capabilities of WordPress by allowing users to add and manage custom fields for posts, pages, and custom post types. This plugin supports various field types and includes dynamic repeater fields for flexible content management.

Features
Add Custom Fields: Create custom fields of different types (text, number, image, repeater).
Dynamic Repeater Fields: Add and manage multiple sets of fields dynamically.
Post Type Integration: Associate custom fields with specific post types.
Admin Interface: Intuitive interface for managing custom fields.
Display Fields in Theme: Easily display custom fields in your theme using provided code snippets.
Installation
Upload the Plugin:

Download the plugin ZIP file.
Go to WordPress Admin Dashboard > Plugins > Add New > Upload Plugin.
Upload the ZIP file and activate the plugin.
Activate the Plugin:

Go to WordPress Admin Dashboard > Plugins.
Locate Pro Custom Fields and click Activate.
Configuration
Add Custom Fields:

Navigate to WordPress Admin Dashboard > Custom Fields.
Fill in the required details:
Field Name: The name of the field.
Field Type: Choose from text, number, image URL, or repeater.
Post Type: Select the post type to which this field will be added.
Click Add Field to save.
Manage Fields:

View and manage your custom fields from the same page.
Edit or delete fields as necessary.
Usage
Adding Fields to Posts:

Edit or create a new post.
Locate the "Custom Fields" meta box in the post edit screen.
Enter values for the custom fields. For repeater fields, use the "Add Item" button to manage multiple entries.
Displaying Custom Fields in single.php:

Use the following code snippet in your single.php file to display custom fields:

// Fetch custom fields data from post meta
$post_id = get_the_ID();
$fields = get_option('_cfp_fields', []);

if ($fields) {
    echo '<div class="custom-fields-section">';
    echo '<h3>Custom Fields</h3>';
    echo '<ul>';

    foreach ($fields as $field) {
        $name = isset($field['name']) ? $field['name'] : '';
        $type = isset($field['type']) ? $field['type'] : '';
        
        // Retrieve the field value
        $value = get_post_meta($post_id, '_cfp_field_' . $name, true);
					if (!empty($value) && is_array($value)) {
						echo '<ul>';
						foreach ($value as $sub_value) {
							echo '<li>' . esc_html($sub_value) . '</li>';
						}
						echo '</ul>';
					} else {
						echo 'No repeater data found.';
					}

        if ($value) {
            echo '<li>';
            echo '<strong>' . esc_html($name) . ':</strong> ';

            switch ($type) {
                case 'text':
                case 'number':
                    // Display text or number field value
                    echo esc_html($value);
                    break;

                case 'image':
                    // Display image field (assuming the value is a URL)
                    if (filter_var($value, FILTER_VALIDATE_URL)) {
                        echo '<img src="' . esc_url($value) . '" alt="' . esc_attr($name) . '" style="max-width:200px;"/>';
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
                        echo 'No repeater data found.';
                    }
                    break;

                default:
                    // Default display for other types
                    echo esc_html($value);
                    break;
            }

            echo '</li>';
        }
    }

    echo '</ul>';
    echo '</div>';
}


Advanced Usage
Handling Repeater Fields
For repeater fields, the plugin provides JavaScript functionality to dynamically add and remove items. This allows users to manage multiple sets of data easily. Repeater fields are saved as arrays in the post meta and displayed accordingly in the single.php file.

Custom Post Types
You can specify the post types when adding custom fields. This ensures that fields are only shown in relevant post types. Make sure to select the correct post type in the plugin settings.

Troubleshooting
Custom Fields Not Displaying: Ensure that the single.php file is correctly modified to fetch and display custom fields. Verify that fields are saved in the post meta.
Repeater Fields Not Working: Check if JavaScript is enabled in your browser and ensure no JavaScript errors occur on the admin page.
Contact
For support or questions regarding the plugin, please contact the plugin author at chhotelaljatav.clj@gmail.com

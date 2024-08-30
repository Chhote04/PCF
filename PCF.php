<?php
/*
Plugin Name: Pro Custom Feildes 
Description: Advanced Custom Feilds plugin for WordPress Developed using ChatGPT by Chhote Lal Jatav. inspired by ACF plugin WP Engine 
Version: 1.0
Author: C L Jatav
Author URI:https://www.coursera.org/user/b5f888727e157b6c6a4cca2f0389bbf6

*/



// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register the admin menu for the plugin
add_action('admin_menu', 'cfpui_register_menu');
function cfpui_register_menu() {
    add_menu_page(
        'Pro Custom Fields',
        'Pro Custom Fields',
        'manage_options',
        'cfpui_custom_fields',
        'cfpui_custom_fields_page',
        'dashicons-admin-generic',
        20
    );
}

// Display the Pro Custom Fields page in the admin
function cfpui_custom_fields_page() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cfpui_add_field'])) {
        $fields = get_option('_cfp_fields', []);
        $new_field = [
            'name' => sanitize_text_field($_POST['field_name']),
            'type' => sanitize_text_field($_POST['field_type']),
            'post_type' => sanitize_text_field($_POST['post_type']),
        ];
        $fields[] = $new_field;
        update_option('_cfp_fields', $fields);
    }

    // Get the existing fields
    $fields = get_option('_cfp_fields', []);
    ?>
    <div class="wrap">
        <h1>Pro Pro Custom Fields</h1>
        <form method="post">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Field Name</th>
                    <td><input type="text" name="field_name" required /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Field Type</th>
                    <td>
                        <select name="field_type">
                            <option value="text">Text</option>
                            <option value="number">Number</option>
                            <option value="image">Image URL</option>
                            <option value="repeater">Repeater</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Post Type</th>
                    <td>
                        <select name="post_type">
                            <?php
                            $post_types = get_post_types(['public' => true], 'objects');
                            foreach ($post_types as $post_type) {
                                echo '<option value="' . esc_attr($post_type->name) . '">' . esc_html($post_type->label) . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <input type="submit" name="cfpui_add_field" class="button-primary" value="Add Field" />
        </form>

        <h2>Existing Fields</h2>
        <table class="widefat fixed" cellspacing="0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Post Type</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($fields): ?>
                    <?php foreach ($fields as $field): ?>
                        <tr>
                            <td><?php echo esc_html($field['name']); ?></td>
                            <td><?php echo esc_html($field['type']); ?></td>
                            <td><?php echo esc_html($field['post_type']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No fields added yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}

// Add meta box for Pro Custom Fields on post types
add_action('add_meta_boxes', 'cfpui_add_meta_box');
function cfpui_add_meta_box() {
    $fields = get_option('_cfp_fields', []);

    // Add meta boxes for each post type with defined fields
    foreach ($fields as $field) {
        if (isset($field['post_type'])) {
            add_meta_box(
                'cfpui_custom_fields_' . $field['post_type'],
                'Pro Custom Fields',
                'cfpui_display_meta_box',
                $field['post_type'],
                'normal',
                'high',
                $field
            );
        }
    }
}

// Display Pro Custom Fields in the meta box
function cfpui_display_meta_box($post, $meta) {
    $field = $meta['args'];

    // Retrieve the current value of the custom field for this post
    $value = get_post_meta($post->ID, '_cfp_field_' . $field['name'], true);

    // Render input field according to its type
    echo '<p><label>' . esc_html($field['name']) . ':</label>';

    if ($field['type'] === 'repeater') {
        // Display repeater fields dynamically
        echo '<div class="repeater-container" data-name="' . esc_attr($field['name']) . '">';
        if (!empty($value) && is_array($value)) {
            foreach ($value as $index => $sub_value) {
                echo '<div class="repeater-item">';
                echo '<input type="text" name="_cfp_field_' . esc_attr($field['name']) . '[' . $index . ']" value="' . esc_attr($sub_value) . '" style="width:80%;" />';
                echo '<button type="button" class="remove-repeater-item button">Remove</button>';
                echo '</div>';
            }
        }
        echo '</div>';
        echo '<button type="button" class="add-repeater-item button">Add Item</button>';
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const addButton = document.querySelector('.add-repeater-item');
                if (addButton) {
                    addButton.addEventListener('click', function() {
                        const container = document.querySelector('.repeater-container');
                        const index = container.querySelectorAll('.repeater-item').length;
                        const item = document.createElement('div');
                        item.classList.add('repeater-item');
                        item.innerHTML = `
                            <input type="text" name="_cfp_field_${container.getAttribute('data-name')}[${index}]" style="width:80%;" />
                            <button type="button" class="remove-repeater-item button">Remove</button>
                        `;
                        container.appendChild(item);
                        item.querySelector('.remove-repeater-item').addEventListener('click', function() {
                            item.remove();
                        });
                    });
                }
                document.querySelectorAll('.remove-repeater-item').forEach(function(button) {
                    button.addEventListener('click', function() {
                        button.closest('.repeater-item').remove();
                    });
                });
            });
        </script>
        <?php
    } elseif ($field['type'] === 'image') {
        echo '<input type="text" name="_cfp_field_' . esc_attr($field['name']) . '" value="' . esc_url($value) . '" style="width:100%;" placeholder="Enter image URL" />';
    } else {
        echo '<input type="' . esc_attr($field['type']) . '" name="_cfp_field_' . esc_attr($field['name']) . '" value="' . esc_attr($value) . '" style="width:100%;" />';
    }
    echo '</p>';
}

// Save Pro Custom Fields when the post is saved
add_action('save_post', 'cfpui_save_post_meta');
function cfpui_save_post_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = get_option('_cfp_fields', []);

    foreach ($fields as $field) {
        if ($field['type'] === 'repeater') {
            $repeater_values = [];
            if (isset($_POST['_cfp_field_' . $field['name']]) && is_array($_POST['_cfp_field_' . $field['name']])) {
                foreach ($_POST['_cfp_field_' . $field['name']] as $sub_value) {
                    $repeater_values[] = sanitize_text_field($sub_value);
                }
            }
            update_post_meta($post_id, '_cfp_field_' . $field['name'], $repeater_values);
        } else {
            if (isset($_POST['_cfp_field_' . $field['name']])) {
                update_post_meta($post_id, '_cfp_field_' . $field['name'], sanitize_text_field($_POST['_cfp_field_' . $field['name']]));
            }
        }
    }
}

<?php
if (!defined('ABSPATH')) {
    exit;
}

class Draft_Post_Admin {
    public static function init() {
        add_action('admin_post_draft_post_generator', [__CLASS__, 'process_form']);
    }

    public static function admin_menu() {
        add_submenu_page(
            'tools.php',
            __('Draft Post Generator', 'draft-post-generator'),
            __('Draft Post Generator', 'draft-post-generator'),
            'manage_options',
            'draft-post-generator',
            [__CLASS__, 'render_admin_page']
        );
    }
    
    public static function render_admin_page() {
        if ($message = get_transient('draft_post_generator_message')) {
            echo '<div class="notice notice-success is-dismissible">';
            echo '<p>' . esc_html($message) . '</p>';
            echo '</div>';

            delete_transient('draft_post_generator_message');
        }
        ?>
        <div class="wrap">
            <h1><?php _e('Draft Post Generator', 'draft-post-generator'); ?></h1>
                <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
                <input type="hidden" name="action" value="draft_post_generator">
                <?php wp_nonce_field('draft_post_generator_action', 'draft_post_generator_nonce'); ?>

                <table class="form-table">
                    <tr>
                        <th><?php _e('Post Titles', 'draft-post-generator'); ?></th>
                        <td>
                            <textarea name="post_titles" rows="10" cols="50" required></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Post Type', 'draft-post-generator'); ?></th>
                        <td>
                            <select name="post_type">
                                <?php
                                $post_types = get_post_types(['public' => true], 'objects');
                                foreach ($post_types as $type) {
                                    echo '<option value="' . esc_attr($type->name) . '">' . esc_html($type->label) . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Post Status', 'draft-post-generator'); ?></th>
                        <td>
                            <select name="post_status">
                                <?php
                                $statuses = get_post_statuses();
                                foreach ($statuses as $status => $label) {
                                    echo '<option value="' . esc_attr($status) . '" ' . selected($status, 'draft', false) . '>' . esc_html($label) . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>

                <input type="submit" class="button-primary" value="<?php _e('Generate Drafts', 'draft-post-generator'); ?>">
            </form>
        </div>
        <?php
    }

    public static function process_form() {
        if (!isset($_POST['draft_post_generator_nonce']) || !wp_verify_nonce($_POST['draft_post_generator_nonce'], 'draft_post_generator_action')) {
            wp_die(__('Nonce verification failed', 'draft-post-generator'));
        }
    
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have permission.', 'draft-post-generator'));
        }
    
        $titles = sanitize_textarea_field($_POST['post_titles']);
        $post_type = sanitize_text_field($_POST['post_type']);
        $post_status = sanitize_text_field($_POST['post_status']);
    
        $created_count = Draft_Post_Creator::create_drafts_from_titles($titles, $post_type, [], null, '', $post_status);

        $post_type_object = get_post_type_object($post_type);
        $post_status_object = get_post_status_object($post_status);
    
        $post_type_label = $post_type_object ? strtolower($post_type_object->labels->singular_name) : $post_type;
        $post_status_label = $post_status_object ? strtolower($post_status_object->label) : $post_status;

        set_transient('draft_post_generator_message', sprintf(__('%d %s %s created successfully.', 'draft-post-generator'), $created_count, $post_status_label, $post_type_label), 30);
    
        wp_redirect(admin_url('tools.php?page=draft-post-generator'));
        exit;
    }    
}

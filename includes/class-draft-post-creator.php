<?php
if (!defined('ABSPATH')) {
    exit;
}

class Draft_Post_Creator {
    public static function init() {
        // Placeholder for future hooks
    }

    public static function create_drafts_from_titles($titles, $post_type, $taxonomy_terms, $menu_id, $default_content, $post_status) {
        if (empty($titles)) {
            return false;
        }
    
        $parent_map = [0];
        $lines = explode("\n", $titles);
        $created_count = 0;

        foreach ($lines as $title) {
            preg_match('/^(-+)?(.*)$/', $title, $matches);
            $hyphen_count = isset($matches[1]) ? strlen($matches[1]) : 0;
            $clean_title = trim($matches[2]);
    
            $parent_level = max(0, $hyphen_count);
            $parent_id = isset($parent_map[$parent_level]) ? $parent_map[$parent_level] : 0;
    
            $post_id = self::insert_post($clean_title, $parent_id, $post_type, $taxonomy_terms, $menu_id, $default_content, $post_status);

            if ($post_id) {
                $created_count++;
                $parent_map[$parent_level + 1] = $post_id;
            }
        }
    
        return $created_count;
    }

    private static function insert_post($title, $parent_id, $post_type, $taxonomy_terms, $menu_id, $default_content, $post_status) {
        $post_data = [
            'post_title'    => sanitize_text_field($title),
            'post_content'  => $default_content,
            'post_status'   => $post_status,
            'post_type'     => $post_type,
            'post_parent'   => $parent_id,
            'post_author'   => get_current_user_id(),
        ];

        $post_id = wp_insert_post($post_data);
        if(!is_wp_error($post_id)){
        } else {
            echo $post_id->get_error_message();
        }        

        if ($post_id && !empty($taxonomy_terms)) {
            wp_set_post_terms($post_id, $taxonomy_terms, get_object_taxonomies($post_type)[0]);
        }

        return $post_id;
    }
}

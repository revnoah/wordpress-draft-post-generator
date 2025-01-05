<?php
/**
 * Plugin Name: Draft Post Generator
 * Description: Quickly generate draft posts from new line-delimited titles with taxonomy, post status, and menu options.
 * Version: 1.0.0
 * Author: Noah Stewart
 * Text Domain: draft-post-generator
 */

if (!defined('ABSPATH')) {
    exit;  // Exit if accessed directly
}

// Define paths
define('DRAFT_POST_GEN_PATH', plugin_dir_path(__FILE__));
define('DRAFT_POST_GEN_URL', plugin_dir_url(__FILE__));

// Autoload files
require_once DRAFT_POST_GEN_PATH . 'includes/class-draft-post-creator.php';
require_once DRAFT_POST_GEN_PATH . 'admin/draft-post-admin.php';

// Initialize plugin
add_action('plugins_loaded', ['Draft_Post_Creator', 'init']);
add_action('init', ['Draft_Post_Admin', 'init']);
add_action('admin_menu', ['Draft_Post_Admin', 'admin_menu']);
<?php
/**
 * Plugin Name: Auto Post Type Post Delete
 * Plugin URI: https://github.com/kmfoysal06/auto-post-type-post-delete
 * Description: A WordPress plugin to manage and bulk delete posts from specific post types with secure AJAX functionality.
 * Version: 1.0.0
 * Author: kmfoysal06
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: auto-post-type-post-delete
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('APTPD_PLUGIN_URL', plugin_dir_url(__FILE__));
define('APTPD_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('APTPD_VERSION', '1.0.0');

/**
 * Main plugin class
 */
class AutoPostTypePostDelete {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Enqueue scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        
        // AJAX handlers
        add_action('wp_ajax_aptpd_delete_posts', array($this, 'handle_delete_posts'));
        add_action('wp_ajax_aptpd_get_post_count', array($this, 'handle_get_post_count'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_management_page(
            __('Post Type Manager', 'auto-post-type-post-delete'),
            __('Post Type Manager', 'auto-post-type-post-delete'),
            'manage_options',
            'auto-post-type-post-delete',
            array($this, 'admin_page_callback')
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        // Only load on our admin page
        if ($hook !== 'tools_page_auto-post-type-post-delete') {
            return;
        }
        
        // Enqueue SweetAlert2
        wp_enqueue_script(
            'sweetalert2',
            'https://cdn.jsdelivr.net/npm/sweetalert2@11',
            array(),
            '11.0.0',
            true
        );
        
        // Enqueue custom admin script
        wp_enqueue_script(
            'aptpd-admin',
            APTPD_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery', 'sweetalert2'),
            APTPD_VERSION,
            true
        );
        
        // Enqueue custom admin styles
        wp_enqueue_style(
            'aptpd-admin',
            APTPD_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            APTPD_VERSION
        );
        
        // Localize script for AJAX
        wp_localize_script('aptpd-admin', 'aptpd_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aptpd_nonce'),
            'messages' => array(
                'confirm_title' => __('Are you sure?', 'auto-post-type-post-delete'),
                'confirm_text' => __('This will permanently delete all posts from the selected post type. This action cannot be undone!', 'auto-post-type-post-delete'),
                'confirm_button' => __('Yes, delete all!', 'auto-post-type-post-delete'),
                'cancel_button' => __('Cancel', 'auto-post-type-post-delete'),
                'success_title' => __('Deleted!', 'auto-post-type-post-delete'),
                'success_text' => __('All posts have been deleted successfully.', 'auto-post-type-post-delete'),
                'error_title' => __('Error!', 'auto-post-type-post-delete'),
                'error_text' => __('An error occurred while deleting posts.', 'auto-post-type-post-delete'),
                'loading_title' => __('Processing...', 'auto-post-type-post-delete'),
                'loading_text' => __('Please wait while we delete the posts.', 'auto-post-type-post-delete'),
            )
        ));
    }
    
    /**
     * Admin page callback
     */
    public function admin_page_callback() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        // Get all post types
        $post_types = get_post_types(array('public' => true), 'objects');
        
        // Remove attachment post type
        unset($post_types['attachment']);
        
        include APTPD_PLUGIN_PATH . 'templates/admin-page.php';
    }
    
    /**
     * Handle AJAX request to get post count
     */
    public function handle_get_post_count() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'aptpd_nonce')) {
            wp_die('Security check failed');
        }
        
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        $post_type = sanitize_text_field($_POST['post_type']);
        
        // Get post count
        $count = wp_count_posts($post_type);
        $total = $count->publish + $count->draft + $count->private + $count->pending + $count->future;
        
        wp_send_json_success(array(
            'count' => $total,
            'post_type' => $post_type
        ));
    }
    
    /**
     * Handle AJAX request to delete posts
     */
    public function handle_delete_posts() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'aptpd_nonce')) {
            wp_die('Security check failed');
        }
        
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        $post_type = sanitize_text_field($_POST['post_type']);
        
        // Validate post type exists
        if (!post_type_exists($post_type)) {
            wp_send_json_error('Invalid post type');
        }
        
        // Get all posts of the specified type
        $posts = get_posts(array(
            'post_type' => $post_type,
            'numberposts' => -1,
            'post_status' => array('publish', 'draft', 'private', 'pending', 'future', 'trash')
        ));
        
        $deleted_count = 0;
        $errors = array();
        
        foreach ($posts as $post) {
            $result = wp_delete_post($post->ID, true); // Force delete
            if ($result) {
                $deleted_count++;
            } else {
                $errors[] = sprintf('Failed to delete post ID: %d', $post->ID);
            }
        }
        
        if (empty($errors)) {
            wp_send_json_success(array(
                'deleted_count' => $deleted_count,
                'message' => sprintf(__('Successfully deleted %d posts from %s post type.', 'auto-post-type-post-delete'), $deleted_count, $post_type)
            ));
        } else {
            wp_send_json_error(array(
                'deleted_count' => $deleted_count,
                'errors' => $errors,
                'message' => sprintf(__('Deleted %d posts with %d errors.', 'auto-post-type-post-delete'), $deleted_count, count($errors))
            ));
        }
    }
}

// Initialize the plugin
new AutoPostTypePostDelete();
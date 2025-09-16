<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="aptpd-container">
        <div class="aptpd-header">
            <h2><?php _e('Manage Post Types', 'auto-post-type-post-delete'); ?></h2>
            <p class="description">
                <?php _e('Select a post type below to view the number of posts and delete all posts from that post type. <strong>Warning:</strong> This action cannot be undone!', 'auto-post-type-post-delete'); ?>
            </p>
        </div>
        
        <div class="aptpd-content">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th scope="col" class="manage-column column-name column-primary">
                            <?php _e('Post Type', 'auto-post-type-post-delete'); ?>
                        </th>
                        <th scope="col" class="manage-column column-description">
                            <?php _e('Description', 'auto-post-type-post-delete'); ?>
                        </th>
                        <th scope="col" class="manage-column column-count">
                            <?php _e('Post Count', 'auto-post-type-post-delete'); ?>
                        </th>
                        <th scope="col" class="manage-column column-actions">
                            <?php _e('Actions', 'auto-post-type-post-delete'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($post_types)) : ?>
                        <?php foreach ($post_types as $post_type_name => $post_type_obj) : ?>
                            <?php
                            $count = wp_count_posts($post_type_name);
                            $total_count = $count->publish + $count->draft + $count->private + $count->pending + $count->future;
                            ?>
                            <tr data-post-type="<?php echo esc_attr($post_type_name); ?>">
                                <td class="column-name column-primary">
                                    <strong><?php echo esc_html($post_type_obj->labels->name); ?></strong>
                                    <small class="post-type-slug">(<?php echo esc_html($post_type_name); ?>)</small>
                                </td>
                                <td class="column-description">
                                    <?php echo esc_html($post_type_obj->description ?: __('No description available', 'auto-post-type-post-delete')); ?>
                                </td>
                                <td class="column-count">
                                    <span class="post-count" data-post-type="<?php echo esc_attr($post_type_name); ?>">
                                        <?php echo esc_html($total_count); ?>
                                    </span>
                                    <?php if ($total_count > 0) : ?>
                                        <small class="count-breakdown">
                                            (<?php printf(
                                                __('Published: %d, Draft: %d, Private: %d, Pending: %d, Future: %d', 'auto-post-type-post-delete'),
                                                $count->publish,
                                                $count->draft,
                                                $count->private,
                                                $count->pending,
                                                $count->future
                                            ); ?>)
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td class="column-actions">
                                    <?php if ($total_count > 0) : ?>
                                        <button type="button" 
                                                class="button button-secondary aptpd-refresh-count" 
                                                data-post-type="<?php echo esc_attr($post_type_name); ?>"
                                                title="<?php _e('Refresh post count', 'auto-post-type-post-delete'); ?>">
                                            <span class="dashicons dashicons-update-alt"></span>
                                            <?php _e('Refresh', 'auto-post-type-post-delete'); ?>
                                        </button>
                                        
                                        <button type="button" 
                                                class="button button-delete aptpd-delete-posts" 
                                                data-post-type="<?php echo esc_attr($post_type_name); ?>"
                                                data-post-type-label="<?php echo esc_attr($post_type_obj->labels->name); ?>"
                                                data-post-count="<?php echo esc_attr($total_count); ?>">
                                            <span class="dashicons dashicons-trash"></span>
                                            <?php _e('Delete All Posts', 'auto-post-type-post-delete'); ?>
                                        </button>
                                    <?php else : ?>
                                        <span class="no-posts"><?php _e('No posts to delete', 'auto-post-type-post-delete'); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="no-items">
                                <?php _e('No public post types found.', 'auto-post-type-post-delete'); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="aptpd-footer">
            <div class="aptpd-warning">
                <h3><?php _e('⚠️ Important Safety Information', 'auto-post-type-post-delete'); ?></h3>
                <ul>
                    <li><?php _e('This action will permanently delete ALL posts from the selected post type.', 'auto-post-type-post-delete'); ?></li>
                    <li><?php _e('Deleted posts cannot be recovered unless you have a backup.', 'auto-post-type-post-delete'); ?></li>
                    <li><?php _e('Always create a backup before performing bulk delete operations.', 'auto-post-type-post-delete'); ?></li>
                    <li><?php _e('Only users with "manage_options" capability can perform this action.', 'auto-post-type-post-delete'); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
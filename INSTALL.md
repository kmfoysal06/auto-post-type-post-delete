# Installation Guide for Auto Post Type Post Delete Plugin

## Prerequisites
- WordPress 4.6 or higher
- PHP 7.0 or higher  
- Administrator privileges on your WordPress site

## Installation Steps

### Method 1: Manual Upload
1. Download the plugin files
2. Upload the entire `auto-post-type-post-delete` folder to your `/wp-content/plugins/` directory
3. Log in to your WordPress admin panel
4. Go to **Plugins > Installed Plugins**
5. Find "Auto Post Type Post Delete" and click **Activate**

### Method 2: ZIP Upload (if creating a ZIP file)
1. Create a ZIP file of the `auto-post-type-post-delete` folder
2. Log in to your WordPress admin panel
3. Go to **Plugins > Add New**
4. Click **Upload Plugin**
5. Choose the ZIP file and click **Install Now**
6. Click **Activate Plugin**

## Usage Instructions

1. After activation, go to **Tools > Post Type Manager** in your WordPress admin
2. You'll see a table showing all public post types in your WordPress installation
3. Each post type displays:
   - Post type name and slug
   - Description
   - Current post count (with breakdown by status)
   - Action buttons

### Features Available:

#### Refresh Post Count
- Click the **Refresh** button to update the post count for any post type
- Useful after manually deleting posts or importing content

#### Delete All Posts
- Click **Delete All Posts** to remove all posts from a specific post type
- The system will show a confirmation dialog with:
  - Post type details
  - Total number of posts to be deleted
  - Warning about permanent deletion
- Confirm the action to proceed with deletion
- Progress indicator will show during the deletion process
- Success/error messages will appear after completion

## Security Features

This plugin implements multiple security layers:

- **Access Control**: Only users with `manage_options` capability can access the plugin
- **Nonce Protection**: All AJAX requests use WordPress nonces for verification
- **Input Validation**: All user inputs are sanitized and validated
- **Confirmation Steps**: Multiple confirmation dialogs prevent accidental deletions
- **Error Handling**: Comprehensive error handling with user-friendly messages

## Important Warnings

⚠️ **BACKUP YOUR DATA**: Always create a full backup of your WordPress site before using this plugin.

⚠️ **PERMANENT DELETION**: Deleted posts cannot be recovered unless you have a backup.

⚠️ **ALL POST STATUSES**: The plugin deletes posts in ALL statuses (published, draft, private, pending, future, trash).

## Troubleshooting

### Plugin doesn't appear in admin menu
- Check that you have administrator privileges
- Ensure the plugin is properly activated
- Verify that your user account has the `manage_options` capability

### AJAX requests fail
- Check that JavaScript is enabled in your browser
- Verify that your WordPress site allows AJAX requests
- Look for JavaScript errors in your browser's developer console

### Delete operation fails
- Ensure you have sufficient server resources
- Check for any server-side errors in your WordPress error log
- Verify that the post type exists and contains posts

## File Structure

```
wp-content/plugins/auto-post-type-post-delete/
├── auto-post-type-post-delete.php   # Main plugin file
├── templates/
│   └── admin-page.php               # Admin interface template
├── assets/
│   ├── js/
│   │   └── admin.js                 # JavaScript functionality
│   └── css/
│       └── admin.css                # Styling
├── README.md                        # Main documentation
└── INSTALL.md                       # This installation guide
```

## Support

For support, issues, or feature requests, please visit the plugin's GitHub repository.

## Uninstallation

To remove the plugin:

1. Go to **Plugins > Installed Plugins**
2. Find "Auto Post Type Post Delete"
3. Click **Deactivate**
4. Click **Delete**
5. Confirm the deletion

The plugin does not store any settings in the database, so deactivation and deletion will completely remove it from your site.
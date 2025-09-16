# Auto Post Type Post Delete

A WordPress plugin that provides a secure and user-friendly interface for managing and bulk deleting posts from specific post types.

## Features

- **Post Type Management**: View all public post types in your WordPress installation
- **Bulk Delete Functionality**: Delete all posts from a selected post type with a single click
- **Security First**: Implements WordPress nonces and capability checks to prevent unauthorized access
- **AJAX-Powered**: Smooth user experience with asynchronous operations
- **SweetAlert Integration**: Beautiful confirmation dialogs and user feedback
- **Real-time Post Counts**: View and refresh post counts for each post type
- **Responsive Design**: Works perfectly on desktop and mobile devices
- **Safety Warnings**: Clear warnings about the permanent nature of deletions

## Installation

1. Download the plugin files
2. Upload the `auto-post-type-post-delete` folder to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Navigate to **Tools > Post Type Manager** to access the plugin

## Usage

1. Go to **Tools > Post Type Manager** in your WordPress admin
2. You'll see a table with all public post types and their current post counts
3. Use the **Refresh** button to update post counts
4. Click **Delete All Posts** to remove all posts from a specific post type
5. Confirm the action in the popup dialog

## Security Features

- **Capability Checks**: Only users with `manage_options` capability can access the plugin
- **Nonce Verification**: All AJAX requests are protected with WordPress nonces
- **Data Sanitization**: All user inputs are properly sanitized
- **Confirmation Dialogs**: Multiple confirmation steps prevent accidental deletions

## Requirements

- WordPress 4.6 or higher
- PHP 7.0 or higher
- Administrator privileges

## File Structure

```
auto-post-type-post-delete/
├── auto-post-type-post-delete.php   # Main plugin file
├── templates/
│   └── admin-page.php               # Admin interface template
├── assets/
│   ├── js/
│   │   └── admin.js                 # JavaScript functionality
│   └── css/
│       └── admin.css                # Styling
└── README.md                        # Documentation
```

## Development

The plugin follows WordPress coding standards and best practices:

- Object-oriented programming structure
- Proper escaping and sanitization
- Internationalization ready (text domain: `auto-post-type-post-delete`)
- Responsive design principles
- Accessibility considerations

## Changelog

### Version 1.0.0
- Initial release
- Post type listing functionality
- Bulk delete with AJAX
- SweetAlert integration
- Complete security implementation
- Responsive admin interface

## License

This plugin is licensed under the GPL v2 or later.

## Support

For support, feature requests, or bug reports, please create an issue in the GitHub repository.

## Warning

⚠️ **Important**: This plugin permanently deletes posts. Always create a backup before using the bulk delete functionality. Deleted posts cannot be recovered unless you have a backup.

## Screenshots

The plugin provides:
- A clean admin interface showing all post types
- Real-time post counts with breakdown by status
- Secure confirmation dialogs with detailed information
- Beautiful success/error messages
- Responsive design for all devices
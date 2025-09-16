jQuery(document).ready(function($) {
    'use strict';
    
    /**
     * Handle refresh post count button click
     */
    $('.aptpd-refresh-count').on('click', function() {
        var $button = $(this);
        var postType = $button.data('post-type');
        var $countSpan = $('.post-count[data-post-type="' + postType + '"]');
        var $row = $button.closest('tr');
        
        // Disable button and show loading
        $button.prop('disabled', true);
        $button.find('.dashicons').addClass('spin');
        
        $.ajax({
            url: aptpd_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'aptpd_get_post_count',
                post_type: postType,
                nonce: aptpd_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $countSpan.text(response.data.count);
                    
                    // Update button visibility based on count
                    if (response.data.count > 0) {
                        $row.find('.aptpd-delete-posts').attr('data-post-count', response.data.count);
                        $row.find('.no-posts').hide();
                        $row.find('.aptpd-delete-posts, .aptpd-refresh-count').show();
                    } else {
                        $row.find('.aptpd-delete-posts, .aptpd-refresh-count').hide();
                        $row.find('.no-posts').show();
                    }
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: aptpd_ajax.messages.error_title,
                    text: 'Failed to refresh post count.',
                    confirmButtonColor: '#d33'
                });
            },
            complete: function() {
                // Re-enable button and remove loading
                $button.prop('disabled', false);
                $button.find('.dashicons').removeClass('spin');
            }
        });
    });
    
    /**
     * Handle delete posts button click
     */
    $('.aptpd-delete-posts').on('click', function() {
        var $button = $(this);
        var postType = $button.data('post-type');
        var postTypeLabel = $button.data('post-type-label');
        var postCount = $button.data('post-count');
        
        // Show confirmation dialog
        Swal.fire({
            title: aptpd_ajax.messages.confirm_title,
            html: aptpd_ajax.messages.confirm_text + '<br><br>' +
                  '<strong>Post Type:</strong> ' + postTypeLabel + ' (' + postType + ')<br>' +
                  '<strong>Total Posts:</strong> ' + postCount,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: aptpd_ajax.messages.confirm_button,
            cancelButtonText: aptpd_ajax.messages.cancel_button,
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading dialog
                Swal.fire({
                    title: aptpd_ajax.messages.loading_title,
                    text: aptpd_ajax.messages.loading_text,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Disable all delete buttons to prevent multiple requests
                $('.aptpd-delete-posts').prop('disabled', true);
                
                // Perform AJAX delete
                $.ajax({
                    url: aptpd_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'aptpd_delete_posts',
                        post_type: postType,
                        nonce: aptpd_ajax.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // Success - show success message
                            Swal.fire({
                                icon: 'success',
                                title: aptpd_ajax.messages.success_title,
                                text: response.data.message,
                                confirmButtonColor: '#28a745'
                            }).then(() => {
                                // Update the UI
                                updatePostCount(postType, 0);
                            });
                        } else {
                            // Error - show error message
                            var errorMessage = response.data && response.data.message ? 
                                             response.data.message : 
                                             aptpd_ajax.messages.error_text;
                            
                            Swal.fire({
                                icon: 'error',
                                title: aptpd_ajax.messages.error_title,
                                text: errorMessage,
                                confirmButtonColor: '#d33'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // AJAX error
                        Swal.fire({
                            icon: 'error',
                            title: aptpd_ajax.messages.error_title,
                            text: aptpd_ajax.messages.error_text + ' (' + error + ')',
                            confirmButtonColor: '#d33'
                        });
                    },
                    complete: function() {
                        // Re-enable all delete buttons
                        $('.aptpd-delete-posts').prop('disabled', false);
                    }
                });
            }
        });
    });
    
    /**
     * Update post count in the UI
     */
    function updatePostCount(postType, newCount) {
        var $row = $('tr[data-post-type="' + postType + '"]');
        var $countSpan = $row.find('.post-count');
        var $deleteButton = $row.find('.aptpd-delete-posts');
        var $refreshButton = $row.find('.aptpd-refresh-count');
        var $noPostsSpan = $row.find('.no-posts');
        
        // Update count display
        $countSpan.text(newCount);
        
        if (newCount > 0) {
            $deleteButton.attr('data-post-count', newCount);
            $deleteButton.show();
            $refreshButton.show();
            $noPostsSpan.hide();
        } else {
            $deleteButton.hide();
            $refreshButton.hide();
            $noPostsSpan.show();
        }
        
        // Remove count breakdown since all posts are deleted
        $row.find('.count-breakdown').remove();
    }
    
    /**
     * Add CSS class for spinning animation
     */
    $('<style>')
        .prop('type', 'text/css')
        .html('.spin { animation: spin 1s linear infinite; } @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }')
        .appendTo('head');
});
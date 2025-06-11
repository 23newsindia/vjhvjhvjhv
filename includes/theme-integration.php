<?php
if (!defined('ABSPATH')) {
    exit;
}

// Hook into the theme's download form submission
add_action('foxiz_subscribe', 'wns_handle_theme_download_subscription');

function wns_handle_theme_download_subscription() {
    // Get the email from the POST data (same as theme is using)
    $email = isset($_POST['EMAIL']) ? sanitize_email($_POST['EMAIL']) : '';
    
    if (empty($email) || !is_email($email)) {
        return; // Invalid email, let theme handle the error
    }
    
    // Additional validation
    if (strlen($email) > 254) {
        return; // Email too long
    }
    
    // Check for disposable email domains
    if (wns_is_disposable_email($email)) {
        return; // Skip disposable emails
    }
    
    // Ensure our tables exist
    wns_check_and_create_tables();
    
    global $wpdb;
    $table_name = WNS_TABLE_SUBSCRIBERS;
    
    // Check if email already exists in our subscriber list
    $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `$table_name` WHERE `email` = %s", $email));
    
    if ($wpdb->last_error) {
        error_log('WNS Plugin Error in theme integration: ' . $wpdb->last_error);
        return;
    }
    
    // If email doesn't exist, add it to our subscriber list
    if ($exists == 0) {
        $enable_verification = get_option('wns_enable_verification', false);
        $verified = $enable_verification ? 0 : 1; // Auto-verify download subscribers or follow plugin setting
        
        $inserted = $wpdb->insert($table_name, array(
            'email'     => $email,
            'verified'  => $verified
        ), array('%s', '%d'));
        
        if (!$inserted) {
            error_log('WNS Plugin Error: Failed to insert theme download subscriber - ' . $wpdb->last_error);
            return;
        }
        
        // If verification is enabled, send verification email
        if ($enable_verification) {
            wns_send_verification_email($email);
        }
        
        // Log successful addition for debugging
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('WNS Plugin: Successfully added download subscriber: ' . $email);
        }
    }
}

// Add admin notice to inform about theme integration
add_action('admin_notices', 'wns_theme_integration_notice');

function wns_theme_integration_notice() {
    // Only show on our plugin pages
    $screen = get_current_screen();
    if (!$screen || strpos($screen->id, 'wns-') === false) {
        return;
    }
    
    // Check if theme integration is working
    if (has_action('foxiz_subscribe', 'wns_handle_theme_download_subscription')) {
        echo '<div class="notice notice-success is-dismissible">';
        echo '<p><strong>' . esc_html__('Theme Integration Active:', 'wp-newsletter-subscription') . '</strong> ';
        echo esc_html__('Download form submissions are automatically being added to your newsletter subscriber list.', 'wp-newsletter-subscription');
        echo '</p>';
        echo '</div>';
    }
}

// Add settings to control theme integration behavior
add_action('admin_init', 'wns_register_theme_integration_settings');

function wns_register_theme_integration_settings() {
    register_setting('wns_settings_group', 'wns_auto_verify_download_subscribers', array(
        'type' => 'boolean',
        'default' => true,
    ));
    
    register_setting('wns_settings_group', 'wns_send_welcome_to_download_subscribers', array(
        'type' => 'boolean',
        'default' => false,
    ));
}

// Modify the settings page to include theme integration options
add_action('admin_init', 'wns_add_theme_integration_settings_section');

function wns_add_theme_integration_settings_section() {
    // This will be displayed in the main settings page
    add_action('wns_settings_page_after_unsubscribe', 'wns_render_theme_integration_settings');
}

function wns_render_theme_integration_settings() {
    ?>
    <!-- Theme Integration Section -->
    <h2><?php _e('Theme Integration', 'wp-newsletter-subscription'); ?></h2>
    <table class="form-table">
        <tr valign="top">
            <th scope="row"><?php _e('Download Form Integration', 'wp-newsletter-subscription'); ?></th>
            <td>
                <p class="description">
                    <?php if (has_action('foxiz_subscribe', 'wns_handle_theme_download_subscription')): ?>
                        <span style="color: green;">✓ <?php _e('Active - Download form emails are automatically added to newsletter subscribers', 'wp-newsletter-subscription'); ?></span>
                    <?php else: ?>
                        <span style="color: red;">✗ <?php _e('Not detected - Theme download form integration not found', 'wp-newsletter-subscription'); ?></span>
                    <?php endif; ?>
                </p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Auto-verify Download Subscribers', 'wp-newsletter-subscription'); ?></th>
            <td>
                <label>
                    <input type="checkbox" name="wns_auto_verify_download_subscribers" value="1" <?php checked(1, get_option('wns_auto_verify_download_subscribers', true)); ?> />
                    <?php _e('Automatically verify emails from download forms (recommended)', 'wp-newsletter-subscription'); ?>
                </label>
                <p class="description"><?php _e('When enabled, emails from download forms will be automatically verified and can receive newsletters immediately.', 'wp-newsletter-subscription'); ?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Send Welcome Email to Download Subscribers', 'wp-newsletter-subscription'); ?></th>
            <td>
                <label>
                    <input type="checkbox" name="wns_send_welcome_to_download_subscribers" value="1" <?php checked(1, get_option('wns_send_welcome_to_download_subscribers', false)); ?> />
                    <?php _e('Send welcome email to new download subscribers', 'wp-newsletter-subscription'); ?>
                </label>
                <p class="description"><?php _e('When enabled, users who subscribe via download forms will receive a welcome email.', 'wp-newsletter-subscription'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

// Enhanced version of the theme integration that respects the new settings
function wns_handle_theme_download_subscription_enhanced() {
    // Get the email from the POST data (same as theme is using)
    $email = isset($_POST['EMAIL']) ? sanitize_email($_POST['EMAIL']) : '';
    
    if (empty($email) || !is_email($email)) {
        return; // Invalid email, let theme handle the error
    }
    
    // Additional validation
    if (strlen($email) > 254) {
        return; // Email too long
    }
    
    // Check for disposable email domains
    if (wns_is_disposable_email($email)) {
        return; // Skip disposable emails
    }
    
    // Ensure our tables exist
    wns_check_and_create_tables();
    
    global $wpdb;
    $table_name = WNS_TABLE_SUBSCRIBERS;
    
    // Check if email already exists in our subscriber list
    $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `$table_name` WHERE `email` = %s", $email));
    
    if ($wpdb->last_error) {
        error_log('WNS Plugin Error in theme integration: ' . $wpdb->last_error);
        return;
    }
    
    // If email doesn't exist, add it to our subscriber list
    if ($exists == 0) {
        // Check settings for auto-verification
        $auto_verify_downloads = get_option('wns_auto_verify_download_subscribers', true);
        $enable_verification = get_option('wns_enable_verification', false);
        
        // Auto-verify if setting is enabled, otherwise follow main verification setting
        $verified = $auto_verify_downloads ? 1 : ($enable_verification ? 0 : 1);
        
        $inserted = $wpdb->insert($table_name, array(
            'email'     => $email,
            'verified'  => $verified
        ), array('%s', '%d'));
        
        if (!$inserted) {
            error_log('WNS Plugin Error: Failed to insert theme download subscriber - ' . $wpdb->last_error);
            return;
        }
        
        // Send welcome email if enabled
        $send_welcome = get_option('wns_send_welcome_to_download_subscribers', false);
        if ($send_welcome && $verified) {
            wns_send_download_welcome_email($email);
        }
        
        // If verification is required and auto-verify is disabled, send verification email
        if (!$auto_verify_downloads && $enable_verification) {
            wns_send_verification_email($email);
        }
        
        // Log successful addition for debugging
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('WNS Plugin: Successfully added download subscriber: ' . $email . ' (verified: ' . $verified . ')');
        }
    }
}

function wns_send_download_welcome_email($email) {
    if (!is_email($email)) {
        return false;
    }
    
    $subject = __('Welcome! Thanks for downloading our content', 'wp-newsletter-subscription');
    $message = __('Hi there,

Thank you for downloading our content! You\'ve been automatically subscribed to our newsletter to receive updates about new posts and exclusive content.

If you don\'t want to receive these emails, you can unsubscribe at any time using the link in our emails.

Best regards,
The Team', 'wp-newsletter-subscription');
    
    $headers = array('Content-Type: text/html; charset=UTF-8');
    
    return wp_mail($email, $subject, nl2br($message), $headers);
}

// Replace the original hook with the enhanced version
remove_action('foxiz_subscribe', 'wns_handle_theme_download_subscription');
add_action('foxiz_subscribe', 'wns_handle_theme_download_subscription_enhanced');
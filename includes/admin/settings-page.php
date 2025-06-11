<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'wns_add_main_menu_page');

function wns_add_main_menu_page() {
    // Add main menu page
    add_menu_page(
        __('WP Newsletter', 'wp-newsletter-subscription'),
        __('WP Newsletter', 'wp-newsletter-subscription'),
        'manage_options',
        'wns-settings',
        'wns_render_settings_page',
        'dashicons-email-alt',
        30
    );

    // Add submenu pages
    add_submenu_page(
        'wns-settings',
        __('Newsletter Settings', 'wp-newsletter-subscription'),
        __('Settings', 'wp-newsletter-subscription'),
        'manage_options',
        'wns-settings',
        'wns_render_settings_page'
    );
}

add_action('admin_init', 'wns_register_settings');

function wns_register_settings() {
    register_setting('wns_settings_group', 'wns_enable_verification', array(
        'type' => 'boolean',
        'default' => false,
    ));

    register_setting('wns_settings_group', 'wns_template_subscribe_subject', array(
        'type' => 'string',
        'default' => __('Welcome to Our Newsletter!', 'wp-newsletter-subscription')
    ));

    register_setting('wns_settings_group', 'wns_template_subscribe_body', array(
        'type' => 'string',
        'default' => __("Thank you for subscribing to our newsletter!\n\nClick the link below to verify your email:\n\n{verify_link}\n\nTo unsubscribe at any time: {unsubscribe_link}", 'wp-newsletter-subscription')
    ));

    register_setting('wns_settings_group', 'wns_template_unsubscribe_subject', array(
        'type' => 'string',
        'default' => __('You Have Been Unsubscribed', 'wp-newsletter-subscription')
    ));

    register_setting('wns_settings_group', 'wns_template_unsubscribe_body', array(
        'type' => 'string',
        'default' => __("You have successfully unsubscribed from our newsletter. We're sorry to see you go!\n\nIf this was a mistake, you can resubscribe here: {unsubscribe_link}", 'wp-newsletter-subscription')
    ));

    register_setting('wns_settings_group', 'wns_enable_new_post_notification', array(
        'type' => 'boolean',
        'default' => false,
    ));

    register_setting('wns_settings_group', 'wns_template_new_post_subject', array(
        'type' => 'string',
        'default' => __('New Blog Post: {post_title}', 'wp-newsletter-subscription')
    ));

    register_setting('wns_settings_group', 'wns_template_new_post_body', array(
        'type' => 'string',
        'default' => __("Hi there,\n\nWe've just published a new blog post that you might enjoy:\n\n{post_title}\n{post_excerpt}\n\nRead more: {post_url}\n\nThanks,\nThe Team\n\n{unsubscribe_link}", 'wp-newsletter-subscription')
    ));

    register_setting('wns_settings_group', 'wns_email_batch_size', array(
        'type' => 'integer',
        'default' => 100,
    ));

    register_setting('wns_settings_group', 'wns_email_send_interval_minutes', array(
        'type' => 'integer',
        'default' => 5,
    ));

    register_setting('wns_settings_group', 'wns_unsubscribe_page_id', array(
        'type' => 'integer',
        'default' => 0,
    ));

    // Theme integration settings
    register_setting('wns_settings_group', 'wns_auto_verify_download_subscribers', array(
        'type' => 'boolean',
        'default' => true,
    ));

    register_setting('wns_settings_group', 'wns_send_welcome_to_download_subscribers', array(
        'type' => 'boolean',
        'default' => false,
    ));

    // Social media settings for email templates
    register_setting('wns_settings_group', 'wns_facebook_url', array(
        'type' => 'string',
        'default' => '',
    ));

    register_setting('wns_settings_group', 'wns_twitter_url', array(
        'type' => 'string',
        'default' => '',
    ));

    register_setting('wns_settings_group', 'wns_instagram_url', array(
        'type' => 'string',
        'default' => '',
    ));

    register_setting('wns_settings_group', 'wns_linkedin_url', array(
        'type' => 'string',
        'default' => '',
    ));
}

function wns_render_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('WP Newsletter Subscription Settings', 'wp-newsletter-subscription'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('wns_settings_group');
            do_settings_sections('wns_settings_group');
            ?>

            <!-- Email Verification Section -->
            <h2><?php _e('Email Verification', 'wp-newsletter-subscription'); ?></h2>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Enable Email Verification', 'wp-newsletter-subscription'); ?></th>
                    <td>
                        <label><input type="checkbox" name="wns_enable_verification" value="1" <?php checked(1, get_option('wns_enable_verification', false)); ?> /> <?php _e('Verify new subscriptions via email.', 'wp-newsletter-subscription'); ?></label>
                    </td>
                </tr>
            </table>

            <!-- Email Templates Section -->
            <h2><?php _e('Email Templates', 'wp-newsletter-subscription'); ?></h2>
            <p class="description"><?php _e('Note: All emails now use professional HTML templates automatically. The content below will be wrapped in a beautiful, responsive design.', 'wp-newsletter-subscription'); ?></p>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Subscribe Email Subject', 'wp-newsletter-subscription'); ?></th>
                    <td><input type="text" name="wns_template_subscribe_subject" value="<?php echo esc_attr(get_option('wns_template_subscribe_subject', __('Welcome to Our Newsletter!', 'wp-newsletter-subscription'))); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Unsubscribe Email Subject', 'wp-newsletter-subscription'); ?></th>
                    <td><input type="text" name="wns_template_unsubscribe_subject" value="<?php echo esc_attr(get_option('wns_template_unsubscribe_subject', __('You Have Been Unsubscribed', 'wp-newsletter-subscription'))); ?>" class="regular-text" /></td>
                </tr>
            </table>

            <!-- New Post Notifications Section -->
            <h2><?php _e('New Post Notifications', 'wp-newsletter-subscription'); ?></h2>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Enable New Post Notifications', 'wp-newsletter-subscription'); ?></th>
                    <td>
                        <label><input type="checkbox" name="wns_enable_new_post_notification" value="1" <?php checked(1, get_option('wns_enable_new_post_notification', false)); ?> /> <?php _e('Send email to all subscribers when a new post is published.', 'wp-newsletter-subscription'); ?></label>
                        <p class="description"><?php _e('New post emails will include the featured image, title, excerpt, and a beautiful "Read More" button.', 'wp-newsletter-subscription'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('New Post Email Subject', 'wp-newsletter-subscription'); ?></th>
                    <td><input type="text" name="wns_template_new_post_subject" value="<?php echo esc_attr(get_option('wns_template_new_post_subject', __('New Blog Post: {post_title}', 'wp-newsletter-subscription'))); ?>" class="regular-text" /></td>
                </tr>
            </table>

            <!-- Social Media Links Section -->
            <h2><?php _e('Social Media Links', 'wp-newsletter-subscription'); ?></h2>
            <p class="description"><?php _e('These links will appear in the footer of all newsletter emails.', 'wp-newsletter-subscription'); ?></p>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Facebook URL', 'wp-newsletter-subscription'); ?></th>
                    <td><input type="url" name="wns_facebook_url" value="<?php echo esc_attr(get_option('wns_facebook_url', '')); ?>" class="regular-text" placeholder="https://facebook.com/yourpage" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Twitter URL', 'wp-newsletter-subscription'); ?></th>
                    <td><input type="url" name="wns_twitter_url" value="<?php echo esc_attr(get_option('wns_twitter_url', '')); ?>" class="regular-text" placeholder="https://twitter.com/youraccount" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Instagram URL', 'wp-newsletter-subscription'); ?></th>
                    <td><input type="url" name="wns_instagram_url" value="<?php echo esc_attr(get_option('wns_instagram_url', '')); ?>" class="regular-text" placeholder="https://instagram.com/youraccount" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('LinkedIn URL', 'wp-newsletter-subscription'); ?></th>
                    <td><input type="url" name="wns_linkedin_url" value="<?php echo esc_attr(get_option('wns_linkedin_url', '')); ?>" class="regular-text" placeholder="https://linkedin.com/company/yourcompany" /></td>
                </tr>
            </table>

            <!-- Email Sending Limits Section -->
            <h2><?php _e('Email Sending Limits', 'wp-newsletter-subscription'); ?></h2>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Emails Per Batch', 'wp-newsletter-subscription'); ?></th>
                    <td>
                        <input type="number" name="wns_email_batch_size" value="<?php echo esc_attr(get_option('wns_email_batch_size', 100)); ?>" min="1" max="1000" />
                        <p class="description"><?php _e('How many emails to send at once.', 'wp-newsletter-subscription'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Send Interval (minutes)', 'wp-newsletter-subscription'); ?></th>
                    <td>
                        <input type="number" name="wns_email_send_interval_minutes" value="<?php echo esc_attr(get_option('wns_email_send_interval_minutes', 5)); ?>" min="1" max="60" />
                        <p class="description"><?php _e('Time to wait between each batch of emails.', 'wp-newsletter-subscription'); ?></p>
                    </td>
                </tr>
            </table>

            <!-- Unsubscribe Settings Section -->
            <h2><?php _e('Unsubscribe Settings', 'wp-newsletter-subscription'); ?></h2>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Unsubscribe Page', 'wp-newsletter-subscription'); ?></th>
                    <td>
                        <?php
                        $pages = get_pages();
                        $selected = get_option('wns_unsubscribe_page_id', 0);
                        ?>
                        <select name="wns_unsubscribe_page_id">
                            <option value="0"><?php _e('-- Select a Page --', 'wp-newsletter-subscription'); ?></option>
                            <?php foreach ($pages as $page): ?>
                                <option value="<?php echo esc_attr($page->ID); ?>" <?php selected($selected, $page->ID); ?>>
                                    <?php echo esc_html($page->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description"><?php _e('Select a page that contains the [newsletter_unsubscribe] shortcode.', 'wp-newsletter-subscription'); ?></p>
                    </td>
                </tr>
            </table>

            <!-- Theme Integration Section -->
            <h2><?php _e('Theme Integration', 'wp-newsletter-subscription'); ?></h2>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Download Form Integration', 'wp-newsletter-subscription'); ?></th>
                    <td>
                        <p class="description">
                            <?php if (has_action('foxiz_subscribe', 'wns_handle_theme_download_subscription_enhanced')): ?>
                                <span style="color: green; font-weight: bold;">✓ <?php _e('Active', 'wp-newsletter-subscription'); ?></span> - <?php _e('Download form emails are automatically added to newsletter subscribers', 'wp-newsletter-subscription'); ?>
                            <?php else: ?>
                                <span style="color: red; font-weight: bold;">✗ <?php _e('Not detected', 'wp-newsletter-subscription'); ?></span> - <?php _e('Theme download form integration not found', 'wp-newsletter-subscription'); ?>
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

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
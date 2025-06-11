<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Professional Email Template System
 */
class WNS_Email_Templates {
    
    public static function get_email_wrapper($content, $title = '') {
        $site_name = get_bloginfo('name');
        $site_url = home_url();
        $logo_url = get_site_icon_url(200);
        
        // If no site icon, use a placeholder or skip the logo
        if (!$logo_url) {
            $logo_section = '';
        } else {
            $logo_section = '<img src="https://aistudynow.com/wp-content/uploads/2022/11/LOGO-2-DARK.png" alt="' . esc_attr($site_name) . '" style="max-width: 60px; height: auto; margin-bottom: 15px; border-radius: 8px;">';
        }
        
        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>' . esc_html($title ?: $site_name) . '</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8f9fa; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Arial, sans-serif; line-height: 1.6; color: #333333;">
    <div style="background-color: #f8f9fa; padding: 20px 0;">
        <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
            
            <!-- Header -->
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px 40px; text-align: center;">
                ' . $logo_section . '
                <h1 style="color: #ffffff; font-size: 24px; font-weight: 700; margin: 0; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">' . esc_html($site_name) . '</h1>
            </div>
            
            <!-- Body -->
            <div style="padding: 40px;">
                ' . $content . '
            </div>
            
            <!-- Footer -->
            <div style="background-color: #f8f9fa; padding: 30px 40px; text-align: center; border-top: 1px solid #e9ecef;">
                ' . self::get_social_links() . '
                <div style="font-size: 14px; color: #6c757d; margin: 15px 0;">
                    <p style="margin: 0 0 10px 0;">You\'re receiving this email because you subscribed to our newsletter.</p>
                    <p style="margin: 0;">
                        <a href="{unsubscribe_link}" style="color: #6c757d; text-decoration: underline; font-size: 12px;">Unsubscribe</a> | 
                        <a href="' . esc_url($site_url) . '" style="color: #6c757d; text-decoration: underline;">Visit our website</a>
                    </p>
                </div>
            </div>
            
        </div>
    </div>
</body>
</html>';
    }
    
    public static function get_new_post_template($post) {
        $post_title = get_the_title($post->ID);
        $post_url = get_permalink($post->ID);
        $post_excerpt = has_excerpt($post->ID) ? get_the_excerpt($post->ID) : wp_trim_words(strip_tags($post->post_content), 30);
        $post_date = get_the_date('F j, Y', $post->ID);
        $author_name = get_the_author_meta('display_name', $post->post_author);
        
        // Get featured image
        $featured_image = '';
        if (has_post_thumbnail($post->ID)) {
            $image_url = get_the_post_thumbnail_url($post->ID, 'large');
            $featured_image = '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($post_title) . '" style="width: 100%; height: 200px; object-fit: cover; display: block; border-radius: 8px 8px 0 0;">';
        }
        
        $content = '
        <div style="margin-bottom: 30px; text-align: center;">
            <h2 style="color: #2c3e50; font-size: 28px; font-weight: 700; margin: 0 0 20px 0;">
                🎉 New Post Published!
            </h2>
            <p style="color: #6c757d; font-size: 16px; margin: 0 0 30px 0;">
                We just published something new that we think you\'ll love.
            </p>
        </div>
        
        <div style="border: 1px solid #e9ecef; border-radius: 12px; overflow: hidden; margin-bottom: 30px;">
            ' . $featured_image . '
            <div style="padding: 25px;">
                <div style="margin-bottom: 20px; font-size: 14px; color: #6c757d;">
                    <span style="background-color: #f8f9fa; padding: 4px 12px; border-radius: 20px; font-weight: 500; margin-right: 15px;">' . esc_html($post_date) . '</span>
                    <span>By ' . esc_html($author_name) . '</span>
                </div>
                
                <h3 style="font-size: 22px; font-weight: 700; color: #2c3e50; margin: 0 0 15px 0; line-height: 1.3;">
                    <a href="' . esc_url($post_url) . '" style="color: #2c3e50; text-decoration: none;">' . esc_html($post_title) . '</a>
                </h3>
                
                <p style="color: #6c757d; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">' . esc_html($post_excerpt) . '</p>
                
                <div style="text-align: center; margin-top: 25px;">
                    <a href="' . esc_url($post_url) . '" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; padding: 12px 24px; border-radius: 25px; text-decoration: none; font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Read Full Article</a>
                </div>
            </div>
        </div>
        
        <div style="background-color: #f8f9fa; padding: 25px; border-radius: 8px; text-align: center; margin-top: 30px;">
            <h4 style="color: #2c3e50; margin: 0 0 15px 0;">Stay Connected</h4>
            <p style="color: #6c757d; margin: 0; font-size: 14px;">
                Don\'t miss out on our latest updates. Follow us on social media for more great content!
            </p>
        </div>';
        
        return self::get_email_wrapper($content, 'New Post: ' . $post_title);
    }
    
    public static function get_welcome_template($email) {
        $site_name = get_bloginfo('name');
        
        $content = '
        <div style="text-align: center; margin-bottom: 40px;">
            <h2 style="color: #2c3e50; font-size: 32px; font-weight: 700; margin: 0 0 15px 0;">
                Welcome to ' . esc_html($site_name) . '! 🎉
            </h2>
            <p style="color: #6c757d; font-size: 18px; margin: 0;">
                Thanks for joining our community of awesome readers!
            </p>
        </div>
        
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 12px; text-align: center; margin: 30px 0;">
            <h3 style="color: #ffffff; font-size: 24px; margin: 0 0 15px 0;">
                You\'re All Set! ✨
            </h3>
            <p style="color: #ffffff; font-size: 16px; margin: 0; opacity: 0.9;">
                You\'ll now receive our latest posts and exclusive content directly in your inbox.
            </p>
        </div>
        
        <div style="background-color: #f8f9fa; padding: 25px; border-radius: 8px; margin: 30px 0;">
            <h4 style="color: #2c3e50; margin: 0 0 15px 0; text-align: center;">What to Expect</h4>
            <ul style="color: #6c757d; padding-left: 20px; margin: 0;">
                <li style="margin-bottom: 8px;">📧 Weekly newsletter with our best content</li>
                <li style="margin-bottom: 8px;">🚀 Instant notifications for new posts</li>
                <li style="margin-bottom: 8px;">💎 Exclusive content just for subscribers</li>
                <li style="margin-bottom: 8px;">🎯 No spam, just quality content</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="' . esc_url(home_url()) . '" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; padding: 12px 24px; border-radius: 25px; text-decoration: none; font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Explore Our Website</a>
        </div>
        
        <div style="text-align: center; margin-top: 40px; padding-top: 30px; border-top: 1px solid #e9ecef;">
            <p style="color: #6c757d; font-size: 14px; margin: 0;">
                Have questions? Just reply to this email - we\'d love to hear from you!
            </p>
        </div>';
        
        return self::get_email_wrapper($content, 'Welcome to ' . $site_name);
    }
    
    public static function get_verification_template($verify_link) {
        $site_name = get_bloginfo('name');
        
        $content = '
        <div style="text-align: center; margin-bottom: 40px;">
            <h2 style="color: #2c3e50; font-size: 28px; font-weight: 700; margin: 0 0 15px 0;">
                Almost There! 🔐
            </h2>
            <p style="color: #6c757d; font-size: 16px; margin: 0;">
                Please verify your email address to complete your subscription.
            </p>
        </div>
        
        <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 25px; border-radius: 8px; margin: 30px 0; text-align: center;">
            <h3 style="color: #856404; margin: 0 0 15px 0;">⚡ One Click Away</h3>
            <p style="color: #856404; margin: 0 0 20px 0;">
                Click the button below to verify your email and start receiving our awesome content!
            </p>
            <a href="' . esc_url($verify_link) . '" style="display: inline-block; background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: #ffffff; padding: 12px 24px; border-radius: 25px; text-decoration: none; font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">
                Verify My Email
            </a>
        </div>
        
        <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 30px 0;">
            <p style="color: #6c757d; font-size: 14px; margin: 0; text-align: center;">
                <strong>Can\'t click the button?</strong> Copy and paste this link into your browser:<br>
                <a href="' . esc_url($verify_link) . '" style="color: #667eea; word-break: break-all;">' . esc_url($verify_link) . '</a>
            </p>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <p style="color: #6c757d; font-size: 14px; margin: 0;">
                This verification link will expire in 24 hours for security reasons.
            </p>
        </div>';
        
        return self::get_email_wrapper($content, 'Verify Your Email - ' . $site_name);
    }
    
    public static function get_newsletter_template($subject, $content) {
        $formatted_content = '
        <div style="margin-bottom: 30px;">
            <h2 style="color: #2c3e50; font-size: 28px; font-weight: 700; margin: 0 0 20px 0; text-align: center;">
                📬 ' . esc_html($subject) . '
            </h2>
        </div>
        
        <div style="background-color: #ffffff; padding: 0; border-radius: 8px; line-height: 1.6;">
            ' . wp_kses_post($content) . '
        </div>
        
        <div style="background-color: #f8f9fa; padding: 25px; border-radius: 8px; text-align: center; margin-top: 30px;">
            <h4 style="color: #2c3e50; margin: 0 0 15px 0;">Enjoying Our Content?</h4>
            <p style="color: #6c757d; margin: 0 0 15px 0; font-size: 14px;">
                Share this newsletter with friends who might be interested!
            </p>
            <a href="' . esc_url(home_url()) . '" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; padding: 12px 24px; border-radius: 25px; text-decoration: none; font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Visit Our Website</a>
        </div>';
        
        return self::get_email_wrapper($formatted_content, $subject);
    }
    
    private static function get_social_links() {
        $facebook = get_option('wns_facebook_url', '');
        $twitter = get_option('wns_twitter_url', '');
        $instagram = get_option('wns_instagram_url', '');
        $linkedin = get_option('wns_linkedin_url', '');
        
        $social_html = '<div style="margin: 20px 0;">';
        
        if ($facebook) {
            $social_html .= '<a href="' . esc_url($facebook) . '" target="_blank" style="display: inline-block; margin: 0 10px; width: 40px; height: 40px; background-color: #667eea; border-radius: 50%; text-align: center; line-height: 40px; color: #ffffff; text-decoration: none; font-size: 18px;">📘</a>';
        }
        if ($twitter) {
            $social_html .= '<a href="' . esc_url($twitter) . '" target="_blank" style="display: inline-block; margin: 0 10px; width: 40px; height: 40px; background-color: #667eea; border-radius: 50%; text-align: center; line-height: 40px; color: #ffffff; text-decoration: none; font-size: 18px;">🐦</a>';
        }
        if ($instagram) {
            $social_html .= '<a href="' . esc_url($instagram) . '" target="_blank" style="display: inline-block; margin: 0 10px; width: 40px; height: 40px; background-color: #667eea; border-radius: 50%; text-align: center; line-height: 40px; color: #ffffff; text-decoration: none; font-size: 18px;">📷</a>';
        }
        if ($linkedin) {
            $social_html .= '<a href="' . esc_url($linkedin) . '" target="_blank" style="display: inline-block; margin: 0 10px; width: 40px; height: 40px; background-color: #667eea; border-radius: 50%; text-align: center; line-height: 40px; color: #ffffff; text-decoration: none; font-size: 18px;">💼</a>';
        }
        
        $social_html .= '</div>';
        
        return $social_html;
    }
}
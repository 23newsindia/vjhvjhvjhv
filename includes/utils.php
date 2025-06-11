<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Utility functions shared across the plugin
 */

if (!function_exists('wns_get_unsubscribe_link')) {
    function wns_get_unsubscribe_link($email = '') {
        $unsubscribe_page = get_option('wns_unsubscribe_page_id');
        if ($unsubscribe_page && get_post_status($unsubscribe_page) === 'publish') {
            $unsubscribe_url = get_permalink($unsubscribe_page);
            if ($email) {
                $unsubscribe_url = add_query_arg('email', urlencode($email), $unsubscribe_url);
            }
            return $unsubscribe_url;
        } else {
            return home_url('/unsubscribe/');
        }
    }
}

if (!function_exists('wns_is_disposable_email')) {
    function wns_is_disposable_email($email) {
        $disposable_domains = array(
            '10minutemail.com', 'tempmail.org', 'guerrillamail.com', 'mailinator.com',
            'yopmail.com', 'temp-mail.org', 'throwaway.email', 'getnada.com'
        );
        
        $domain = substr(strrchr($email, "@"), 1);
        return in_array(strtolower($domain), $disposable_domains);
    }
}

if (!function_exists('wns_get_client_ip')) {
    function wns_get_client_ip() {
        $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = explode(',', $ip)[0];
                }
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return '127.0.0.1'; // Fallback
    }
}
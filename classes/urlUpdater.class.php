<?php

class SecureWordPress {

  public function __construct() {
      // Remove unnecessary header information
      remove_action('wp_head', 'wp_generator');
      remove_action('wp_head', 'rsd_link');
      remove_action('wp_head', 'wlwmanifest_link');
      remove_action('wp_head', 'wp_shortlink_wp_head');
      remove_action('wp_head', 'rest_output_link_wp_head');
      remove_action('wp_head', 'wp_oembed_add_discovery_links');
      remove_action('template_redirect', 'rest_output_link_header', 11, 0);

      // Remove the WordPress version number
      add_filter('the_generator', '__return_empty_string');

      // Change the default login URL to a custom URL
      add_filter('login_url', array($this, 'customLoginUrl'), 10, 2);

      // Disable XML-RPC to prevent brute force attacks
      add_filter('xmlrpc_enabled', '__return_false');
  }

  public function customLoginUrl($login_url, $redirect) {
      $custom_login_url = '/my-custom-login-url'; // Change this to your desired custom login URL
      $login_url = str_replace('wp-login.php', $custom_login_url, $login_url);
      return $login_url;
  }

}
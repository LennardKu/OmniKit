<?php
class CustomLogin {
  private $db;
  private $options;

  public function __construct() {
    global $wpdb;
    $this->db = $wpdb;
    $this->options = array();
    $this->loadOptions();
    $this->init();
  }

  private function loadOptions() {
    $results = $this->db->get_results("SELECT * FROM wp_custom_login_options");
    foreach ($results as $result) {
      $this->options[$result->slug] = $result->value;
    }
  }

  private function init() {
    add_action('login_enqueue_scripts', array($this, 'customLoginStyle'));
    add_action('login_head', array($this, 'customLoginLogo'));
    add_action('login_enqueue_scripts', array($this, 'customLoginColors'));
    add_action('login_enqueue_scripts', array($this, 'customLoginForm'));
  }

  public function customLoginStyle() {
    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/' . $this->options['css_file'] );
  }

  public function customLoginLogo() {
    $customLogoUrl = get_stylesheet_directory_uri() . '/' . $this->options['logo_file'];
    echo '<style>.login h1 a { background-image: url(' . $customLogoUrl . '); }</style>';
  }

  public function customLoginColors() {
    $style = '<style>';
    $style .= 'body.login { background-image: url("' . get_stylesheet_directory_uri() . '/' . $this->options['background_image_file'] . '"); background-size: cover; }';
    $style .= 'body.login form { background-color: ' . $this->options['form_background_color'] . '; }';
    $style .= 'body.login input[type="text"], body.login input[type="password"] { background-color: ' . $this->options['input_background_color'] . '; border: none; box-shadow: none; }';
    $style .= 'body.login input[type="submit"], body.login .button { background-color: ' . $this->options['button_background_color'] . '; border: none; box-shadow: none; text-shadow: none; }';
    $style .= 'body.login input[type="submit"]:hover, body.login .button:hover { background-color: ' . $this->options['button_hover_color'] . '; }';
    $style .= '</style>';
    echo $style;
  }

  public function customLoginForm() {
    add_filter('login_message', array($this, 'removeLoginLinks'));
  }

  public function removeLoginLinks($message) {
    $message = str_replace('<a href="https://wordpress.org/">', '<a style="display:none;" href="https://wordpress.org/">', $message);
    $message = str_replace('<p class="message register">', '<p style="display:none;" class="message register">', $message);
    return $message;
  }
}

new CustomLogin();

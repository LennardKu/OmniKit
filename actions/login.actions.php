<?php

// Function to enqueue the custom CSS file
function customLoginStyle() {
  wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/custom-login.css' );
}
add_action( 'login_enqueue_scripts', 'customLoginStyle' );

// Function to replace the WordPress logo with your own logo
function customLoginLogo() {
  $customLogoUrl = 'https://wp-test.lennardkuenen.dev/wp-content/uploads/2023/04/favicon-giesen.png';
  echo '<style>.login h1 a { background-image: url(' . $customLogoUrl . '); }</style>';
}
add_action( 'login_head', 'customLoginLogo' );

// Function to customize the login form and buttons
function customLoginColors() {
  echo '<style>body.login { background-image: url("custom-background.jpg"); background-size: cover; }
  body.login form { background-color: #f2f2f2; }
  body.login input[type="text"], body.login input[type="password"] { background-color: #fff; border: none; box-shadow: none; }
  body.login input[type="submit"], body.login .button { background-color: #0073aa; border: none; box-shadow: none; text-shadow: none; }
  body.login input[type="submit"]:hover, body.login .button:hover { background-color: #005b8e; }</style>';
}
add_action( 'login_enqueue_scripts', 'customLoginColors' );

// Function to remove the language and register links from the login form
function customLoginForm() {
  add_filter( 'login_message', 'removeLoginLinks' );
}
function removeLoginLinks( $message ) {
  $message = str_replace( '<a href="https://wordpress.org/">', '<a style="display:none;" href="https://wordpress.org/">', $message );
  $message = str_replace( '<p class="message register">', '<p style="display:none;" class="message register">', $message );
  return $message;
}
add_action( 'login_enqueue_scripts', 'customLoginForm' );

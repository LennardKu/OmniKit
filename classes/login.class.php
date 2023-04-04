<?php

class OmniKitLoginLayout {

  public function __construct() {
    add_action( 'login_enqueue_scripts', array( $this, 'customLoginStyles' ) );
    add_filter( 'login_headerurl', array( $this, 'customLoginLogoUrl' ) );
    add_filter( 'login_headertext', array( $this, 'customLoginLogoTitle' ) );
    add_filter( 'login_message', array( $this, 'customLoginMessage' ) );
    //  ! add_action( 'login_form', array( $this, 'customLoginForm' ) );
  }

  // Add custom styles to the login page
  public function customLoginStyles() {
    wp_enqueue_style( 'custom-login-styles', OmniKitUrl() . '/src/css/login.php' );
  }

  // Change the login logo URL
  public function customLoginLogoUrl() {
    return home_url();
  }

  // Change the login logo title
  public function customLoginLogoTitle() {
    return get_bloginfo( 'name' );
  }

  // Customize the login message
  public function customLoginMessage( $message ) {
    return '<p class="message">Enter your custom message here.</p>';
  }

  // Customize the login form
  public function customLoginForm() {
    $usernameLabel = __( 'Username or Email Address' );
    $passwordLabel = __( 'Password' );
    $submitLabel = __( 'Log In' );

    $userLogin = isset( $_POST['log'] ) ? $_POST['log'] : '';

    $loginForm = '<p class="login-username">
      <label for="user_login">' . $usernameLabel . '</label>
      <input type="text" name="log" id="user_login" class="input" value="' . esc_attr( $userLogin ) . '" size="20" autocapitalize="off" />
    </p>
    <p class="login-password">
      <label for="user_pass">' . $passwordLabel . '</label>
      <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" />
    </p>
    <p class="login-submit">
      <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="' . esc_attr( $submitLabel ) . '" />
    </p>';

    echo $loginForm;
  }
}

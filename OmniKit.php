<?php
/**
 * Plugin Name: OmniKit
 * Plugin URI: https://github.com/LennardKu/wordpress-multitool
 * Version: 1.0.0
 * Author: Lennard
 * Author URI: https://github.com/LennardKu/wordpress-multitool
 * Description: This plugin makes it easy to manage you're site
 */

// Default values
define('OmniKitVersion', '1.0.0');
define("OmniKitPluginLocation", dirname(__FILE__));
define("OmniKitPluginName", "OmniKit");

$debug = true;
if(isset($debug) && $debug == true){ error_reporting(E_ALL); ini_set('display_errors', 'On'); } // Debug state

// Auto load 
foreach (glob(dirname(__FILE__) . "/classes/*.class.php") as $filename){
  require_once($filename);
}

foreach (glob(dirname(__FILE__)  . "/functions/*.function.php") as $filename){
  require_once($filename);
}

foreach (glob(dirname(__FILE__)  . "/actions/*.actions.php") as $filename){
  require_once($filename);
}

// Navigation 
include OmniKitFolder().'/includes/navigation.php';

// This function will be called when the plugin is activated
function OmniKitActivate() {
  OmniKitCreateDatabases();
}

// Start session
function register_my_session()
{
  if( !session_id() )
  {
    session_start();
  }
}

add_action('init', 'register_my_session');

// Register the activation hook
register_activation_hook(__FILE__, 'OmniKitActivate');

// Initialize all classes
// ! $secure_wordpress = new SecureWordPress(); 
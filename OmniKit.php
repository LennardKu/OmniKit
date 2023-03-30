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
  define('MY_PLUGIN_VERSION', '1.0.0');
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

  
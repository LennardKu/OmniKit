<?php

function singleVariable($attr = array()) {
  global $wpdb;
  $slug = (isset($attr['slug']) ? $attr['slug'] : '');

  $dbName = $wpdb->prefix . "globalVariables";
  $variable = $wpdb->get_results("SELECT * FROM $dbName WHERE `slug` = '$slug'");

  if(empty($variable)){
    echo '';
  }
  
  echo $variable[0]->value;
} add_shortcode('omnikit-variable', 'singleVariable');

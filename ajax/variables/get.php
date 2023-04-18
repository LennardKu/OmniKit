<?php
include substr(realpath(__FILE__), 0, strpos(realpath(__FILE__), "/wp-content/")) . "/wp-config.php";

// Page access
OmniKitPageAccess();

if(isset($_GET['all'])){
  $variables = array();
  global $wpdb;
  $dbName = $wpdb->prefix . "globalVariables";
  $createdVariables = $wpdb->get_results("SELECT * FROM $dbName");

  foreach($createdVariables as $key => $variable){
    array_push($variables, array('name'=>$variable->name,'id'=>$variable->id,'slug'=>$variable->slug,'content'=>$variable->value));
  }

  echo json_encode($variables);
  exit;
}

if(isset($_GET['single'])){
  $id = $_GET['single'];
  global $wpdb;
  $dbName = $wpdb->prefix . "globalVariables";
  $variable = $wpdb->get_results("SELECT * FROM $dbName WHERE `id` = $id");


  echo json_encode($variable);
  exit;
}

echo json_encode(array('error'=>true));

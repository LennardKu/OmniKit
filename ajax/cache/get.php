<?php
include substr(realpath(__FILE__), 0, strpos(realpath(__FILE__), "/wp-content/")) . "/wp-config.php";

// Page access
OmniKitPageAccess();

// Options
if(isset($_GET['options'])){



  exit;
}

// Get all cached pages
if(isset($_GET['list'])){
  $variables = array();
  global $wpdb;
  $dbName = $wpdb->prefix . "OmniKitData";
  $createdVariables = $wpdb->get_results("SELECT * FROM $dbName WHERE `slug` = 'cachedPage'");
  foreach($createdVariables as $key => $variable){
    $data = json_decode($variable->value,true);
    if(!isset($data['url'])){ continue; }
    array_push($variables, array('name'=>$variable->name,'id'=>$variable->id,'page'=>$data['url'],'date'=>$variable->created_at));
  }

  echo json_encode($variables);
  exit;
}


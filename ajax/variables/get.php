<?php
include substr(realpath(__FILE__), 0, strpos(realpath(__FILE__), "/wp-content/")) . "/wp-config.php";

// Page access
OmniKitPageAccess();



if(isset($_GET['all'])){
  $variables = array();

  array_push($variables, array('name'=>'test','id'=>'1'));

  echo json_encode($variables);
  exit;
}

echo json_encode(array('name'=>'test','id'=>'1'));

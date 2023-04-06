<?php
include substr(realpath(__FILE__), 0, strpos(realpath(__FILE__), "/wp-content/")) . "/wp-config.php";

// Page access
OmniKitPageAccess();

if(isset($_GET['submit'])){
  $name = (isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '');
  $slug = (isset($_POST['slug']) ? htmlspecialchars($_POST['slug']) : '');
  $content = (isset($_POST['content']) ? $_POST['content'] : '');


  echo json_encode(array('success'=>true,'title'=>'Variable aangemaakt','content'=>'De variable is aangemaakt'));
  exit;
}

echo json_encode(array('error'=>true));
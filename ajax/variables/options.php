<?php
include substr(realpath(__FILE__), 0, strpos(realpath(__FILE__), "/wp-content/")) . "/wp-config.php";

// Page access
OmniKitPageAccess();

if(isset($_GET['submit'])){
  $name = (isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '');
  $slug = (isset($_POST['slug']) ? strtolower(str_replace(' ','-',(htmlspecialchars($_POST['slug'])))) : '');
  $content = (isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '');

  $dbName = $wpdb->prefix . "globalVariables";
  $variable = $wpdb->get_results("SELECT * FROM $dbName WHERE `slug` = '$slug'");

  if(!empty($variable)){
    echo json_encode(array('error'=>true,'title'=>'Slug bestaat al','content'=>'Het lijkt erop dat de slug al bestaat'));
    exit;
  }

  global $wpdb;
  $dbName = $wpdb->prefix . "globalVariables";
  $wpdb->insert($dbName, array(
    'name' => $name,
    'slug' => $slug,
    'value' => $content, 
  ));

  echo json_encode(array('success'=>true,'title'=>'Variable aangemaakt','content'=>'De variable is aangemaakt'));
  exit;
}

if(isset($_GET['change'])){
  $id = $_GET['change'];
  $name = (isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '');
  $slug = (isset($_POST['slug']) ? strtolower(str_replace(' ','-',(htmlspecialchars($_POST['slug'])))) : '');
  $content = (isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '');

  $dbName = $wpdb->prefix . "globalVariables";
  $variable = $wpdb->get_results("SELECT * FROM $dbName WHERE `slug` = '$slug'");

  if(!empty($variable)){
    if($variable[0]->id !== $id){
      echo json_encode(array('error'=>true,'title'=>'Slug bestaat al','content'=>'Het lijkt erop dat de slug al bestaat'));
      exit;
    }
  }

  $wpdb->update($dbName, array('value'=>$content, 'name'=>$name, 'slug'=>$slug), array('id'=>$id));
  echo json_encode(array('success'=>true,'title'=>'Variable Gewijzigd','content'=>'De variable is gewijzigd'));
  exit;
}

if(isset($_GET['delete'])){
  $id = (isset($_POST['id']) ? $_POST['id'] : '');

  $dbName = $wpdb->prefix . "globalVariables";
  $variable = $wpdb->delete( $dbName, array( 'id' => $id ) );
  echo json_encode(array('success'=>true,'title'=>'Variable verwijderd','content'=>'De variable is verwijderd'));
  exit;
}

echo json_encode(array('error'=>true));
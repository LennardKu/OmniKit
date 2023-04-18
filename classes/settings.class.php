<?php
/*
* OmniKit settings class
*/

class OmniKitData {
  private $wpdb;
  
  public function __construct () {
    global $wpdb;
    $this->wpdb = $wpdb;
  }


  // Get value only by slug
  public function getSetting ($slug = '') {
      
    $dbName = $this->wpdb->prefix . "OmniKitSettings";
    $variable = $this->wpdb->get_results("SELECT * FROM $dbName WHERE `slug` = '$slug'");
    if(empty($variable)){
      return array('error'=> true);
    }
    return json_decode($variable['value']);
  }

  public function deleteSetting ($value = '',$deleteBy = 'slug') {
    
  }

  public function createSetting (string $name = '', string $slug = '',  $value = array()) {
    $dbName = $this->wpdb->prefix . "OmniKitData";
    $this->wpdb->insert($dbName, array(
      'name' => $name,
      'slug' => $slug,
      'value' => json_encode($value), 
    ));
  }

  public function updateSettings () {

  }

}
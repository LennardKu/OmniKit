<?php

function OmniKitCreateDatabases(){

  $databases = array(

    'globalVariables'=>array(
      'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT',
      'name' => 'varchar(255) NOT NULL',
      'slug' => 'varchar(255) NOT NULL',
      'value'  => 'varchar(255) NOT NULL',
    ),

    
    // 'globalVariables'=>array(
    //   'id' => 'bigint(20) unsigned NOT NULL AUTO_INCREMENT',
    //   'name' => 'varchar(255) NOT NULL',
    //   'slug' => 'varchar(255) NOT NULL',
    //   'value'  => 'varchar(255) NOT NULL',
    //   'status'  => 'varchar(255) NOT NULL',
    // ),
    
  );

  foreach($databases as $databaseName => $database){
    $dataHandler = new WordPressDataHandler();
    $dataHandler->createTable($databaseName, $database);
  }
}

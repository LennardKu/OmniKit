<?php
include substr(realpath(__FILE__), 0, strpos(realpath(__FILE__), "/wp-content/")) . "/wp-config.php";

// Page access
OmniKitPageAccess();

if(isset($_GET['cachePages'])){
  $offset = (isset($_GET['offset']) ? $_GET['offset'] : 0);

  if($offset == 0 && !isset($_SESSION['postTypeOffset'])){
    $cache = new OmniKitCache();
    $cache->deleteAllCachedPages();
  }

  $pagesCached = array();
  $postTypesOrder = get_post_types();
  
  if(!isset($_SESSION['postTypeOffset'])){
    $_SESSION['postTypeOffset'] = 0;
  }

  $postTypes = array();
  foreach($postTypesOrder as $postType){
    array_push($postTypes,$postType);
  }

  if(isset($postTypes[$_SESSION['postTypeOffset']])){

    $args = array(
      'post_type' => array($postTypes[$_SESSION['postTypeOffset']]),
      'post_status' => 'publish',
      'posts_per_page' => 5,
      'offset' => $offset,
    );

    $query = new WP_Query( $args );
    $pagesCached = array();

    if ( $query->have_posts() ) {
      while ( $query->have_posts() ) {
          $query->the_post();
          $cache = new OmniKitCache();
          $cache->cachePage(get_the_permalink(get_the_ID()));

          array_push($pagesCached,array('name'=> get_the_title(get_the_ID()),'url'=>get_the_permalink(get_the_ID())));
      }
    }
  }
  
  $lastOffset = $newOffset = false;
  $postTypeOffset = $_SESSION['postTypeOffset'];
  if((count($pagesCached) < $offset)){
    $_SESSION['postTypeOffset']++;
    $newOffset = true;
    if(count($postTypes) < $postTypeOffset){
      $lastOffset = true;
      unset($_SESSION['postTypeOffset']);
    }
  }  

  echo json_encode(array('last'=>$lastOffset,'pages'=>$pagesCached,'newOffset'=>$newOffset,'postTypeOffset'=>$postTypeOffset));
  exit;
}

if(isset($_GET['delete'])){  
  $cache = new OmniKitCache();
  $cache->deleteAllCachedPages();
  echo json_encode(array('success'=>true,'title'=>'Cache verwijderd'));
  exit;
}
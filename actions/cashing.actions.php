<?php
// Cache single page 
function cacheSinglePage (){
  if(isset($_GET['cacheCurrentPage'])){
    $token = $_GET['cacheCurrentPage'];
    if( !is_user_logged_in() ){ return; }
    
    $currentUrl = str_replace('?cacheCurrentPage=' . $token, '',currentUrl());
    $cache = new OmniKitCache();
    $cacheFilepath = $cache->checkCached($currentUrl);
    if ($cacheFilepath) {
      echo file_get_contents($cacheFilepath);
      exit;
    }else{
      $cache->cachePage($currentUrl);
    }
  }else{
    $currentUrl = currentUrl();
    $cache = new OmniKitCache();
    $cacheFilepath = $cache->checkCached($currentUrl);
    if ($cacheFilepath) {
      echo file_get_contents($cacheFilepath);
      exit;
    }
  }
} add_action('init', 'cacheSinglePage');


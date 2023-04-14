<?php
include substr(realpath(__FILE__), 0, strpos(realpath(__FILE__), "/wp-content/")) . "/wp-config.php";

// Page access
OmniKitPageAccess();

if(isset($_GET['cachePages'])){
  $offset = (isset($_GET['offset']) ? $_GET['offset'] : 0);

  if($offset == 0){
    $cache = new OmniKitCache();
    $cache->deleteAllCachedPages();
  }

  $args = array(
    'post_type' => array( 'page', 'post', 'custom_post_type' ),
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

  // Frontpage 
  // if($offset == 0){
  //   $cache = new OmniKitCache();
  //   $cache->cachePage(get_the_permalink( (get_bloginfo('url').'/')));
  //   array_push($pagesCached,array('name'=> get_the_title( (get_bloginfo('url').'/') ),'url'=>get_the_permalink( (get_bloginfo('url').'/') )));
  // }

  echo json_encode(array('last'=>(count($pagesCached) < $offset ? true : false),'pages'=>$pagesCached));
  exit;
}
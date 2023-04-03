<?php

function getSiteUrl(){
  if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
    $url = "https://";   
  }else{
    $url = "http://";
  }

  $url.= $_SERVER['HTTP_HOST'];   

  return $url;
}

function OmniKitFolder () {
  return OmniKitPluginLocation;
}

function OmniKitUrl () {
  return str_replace($_SERVER['DOCUMENT_ROOT'],  getSiteUrl(), OmniKitPluginLocation);
}
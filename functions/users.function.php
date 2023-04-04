<?php

function OmniKitPageAccess (string $type = 'jsonResponse') {
   if ( !is_user_logged_in() ) { 
    if($type == 'jsonResponse'){
      echo json_encode(array('error'=>true,'message'=>'notLoggedIn'));
      exit;
    }
   }
  
}
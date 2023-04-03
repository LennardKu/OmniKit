<?php

/*
* Navigation OmniKit
*/
function OmniKitNavigation(){
    if ( current_user_can( 'manage_options' ) ) {

        // Menu 
        add_menu_page('OmniKit', 'OmniKit', 'manage_options', 'OmniKit', 'OmniKitDashboard',OmniKitUrl('/src/images/Simplix-favicon.svg',__DIR__));

        // Style  
        wp_enqueue_style('OmniKitStyle', OmniKitUrl().'/src/css/style.css'  );

        // Submenu 
        add_submenu_page( 'OmniKit', 'Standaard waarden', 'Standaard waarden', 'manage_options', 'OmniKit', 'OmniKitDashboard');

    }

} add_action('admin_menu', 'OmniKitNavigation');

/*
*   Panels
*/
function OmniKitDashboard(){
    include OmniKitFolder().'/panels/dashboard/main.panel.php';
}


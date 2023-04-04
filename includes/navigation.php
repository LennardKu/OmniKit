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
        wp_enqueue_style('OmniKitBootstrap', OmniKitUrl().'/src/css/bootstrap.css'  );
        wp_enqueue_script('OmniKitScript', OmniKitUrl().'/src/js/main.js'  );

        // Submenu 
        add_submenu_page( 'OmniKit', 'Standaard waarden', 'Standaard waarden', 'manage_options', 'OmniKitVariables', 'OmniKitVariables');

    }

} add_action('admin_menu', 'OmniKitNavigation');

/*
*   Dashboard
*/
function OmniKitDashboard(){
    echo '<main class="omnikit-wrapper">';
        include OmniKitFolder().'/panels/dashboard/main.panel.php';
    echo '</main>';
}

/*
*   
*/
function OmniKitVariables(){
    echo '<main class="omnikit-wrapper">';
        include OmniKitFolder().'/panels/variables/dashboard.panel.php';
    echo '</main>';
}


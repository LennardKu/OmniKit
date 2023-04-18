<?php

/*
* Navigation OmniKit
*/
function OmniKitNavigation(){
    if ( current_user_can( 'manage_options' ) ) {

        // Menu 
        add_menu_page('OmniKit', 'OmniKit', 'manage_options', 'OmniKit', 'OmniKitDashboard',OmniKitUrl('/src/images/OmniKit-favicon.svg',__DIR__));

        // Style  
        wp_enqueue_style('OmniKitStyle', OmniKitUrl().'/src/css/style.css'  );
        wp_enqueue_style('OmniKitFontAwesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css'  );
        wp_enqueue_script('OmniKitScript', OmniKitUrl().'/src/js/main.js'  );

        // Submenu 
        add_submenu_page( 'OmniKit', 'Standaard waarden', 'Standaard waarden', 'manage_options', 'OmniKitVariables', 'OmniKitVariables');
        add_submenu_page( 'OmniKit', 'Beveiliging', 'Beveiliging', 'manage_options', 'OmniKitSecurity', 'OmniKitSecurity');
        add_submenu_page( 'OmniKit', 'Cache', 'Cache', 'manage_options', 'OmniKitCache', 'OmniKitCache');

    }

} add_action('admin_menu', 'OmniKitNavigation');

/*
*   Dashboard
*/
function OmniKitDashboard(){
    wp_enqueue_script('OmniKitTailwind', OmniKitUrl().'/src/js/tailwind.js'  );
    echo '<main class="omnikit-wrapper">';
        include OmniKitFolder().'/panels/dashboard/main.panel.php';
    echo '</main>';
}

/*
*   Global variables
*/
function OmniKitVariables(){
    wp_enqueue_script('OmniKitTailwind', OmniKitUrl().'/src/js/tailwind.js'  );
    echo '<main class="omnikit-wrapper">';
        include OmniKitFolder().'/panels/variables/dashboard.panel.php';
    echo '</main>';
}

/*
*   OmniKitSecurity
*/
function OmniKitSecurity () {
    wp_enqueue_script('OmniKitTailwind', OmniKitUrl().'/src/js/tailwind.js'  );
    echo '<main class="omnikit-wrapper">';
        include OmniKitFolder().'/panels/security/main.panel.php';
    echo '</main>';
}

/*
*   OmniKitCache
*/
function OmniKitCache () {
    wp_enqueue_script('OmniKitTailwind', OmniKitUrl().'/src/js/tailwind.js'  );
    echo '<main class="omnikit-wrapper">';
        include OmniKitFolder().'/panels/cache/main.panel.php';
    echo '</main>';
}

